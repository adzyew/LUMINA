<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Models\Order;
use App\Mail\TestMail;
use App\Services\CloudinaryService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

class AuthController extends Controller
{
    private const OTP_MAX_ATTEMPTS = 3;
    private const OTP_LOCK_MINUTES = 5;
    private const OTP_EXPIRES_MINUTES = 2;
    private const OTP_RESEND_COOLDOWN_MINUTES = 2;

    private function secondsUntil(mixed $value): int
    {
        if (!$value) {
            return 0;
        }

        if ($value instanceof Carbon) {
            return max(0, $value->getTimestamp() - time());
        }

        try {
            return max(0, Carbon::parse((string) $value)->getTimestamp() - time());
        } catch (\Throwable $e) {
            return 0;
        }
    }

    private function getOtpLockRemainingSeconds(string $lockKey): int
    {
        $remainingSeconds = $this->secondsUntil(Cache::get($lockKey));
        $maxLockSeconds = self::OTP_LOCK_MINUTES * 60;

        // Normalize legacy lock entries that were created before policy was changed.
        if ($remainingSeconds > $maxLockSeconds) {
            $newLockUntil = now()->addMinutes(self::OTP_LOCK_MINUTES);
            Cache::put($lockKey, $newLockUntil, $newLockUntil);
            return $maxLockSeconds;
        }

        return $remainingSeconds;
    }

    private function activeSessionCacheKey(User $user): string
    {
        return 'active_privileged_session_' . $user->id;
    }

    private function hasDifferentActiveSession(User $user, string $currentSessionId): bool
    {
        if (!$user->isPrivilegedStaff()) {
            return false;
        }

        $existingSessionId = Cache::get($this->activeSessionCacheKey($user));
        return is_string($existingSessionId) && $existingSessionId !== '' && $existingSessionId !== $currentSessionId;
    }

    private function rememberActiveSession(User $user, string $sessionId): void
    {
        if (!$user->isPrivilegedStaff()) {
            return;
        }

        $ttl = now()->addMinutes((int) config('session.lifetime', 120));
        Cache::put($this->activeSessionCacheKey($user), $sessionId, $ttl);
    }

    private function clearActiveSession(User $user, ?string $sessionId = null): void
    {
        if (!$user->isPrivilegedStaff()) {
            return;
        }

        $key = $this->activeSessionCacheKey($user);
        $existingSessionId = Cache::get($key);

        if ($sessionId !== null && is_string($existingSessionId) && $existingSessionId !== $sessionId) {
            return;
        }

        Cache::forget($key);
    }

    private function cartCacheKey(User $user): string
    {
        return 'cart_user_' . $user->id;
    }

    private function mergeCarts(array $baseCart, array $incomingCart): array
    {
        foreach ($incomingCart as $productId => $item) {
            if (!is_array($item)) {
                continue;
            }

            $qty = (int) ($item['quantity'] ?? 0);
            if ($qty <= 0) {
                continue;
            }

            if (isset($baseCart[$productId])) {
                $baseCart[$productId]['quantity'] = ((int) ($baseCart[$productId]['quantity'] ?? 0)) + $qty;
                continue;
            }

            $baseCart[$productId] = [
                'name' => (string) ($item['name'] ?? 'Product'),
                'quantity' => $qty,
                'price' => (float) ($item['price'] ?? 0),
                'image' => (string) ($item['image'] ?? ''),
            ];
        }

        return $baseCart;
    }

