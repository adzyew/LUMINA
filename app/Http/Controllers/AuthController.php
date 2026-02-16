<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Mail\TestMail;
use App\Services\CloudinaryService;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    // --- ADD THESE FUNCTIONS TO AuthController.php ---

   

    public function registerPost(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);

        // 1️⃣ Create user (NOT logged in yet)
        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'email_verified_at' => null,
            'is_verified' => false,
        ]);

        // 2️⃣ Generate OTP
        $otp = rand(100000, 999999);

        $expiresAt = now()->addMinutes(5);

        Cache::put('otp_' . $request->email, $otp, $expiresAt);
        Cache::put('otp_expires_' . $request->email, $expiresAt, $expiresAt);

        // 3️⃣ Send OTP email
        Mail::to($request->email)->send(new TestMail($otp));

        // 4️⃣ Save email in session (VERY IMPORTANT)
        session([
            'email' => $request->email,
        ]);

        return redirect()->route('verify-sms');
    }


    public function user_dashboard(Request $request)
    {
        return view("user.user_dashboard");
    }

    public function editProfile()
    {
        return view('user.profile_edit', ['user' => auth()->user()]);
    }

    public function updateProfile(Request $request, CloudinaryService $cloudinary)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        $data = [
            'name' => $validated['name'],
            'phone' => $validated['phone'],
        ];

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo_public_id) {
                $cloudinary->deleteImage($user->profile_photo_public_id);
            }
            $upload = $cloudinary->uploadImage($request->file('profile_photo')->getRealPath(), 'profiles');
            $data['profile_photo_url'] = $upload['url'];
            $data['profile_photo_public_id'] = $upload['public_id'];
        }

        $user->update($data);

        return redirect()->route('dashboard')->with('success', 'Profile updated successfully.');
    }

    public function login(Request $request)
    {
        $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);

        $user = User::where('email', $request->email)->first();

        // ❌ User exists but NOT verified
        if ($user && !$user->is_verified) {

            // Generate new OTP
            $otp = rand(100000, 999999);

            $expiresAt = now()->addMinutes(5);

            Cache::put('otp_' . $user->email, $otp, $expiresAt);
            Cache::put('otp_expires_' . $user->email, $expiresAt, $expiresAt);

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
        if (Auth::attempt($request->only("email", "password"))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'))
                ->with("success", "Logged in Successfully!");
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.'
        ]);
    }



    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Logged out successfully!');
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

        // 3. SECURITY CHECK: Validate Password BEFORE checking verification
        // If user doesn't exist OR password is wrong, stop here.
        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.'
            ]);
        }

        // 4. CHECK VERIFICATION STATUS
        // If the user is ALREADY verified, skip OTP and log them in directly.
        if ($user->is_verified) {
            Auth::login($user);
            $request->session()->regenerate();

            if ($user->is_admin || $user->hasRole('admin')) {
                return redirect()->route('admin.admin_dashboard'); // Go to Admin Panel
            }
            if ($user->can('inventory.view') || $user->can('sales.view') || $user->can('deliveries.manage')) {
                return redirect()->route('admin.staff.dashboard'); // Staff dashboard (inventory/sales/delivery)
            }

            return redirect()->intended("dashboard")
                ->with("success", "Logged in Successfully!");
        }

        // 5. IF NOT VERIFIED: Send OTP
        // (This code only runs if $user->is_verified is false)
        $otp = rand(100000, 999999);
        $expiresAt = now()->addMinutes(5);

        Cache::put('otp_' . $user->email, $otp, $expiresAt);
        Cache::put('otp_expires_' . $user->email, $expiresAt, $expiresAt);

        try {
            Mail::to($user->email)->send(new TestMail($otp));
        } catch (\Exception $e) {
            // Ignore email errors to prevent crashing
        }

        // Save email to session so the verify page knows who it is
        session(['email' => $user->email]);

        return redirect()->route('verify-sms')
            ->with('info', 'Your account is not verified. Please enter the code sent to your email.');
    }



    public function sendOtp(Request $request){

        $request->validate([
            'email' => 'required|email',
        ]);

        $otp = rand(100000, 999999); // Generate a random 6-digit OTP

        Cache::put('otp_' . $request->email, $otp, now()->addMinutes(10));

        Mail::to($request->email)->send(new TestMail($otp));

        return redirect()->route('verify-sms')->with('success', 'OTP sent to your email!');
    }

    public function verifyOtp(Request $request)
    {
        // 1. Validate Input
        $request->validate([
            'code' => 'required|array|size:6',
        ]);

        // 2. Get Email from Session
        $email = session('email'); 
        if (!$email) {
            return redirect()->route('login')->with('error', 'Session expired.');
        }

        // 3. Verify OTP Matches Cache
        $enteredOtp = implode('', $request->code);
        $cachedOtp = Cache::get('otp_' . $email);

        if (!$cachedOtp || $enteredOtp != $cachedOtp) {
            return back()->withErrors(['otp' => 'Invalid or expired verification code.']);
        }

        // 4. Find User
        $user = User::where('email', $email)->first();

        if ($user) {
            // ✅ A. Mark User as Verified
            $user->is_verified = true;
            $user->email_verified_at = now();
            $user->save();
            
            // ✅ B. Log the User In
            Auth::login($user);
            
            // ✅ C. Clean Up (Remove OTP & Session Email)
            Cache::forget('otp_' . $email);
            Cache::forget('otp_expires_' . $email);
            $request->session()->forget('email');

            // ✅ D. Redirect to Dashboard
            if (($user->is_admin ?? false) || $user->hasRole('admin')) {
                return redirect()->route('admin.admin_dashboard')->with('success', 'Account verified!');
            }
            if ($user->can('inventory.view') || $user->can('sales.view') || $user->can('deliveries.manage')) {
                return redirect()->route('admin.staff.dashboard')->with('success', 'Account verified!');
            }
            return redirect()->route('dashboard')->with('success', 'Account verified!');
        }

        return back()->with('error', 'User not found.');
    }
    



    public function resendOtp()
    {
        $email = session('email');

        if (!$email) {
            return redirect()->route('login')
                ->with('error', 'Session expired.');
        }

        $otp = rand(100000, 999999);
        $expiresAt = now()->addMinutes(5);

        Cache::put('otp_' . $email, $otp, $expiresAt);
        Cache::put('otp_expires_' . $email, $expiresAt, $expiresAt);

        Mail::to($email)->send(new TestMail($otp));

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
            return back()->withInput()->with('error', 'No account found with this email address.');
        }

        $otp = rand(100000, 999999);
        $expiresAt = now()->addMinutes(5);

        Cache::put('password_reset_otp_' . $request->email, $otp, $expiresAt);
        Cache::put('password_reset_expires_' . $request->email, $expiresAt, $expiresAt);

        try {
            Mail::to($request->email)->send(new TestMail($otp));
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to send email. Please try again later.');
        }

        session(['password_reset_email' => $request->email]);

        return redirect()->route('password.verify')
            ->with('info', 'We\'ve sent a 6-digit OTP to your email. Please check your inbox.');
    }

    public function showVerifyPasswordReset()
    {
        if (!session('password_reset_email')) {
            return redirect()->route('password.request')->with('error', 'Session expired. Please request a new OTP.');
        }

        $email = session('password_reset_email');
        $expiresAt = Cache::get('password_reset_expires_' . $email);
        $remainingSeconds = $expiresAt ? max(0, $expiresAt->getTimestamp() - time()) : 300;

        return view('auth.verify-password-reset', compact('remainingSeconds'));
    }

    public function verifyPasswordResetOtp(Request $request)
    {
        $request->validate(['code' => 'required|array|size:6']);

        $email = session('password_reset_email');
        if (!$email) {
            return redirect()->route('password.request')->with('error', 'Session expired. Please request a new OTP.');
        }

        $enteredOtp = implode('', $request->code);
        $cachedOtp = Cache::get('password_reset_otp_' . $email);

        if (!$cachedOtp || $enteredOtp != $cachedOtp) {
            return back()->withErrors(['code' => 'Invalid or expired verification code.']);
        }

        Cache::forget('password_reset_otp_' . $email);
        Cache::forget('password_reset_expires_' . $email);

        session(['password_reset_verified' => true]);

        return redirect()->route('password.reset');
    }

    public function resendPasswordResetOtp()
    {
        $email = session('password_reset_email');
        if (!$email) {
            return redirect()->route('password.request')->with('error', 'Session expired.');
        }

        $otp = rand(100000, 999999);
        $expiresAt = now()->addMinutes(5);

        Cache::put('password_reset_otp_' . $email, $otp, $expiresAt);
        Cache::put('password_reset_expires_' . $email, $expiresAt, $expiresAt);

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
            'password' => 'required|min:8|confirmed',
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