    private function restoreCartAfterLogin(Request $request, User $user): void
    {
        $sessionCart = $request->session()->get('cart', []);
        $storedCart = Cache::get($this->cartCacheKey($user), []);

        if (!is_array($sessionCart)) {
            $sessionCart = [];
        }
        if (!is_array($storedCart)) {
            $storedCart = [];
        }

        $mergedCart = $this->mergeCarts($storedCart, $sessionCart);
        if ($mergedCart === []) {
            return;
        }

        $request->session()->put('cart', $mergedCart);
        Cache::put($this->cartCacheKey($user), $mergedCart, now()->addDays(30));
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function showVerifySms(Request $request)
    {
        if (Auth::check() && Auth::user()->is_verified) {
            return redirect()->route('dashboard');
        }

        $email = session('email');
        if (!$email && Auth::check() && !Auth::user()->is_verified) {
            $email = Auth::user()->email;
            session(['email' => $email]);
        }

        if (!$email) {
            return redirect()->route('login')->with('error', 'Session expired. Please login again.');
        }

        $resendAvailableAt = Cache::get('otp_resend_available_at_' . $email);
        $remainingSeconds = $this->secondsUntil($resendAvailableAt);
        $otpExpiresAt = Cache::get('otp_expires_' . $email);
        $otpExpiresAtTs = $otpExpiresAt ? Carbon::parse((string) $otpExpiresAt)->getTimestamp() : 0;

        $lockRemainingSeconds = $this->getOtpLockRemainingSeconds('otp_lock_until_' . $email);
        $lockUntil = Cache::get('otp_lock_until_' . $email);
        $lockExpiresAtTs = $lockUntil ? Carbon::parse((string) $lockUntil)->getTimestamp() : 0;

        $attemptsUsed = (int) Cache::get('otp_attempts_' . $email, 0);
        $attemptsRemaining = max(0, self::OTP_MAX_ATTEMPTS - $attemptsUsed);
        if ($lockRemainingSeconds > 0) {
            $attemptsRemaining = 0;
        }

        return view('auth.verify-sms', compact('remainingSeconds', 'lockRemainingSeconds', 'attemptsRemaining', 'otpExpiresAtTs', 'lockExpiresAtTs'));
    }

    public function showRegister()
    {
        return view('auth.register');
    }
   

    public function registerPost(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:30', 'regex:/^[\pL\s]+$/u'],
            'last_name' => ['required', 'string', 'max:30', 'regex:/^[\pL\s]+$/u'],
            'phone' => ['required', 'regex:/^09\\d{9}$/'],
            'email' => 'required|email|unique:users',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'confirmed',
            ],
            'terms' => 'accepted',
        ], [
            'first_name.regex' => 'First name must contain letters only.',
            'last_name.regex' => 'Last name must contain letters only.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.regex' => 'Password must include uppercase, lowercase, and a number.',
            'phone.regex' => 'Please enter a valid Philippine mobile number (e.g. 09171234567).',
        ]);

        // Combine first and last name
        $fullName = trim($request->first_name . ' ' . $request->last_name);

        // 1) Store pending registration in cache (no DB record until OTP is verified)
        $pendingRegistration = [
            'name' => $fullName,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ];

        $pendingTtl = now()->addHours(2);
        Cache::put('pending_registration_' . $request->email, $pendingRegistration, $pendingTtl);

        // 2) Generate OTP
        $otp = rand(100000, 999999);

        $expiresAt = now()->addMinutes(self::OTP_EXPIRES_MINUTES);
        $resendAvailableAt = now()->addMinutes(self::OTP_RESEND_COOLDOWN_MINUTES);

        Cache::put('otp_' . $request->email, $otp, $expiresAt);
        Cache::put('otp_expires_' . $request->email, $expiresAt, $expiresAt);
        Cache::put('otp_resend_available_at_' . $request->email, $resendAvailableAt, $resendAvailableAt);

        // 3) Send OTP email
        try {
            Mail::to($request->email)->send(new TestMail($otp));
        } catch (\Exception $e) {
            Log::error('Registration OTP email failed: ' . $e->getMessage());
        }

        // 4) Save email in session
        session([
            'email' => $request->email,
        ]);

        return redirect()->route('verify-sms');
    }


    public function user_dashboard(Request $request)
    {
        $user = Auth::user();
        if (!$user instanceof User) {
            abort(403);
        }

        $ordersQuery = $user->orders()
            ->where('status', '!=', 'awaiting_payment')
            ->where('status', '!=', 'draft');

        $orders = (clone $ordersQuery)
            ->with('items.product')
            ->latest()
            ->paginate(3)
            ->withQueryString();

        $pendingStatuses = ['pending', 'confirmed', 'processing'];
        $spentStatuses = ['confirmed', 'processing', 'shipped', 'delivered'];

        $totalPurchases = (clone $ordersQuery)->count();
        $pendingPurchases = (clone $ordersQuery)->whereIn('status', $pendingStatuses)->count();
        $completedPurchases = (clone $ordersQuery)->where('status', 'delivered')->count();
        $totalSpent = (float) (clone $ordersQuery)->whereIn('status', $spentStatuses)->sum('total_price');

        $recentPurchases = (clone $ordersQuery)
            ->where('created_at', '>=', now()->subDays(30))
            ->count();

        $statusCountsRaw = (clone $ordersQuery)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $statusLabels = ['Pending', 'Confirmed', 'Processing', 'Shipped', 'Delivered', 'Cancelled'];
        $statusKeys = ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'];
        $statusChartData = collect($statusKeys)
            ->map(fn ($key) => (int) ($statusCountsRaw[$key] ?? 0))
            ->values()
            ->all();

        $startMonth = now()->startOfMonth()->subMonths(5);
        $monthlyTotalsRaw = (clone $ordersQuery)
            ->whereIn('status', $spentStatuses)
            ->where('created_at', '>=', $startMonth)
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month_key, SUM(total_price) as total")
            ->groupBy('month_key')
            ->pluck('total', 'month_key');

        $spendingChartLabels = [];
        $spendingChartData = [];
        for ($i = 0; $i < 6; $i++) {
            $month = $startMonth->copy()->addMonths($i);
            $key = $month->format('Y-m');
            $spendingChartLabels[] = $month->format('M Y');
            $spendingChartData[] = (float) ($monthlyTotalsRaw[$key] ?? 0);
        }

        return view('user.user_dashboard', compact(
            'user',
            'orders',
            'totalPurchases',
            'pendingPurchases',
            'completedPurchases',
            'totalSpent',
            'recentPurchases',
            'statusLabels',
            'statusChartData',
            'spendingChartLabels',
            'spendingChartData'
        ));
    }

    public function orders(Request $request)
    {
        $user = Auth::user();
        if (!$user instanceof User) {
            abort(403);
        }

        $allowedStatuses = ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'];
        $selectedStatus = strtolower((string) $request->query('status', ''));
        if (!in_array($selectedStatus, $allowedStatuses, true)) {
            $selectedStatus = '';
        }

        $ordersQuery = $user->orders()
            ->where('status', '!=', 'awaiting_payment')
            ->with('items.product');

        if ($selectedStatus !== '') {
            $ordersQuery->where('status', $selectedStatus);
        }

        $orders = $ordersQuery
            ->latest()
            ->paginate(3)
            ->withQueryString();

        return view('user.orders', compact('orders', 'selectedStatus', 'allowedStatuses'));
    }

    public function showOrder(Order $order)
    {
        $user = Auth::user();
        if ($order->user_id !== $user->id) {
            abort(403);
        }

        if ($order->status === 'awaiting_payment') {
            return redirect()->route('orders.index')->with('info', 'This order is awaiting payment confirmation.');
        }

        $order->load('items.product');

        return view('user.order_detail', compact('order'));
    }

    public function showProfile()
    {
        return redirect()->route('profile.edit');
    }

    

    public function editProfile()
    {
        $user = Auth::user();
        if (!$user instanceof User) {
            abort(403);
        }

        $ordersQuery = $user->orders()->where('status', '!=', 'awaiting_payment');
        $totalOrders = (clone $ordersQuery)->count();
        $deliveredOrders = (clone $ordersQuery)->where('status', 'delivered')->count();
        $pendingOrders = (clone $ordersQuery)->whereIn('status', ['pending', 'confirmed', 'processing'])->count();
        $lifetimeSpend = (float) (clone $ordersQuery)->whereIn('status', ['confirmed', 'processing', 'shipped', 'delivered'])->sum('total_price');

        return view('user.profile_edit', compact(
            'user',
            'totalOrders',
            'deliveredOrders',
            'pendingOrders',
            'lifetimeSpend'
        ));
    }

    public function helpSupport()
    {
        return view('user.help_support');
    }

    public function updateProfile(Request $request, CloudinaryService $cloudinary)
    {
        $validated = $request->validate([
            'active_tab' => 'nullable|string',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => ['required', 'regex:/^09\\d{9}$/'],
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'current_password' => 'nullable|required_with:new_password|string|max:72',
            'new_password' => [
                'nullable',
                'string',
                'min:8',
                'max:72',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'confirmed',
            ],
            'shipping_street' => 'nullable|string|max:255',
            'shipping_secondary_address' => 'nullable|string|max:255',
            'shipping_city' => 'nullable|string|max:100',
            'shipping_barangay' => 'nullable|string|max:100',
            'shipping_region' => 'nullable|string|max:100',
            'shipping_postal_code' => 'nullable|string|max:20',
            'notify_order_updates' => 'nullable|boolean',
            'notify_promotions' => 'nullable|boolean',
            'notify_loyalty' => 'nullable|boolean',
        ], [
            'phone.regex' => 'Please enter a valid Philippine mobile number (e.g. 09171234567).',
            'new_password.min' => 'Password must be at least 8 characters.',
            'new_password.max' => 'Password cannot exceed 72 characters.',
            'current_password.max' => 'Current password cannot exceed 72 characters.',
            'new_password.regex' => 'Password must include uppercase, lowercase, and a number.',
        ]);

        $user = Auth::user();
        if (!$user instanceof User) {
            abort(403);
        }

        $validated['name'] = trim(($validated['first_name'] ?? '') . ' ' . ($validated['last_name'] ?? ''));
        $validated['notify_order_updates'] = $request->boolean('notify_order_updates');
        $validated['notify_promotions'] = $request->boolean('notify_promotions');
        $validated['notify_loyalty'] = $request->boolean('notify_loyalty');

        if ($request->filled('new_password')) {
            if (!$request->filled('current_password') || !Hash::check($request->input('current_password'), $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.'])->withInput();
            }

            $validated['password'] = Hash::make($request->input('new_password'));
        }

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo_public_id) {
                $cloudinary->deleteImage($user->profile_photo_public_id);
            }

            $file = $request->file('profile_photo');
            $publicId = 'profile_pictures/user_' . $user->id;
            $uploaded = $cloudinary->uploadImage($file->getRealPath(), 'profile_pictures', $publicId, 'profile_pictures');
            $validated['profile_photo_url'] = $uploaded['url'];
            $validated['profile_photo_public_id'] = $uploaded['public_id'];
        }

        $addressParts = array_filter([
            $validated['shipping_street'] ?? null,
            $validated['shipping_secondary_address'] ?? null,
            $validated['shipping_barangay'] ?? null,
            $validated['shipping_city'] ?? null,
            'Metro Manila',
            $validated['shipping_postal_code'] ?? null,
            'Philippines',
        ]);
        $validated['shipping_address'] = $addressParts !== [] ? implode(', ', $addressParts) : null;

        unset($validated['active_tab'], $validated['current_password'], $validated['new_password']);

        $user->update($validated);

        return redirect()->route('profile.edit')->with([
            'toast_type' => 'success',
            'toast_message' => 'Settings updated successfully.',
        ]);
    }
    public function deactivateAccount(Request $request)
    {
        $user = Auth::user();
        if (!$user instanceof User) {
            abort(403);
        }

        $this->clearActiveSession($user, $request->session()->getId());

        $user->archived_at = Carbon::now();
        $user->save();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Your account has been deactivated.');
    }

    public function login(Request $request)
    {
        $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);

        $user = User::where('email', $request->email)->first();

        // Prevent archived users from authenticating
        if ($user && $user->archived_at) {
            return back()->withErrors(['email' => 'This account has been archived. Contact admin.']);
        }

        // ❌ User exists but NOT verified
        if ($user && !$user->is_verified) {

            // Generate new OTP
            $otp = rand(100000, 999999);

            $expiresAt = now()->addMinutes(self::OTP_EXPIRES_MINUTES);
            $resendAvailableAt = now()->addMinutes(self::OTP_RESEND_COOLDOWN_MINUTES);

            Cache::put('otp_' . $user->email, $otp, $expiresAt);
            Cache::put('otp_expires_' . $user->email, $expiresAt, $expiresAt);
            Cache::put('otp_resend_available_at_' . $user->email, $resendAvailableAt, $resendAvailableAt);

            // Send OTP
            Mail::to($user->email)->send(new TestMail($otp));

            // Save email in session for verification page
            session([
                'email' => $user->email
            ]);

            return redirect()
                ->route('verify-sms')
                ->with('info', 'Your account is not verified. We sent you a new verification code.');
        }

        // ✅ Verified user → normal login
        $currentSessionId = $request->session()->getId();
        if ($user && $this->hasDifferentActiveSession($user, $currentSessionId)) {
            return back()->withErrors([
                'email' => 'This account is already logged in on another browser or device.',
            ]);
        }

        if (Auth::attempt($request->only("email", "password"))) {
            $request->session()->regenerate();

            $authenticatedUser = Auth::user();
            if ($authenticatedUser instanceof User) {
                $this->rememberActiveSession($authenticatedUser, $request->session()->getId());
                $this->restoreCartAfterLogin($request, $authenticatedUser);
            }

            return redirect()->intended(route('dashboard'))
                ->with([
                    'toast_type' => 'success',
                    'toast_message' => 'Logged in successfully!',
                ]);
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.'
        ]);
    }



    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user instanceof User) {
            $sessionCart = $request->session()->get('cart', []);
            if (is_array($sessionCart) && $sessionCart !== []) {
                Cache::put($this->cartCacheKey($user), $sessionCart, now()->addDays(30));
            }
            $this->clearActiveSession($user, $request->session()->getId());
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with([
            'toast_type' => 'success',
            'toast_message' => 'Logged out successfully!',
        ]);
    }

    public function loginPost(Request $request)
{
    // 1. Validate Input
    $request->validate([
        "email" => "required|email",
        "password" => "required"
    ]);

    // 2. Find the User
    $user = User::where('email', $request->email)->first();

    // Prevent archived users from authenticating
    if ($user && $user->archived_at) {
        return back()->withErrors(['email' => 'This account has been archived. Contact admin.']);
    }

    // 3. SECURITY CHECK: Validate Password BEFORE checking verification
    if (!$user || !Hash::check($request->password, $user->password)) {
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.'
        ]);
    }

    // 4. CHECK VERIFICATION STATUS
    // If the user is ALREADY verified, skip OTP and log them in directly.
    if ($user->is_verified) {
        $currentSessionId = $request->session()->getId();
        if ($this->hasDifferentActiveSession($user, $currentSessionId)) {
            return back()->withErrors([
                'email' => 'This account is already logged in on another browser or device.'
            ]);
        }

        Auth::login($user);
        $request->session()->regenerate();
        $this->rememberActiveSession($user, $request->session()->getId());
        $this->restoreCartAfterLogin($request, $user);

        // Mark session as verified for your custom middleware
        session(['otp_verified' => true]); 

        if ($user->is_admin || $user->hasRole('admin')) {
            return redirect()->route('admin.admin_dashboard'); // Go to Admin Panel
        }
        if ($user->can('inventory.view') || $user->can('sales.view') || $user->can('deliveries.manage') || $user->can('reviews.moderate')) {
            return redirect()->route('admin.staff.dashboard'); // Staff dashboard
        }

        return redirect()->intended("dashboard")
            ->with([
                'toast_type' => 'success',
                'toast_message' => 'Logged in successfully!',
            ]);
    }

    // 5. IF NOT VERIFIED: Send OTP
    session(['email' => $user->email]);

    $otp = rand(100000, 999999);
    $expiresAt = now()->addMinutes(self::OTP_EXPIRES_MINUTES);
    $resendAvailableAt = now()->addMinutes(self::OTP_RESEND_COOLDOWN_MINUTES);

    Cache::put('otp_' . $user->email, $otp, $expiresAt);
    Cache::put('otp_expires_' . $user->email, $expiresAt, $expiresAt);
    Cache::put('otp_resend_available_at_' . $user->email, $resendAvailableAt, $resendAvailableAt);

    try {
        Mail::to($user->email)->send(new TestMail($otp));
    } catch (\Exception $e) {
        // Ignore email errors to prevent crashing
    }

    return redirect()->route('verify-sms')
        ->with('info', 'Your account is not verified. Please enter the code sent to your email.');
}



    public function sendOtp(Request $request){

        $request->validate([
            'email' => 'required|email',
        ]);

        $otp = rand(100000, 999999); // Generate a random 6-digit OTP

        $expiresAt = now()->addMinutes(self::OTP_EXPIRES_MINUTES);
        $resendAvailableAt = now()->addMinutes(self::OTP_RESEND_COOLDOWN_MINUTES);

        Cache::put('otp_' . $request->email, $otp, $expiresAt);
        Cache::put('otp_expires_' . $request->email, $expiresAt, $expiresAt);
        Cache::put('otp_resend_available_at_' . $request->email, $resendAvailableAt, $resendAvailableAt);

        try {
            Mail::to($request->email)->send(new TestMail($otp));
        } catch (\Exception $e) {
            Log::error('sendOtp email failed: ' . $e->getMessage());
        }

        return redirect()->route('verify-sms')->with('success', 'OTP sent to your email!');
    }

    public function verifyOtp(Request $request)
{
    // 1. Validate Input
    $request->validate([
        'code' => 'required|array|size:6',
        'code.*' => 'required|digits:1',
    ]);

    // 2. Get Email from Session
    // Use the same session key (`email`) that is set during register/login flows
    $email = session('email');
    if (!$email) {
        return redirect()->route('login')->with('error', 'Session expired.');
    }

    $attemptsKey = 'otp_attempts_' . $email;
    $lockKey = 'otp_lock_until_' . $email;

    $lockRemainingSeconds = $this->getOtpLockRemainingSeconds($lockKey);
    if ($lockRemainingSeconds > 0) {
        $minutes = intdiv($lockRemainingSeconds, 60);
        $seconds = $lockRemainingSeconds % 60;
        return back()->withErrors([
            'otp' => 'Too many incorrect attempts. Try again in ' . sprintf('%02d:%02d', $minutes, $seconds) . '.',
        ]);
    }

    // 3. Verify OTP Matches Cache
    $enteredOtp = implode('', $request->code);
    $cachedOtp  = Cache::get('otp_' . $email);

    if (!$cachedOtp || $enteredOtp != $cachedOtp) {
        $attempts = $this->incrementOtpAttempts($attemptsKey);

        if ($attempts >= self::OTP_MAX_ATTEMPTS) {
            $lockUntil = now()->addMinutes(self::OTP_LOCK_MINUTES);
            Cache::put($lockKey, $lockUntil, $lockUntil);
            Cache::forget($attemptsKey);

            return back()->withErrors([
                'otp' => 'Too many incorrect attempts. You are locked for 5 minutes.',
            ]);
        }

        $remainingAttempts = self::OTP_MAX_ATTEMPTS - $attempts;
        Log::warning('verifyOtp failed: invalid or expired code', ['email' => $email]);
        return back()->withErrors([
            'otp' => 'Invalid or expired verification code. ' . $remainingAttempts . ' attempt(s) remaining.',
        ]);
    }

    // 4. Resolve user: create from pending registration only after OTP passes
    $pendingRegistration = Cache::get('pending_registration_' . $email);
    $user = User::where('email', $email)->first();

    if (!$user && is_array($pendingRegistration)) {
        $user = User::create([
            'name' => $pendingRegistration['name'] ?? $email,
            'phone' => $pendingRegistration['phone'] ?? null,
            'email' => $pendingRegistration['email'] ?? $email,
            'password' => $pendingRegistration['password'] ?? Hash::make(str()->random(24)),
            'is_verified' => true,
            'email_verified_at' => now(),
        ]);
    }

    if ($user) {
        $currentSessionId = $request->session()->getId();
        if ($this->hasDifferentActiveSession($user, $currentSessionId)) {
            return back()->withErrors([
                'otp' => 'This account is already logged in on another browser or device.',
            ]);
        }

        // ✅ A. Mark User as Verified
        $user->is_verified      = true;
        $user->email_verified_at = now();
        $user->save();

        // ✅ B. Log the User In
        Auth::login($user);
        $request->session()->regenerate();
        $this->rememberActiveSession($user, $request->session()->getId());
        $this->restoreCartAfterLogin($request, $user);

        // ✅ C. Clean Up
        Cache::forget('otp_' . $email);
        Cache::forget('otp_expires_' . $email);
        Cache::forget('otp_resend_available_at_' . $email);
        Cache::forget($attemptsKey);
        Cache::forget($lockKey);
        Cache::forget('pending_registration_' . $email);
        session(['otp_verified' => true]);
        // Clear the `email` session key used while verifying
        $request->session()->forget('email');

        Log::info('verifyOtp success: user verified', ['email' => $email, 'user_id' => $user->id]);

        // ✅ D. Redirect based on role
        if (($user->is_admin ?? false) || $user->hasRole('admin')) {
            return redirect()->route('admin.admin_dashboard')->with('success', 'Account verified!');
        }
        if ($user->can('inventory.view') || $user->can('sales.view') || $user->can('deliveries.manage') || $user->can('reviews.moderate')) {
            return redirect()->route('admin.staff.dashboard')->with('success', 'Account verified!');
        }

        return redirect()->route('dashboard')->with('success', 'Account verified!');
    }

    return back()->with('error', 'User not found.');
}

    private function incrementOtpAttempts(string $attemptsKey): int
    {
        $ttl = now()->addMinutes(self::OTP_LOCK_MINUTES);

        // Ensure the key exists first so increment works consistently across drivers.
        Cache::add($attemptsKey, 0, $ttl);

        $incremented = Cache::increment($attemptsKey);
        if ($incremented === false || $incremented === null) {
            $current = (int) Cache::get($attemptsKey, 0);
            $incremented = $current + 1;
            Cache::put($attemptsKey, $incremented, $ttl);
        }

        return (int) $incremented;
    }
    



    public function resendOtp()
    {
        $email = session('email');

        if (!$email) {
            return redirect()->route('login')
                ->with('error', 'Session expired.');
        }

        $lockKey = 'otp_lock_until_' . $email;
        $lockRemainingSeconds = $this->getOtpLockRemainingSeconds($lockKey);
        if ($lockRemainingSeconds > 0) {
            return back()->with('error', 'You are temporarily locked due to failed attempts. Please wait before retrying.');
        }

        $resendAvailableAt = Cache::get('otp_resend_available_at_' . $email);
        $remainingSeconds = $this->secondsUntil($resendAvailableAt);
        if ($remainingSeconds > 0) {
            $minutes = intdiv($remainingSeconds, 60);
            $seconds = $remainingSeconds % 60;
            return back()->with('error', 'Please wait ' . sprintf('%02d:%02d', $minutes, $seconds) . ' before resending OTP.');
        }

        $otp = rand(100000, 999999);
        $expiresAt = now()->addMinutes(self::OTP_EXPIRES_MINUTES);
        $newResendAvailableAt = now()->addMinutes(self::OTP_RESEND_COOLDOWN_MINUTES);

        Cache::put('otp_' . $email, $otp, $expiresAt);
        Cache::put('otp_expires_' . $email, $expiresAt, $expiresAt);
        Cache::put('otp_resend_available_at_' . $email, $newResendAvailableAt, $newResendAvailableAt);
        Cache::forget('otp_attempts_' . $email);

        try {
            Mail::to($email)->send(new TestMail($otp));
        } catch (\Exception $e) {
            Log::error('Resend OTP email failed: ' . $e->getMessage());
        }

        return back()->with('success', 'A new OTP has been sent.');
    }

    // --- Forgot Password with OTP ---
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('info', 'If the email exists in our system, we sent a 6-digit OTP to your inbox.');
        }

        $otp = rand(100000, 999999);
        $expiresAt = now()->addMinutes(5);

        Cache::put('password_reset_otp_' . $request->email, $otp, $expiresAt);
        Cache::put('password_reset_expires_' . $request->email, $expiresAt, $expiresAt);

        try {
            Mail::to($request->email)->send(new TestMail($otp));
        } catch (\Exception $e) {
            Log::error('Password reset OTP email failed', [
                'email' => $request->email,
                'exception' => $e->getMessage(),
            ]);

            return back()->with('info', 'If the email exists in our system, we sent a 6-digit OTP to your inbox.');
        }

        session(['password_reset_email' => $request->email]);

        return redirect()->route('password.verify')
            ->with('info', 'If the email exists in our system, we sent a 6-digit OTP to your inbox.');
    }

    public function showVerifyPasswordReset()
    {
        if (!session('password_reset_email')) {
            return redirect()->route('password.request')->with('error', 'Session expired. Please request a new OTP.');
        }

        $email = session('password_reset_email');
        $expiresAt = Cache::get('password_reset_expires_' . $email);
        $remainingSeconds = $this->secondsUntil($expiresAt);
        $otpExpiresAtTs = $expiresAt ? Carbon::parse((string) $expiresAt)->getTimestamp() : 0;
        $lockUntil = Cache::get('password_reset_lock_until_' . $email);
        $lockRemainingSeconds = $this->secondsUntil($lockUntil);
        $lockExpiresAtTs = $lockUntil ? Carbon::parse((string) $lockUntil)->getTimestamp() : 0;

        return view('auth.verify-password-reset', compact('remainingSeconds', 'lockRemainingSeconds', 'otpExpiresAtTs', 'lockExpiresAtTs'));
    }

    public function verifyPasswordResetOtp(Request $request)
    {
        $request->validate([
            'code' => 'required|array|size:6',
            'code.*' => 'required|digits:1',
        ]);

        $email = session('password_reset_email');
        if (!$email) {
            return redirect()->route('password.request')->with('error', 'Session expired. Please request a new OTP.');
        }

        $attemptsKey = 'password_reset_attempts_' . $email;
        $lockKey = 'password_reset_lock_until_' . $email;

        $lockRemainingSeconds = $this->secondsUntil(Cache::get($lockKey));
        if ($lockRemainingSeconds > 0) {
            $minutes = intdiv($lockRemainingSeconds, 60);
            $seconds = $lockRemainingSeconds % 60;
            return back()->withErrors([
                'code' => 'Too many incorrect attempts. Try again in ' . sprintf('%02d:%02d', $minutes, $seconds) . '.',
            ]);
        }

        $enteredOtp = implode('', $request->code);
        $cachedOtp = Cache::get('password_reset_otp_' . $email);

        if (!$cachedOtp || $enteredOtp != $cachedOtp) {
            $attempts = $this->incrementOtpAttempts($attemptsKey);

            if ($attempts >= self::OTP_MAX_ATTEMPTS) {
                $lockUntil = now()->addMinutes(self::OTP_LOCK_MINUTES);
                Cache::put($lockKey, $lockUntil, $lockUntil);
                Cache::forget($attemptsKey);

                return back()->withErrors([
                    'code' => 'Too many incorrect attempts. You are locked for 5 minutes.',
                ]);
            }

            $remainingAttempts = self::OTP_MAX_ATTEMPTS - $attempts;
            return back()->withErrors(['code' => 'Invalid or expired verification code. ' . $remainingAttempts . ' attempt(s) remaining.']);
        }

        Cache::forget('password_reset_otp_' . $email);
        Cache::forget('password_reset_expires_' . $email);
        Cache::forget($attemptsKey);
        Cache::forget($lockKey);

        session(['password_reset_verified' => true]);

        return redirect()->route('password.reset');
    }

    public function resendPasswordResetOtp()
    {
        $email = session('password_reset_email');
        if (!$email) {
            return redirect()->route('password.request')->with('error', 'Session expired.');
        }

        $lockKey = 'password_reset_lock_until_' . $email;
        $lockRemainingSeconds = $this->secondsUntil(Cache::get($lockKey));
        if ($lockRemainingSeconds > 0) {
            return back()->with('error', 'You are temporarily locked due to failed attempts. Please wait before retrying.');
        }

        $expiresAt = Cache::get('password_reset_expires_' . $email);
        $remainingSeconds = $this->secondsUntil($expiresAt);
        if ($remainingSeconds > 0) {
            $minutes = intdiv($remainingSeconds, 60);
            $seconds = $remainingSeconds % 60;
            return back()->with('error', 'Please wait ' . sprintf('%02d:%02d', $minutes, $seconds) . ' before resending OTP.');
        }

        $otp = rand(100000, 999999);
        $expiresAt = now()->addMinutes(5);

        Cache::put('password_reset_otp_' . $email, $otp, $expiresAt);
        Cache::put('password_reset_expires_' . $email, $expiresAt, $expiresAt);
        Cache::forget('password_reset_attempts_' . $email);

        try {
            Mail::to($email)->send(new TestMail($otp));
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to resend OTP.');
        }

        return back()->with('success', 'A new OTP has been sent.');
    }

    public function showResetPassword()
    {
        if (!session('password_reset_verified') || !session('password_reset_email')) {
            return redirect()->route('password.request')->with('error', 'Session expired. Please start again.');
        }

        return view('auth.reset-password');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'confirmed',
            ],
        ], [
            'password.min' => 'Password must be at least 8 characters.',
            'password.regex' => 'Password must include uppercase, lowercase, and a number.',
        ]);

        $email = session('password_reset_email');
        if (!$email) {
            return redirect()->route('password.request')->with('error', 'Session expired. Please start again.');
        }

        $user = User::where('email', $email)->firstOrFail();
        $user->update(['password' => Hash::make($request->password)]);

        $request->session()->forget(['password_reset_email', 'password_reset_verified']);

        return redirect()->route('login')->with('success', 'Your password has been reset. You can now log in.');
    }
}
