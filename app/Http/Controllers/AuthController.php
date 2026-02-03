<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Mail\TestMail;

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
            return redirect()->intended("user_dashboard")
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
            return redirect()->route('login.form')->with('error', 'Session expired.');
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
            return redirect()->route('dashboard')->with('success', 'Account verified!');
        }

        return back()->with('error', 'User not found.');
    }
    



    public function resendOtp()
    {
        $email = session('email');

        if (!$email) {
            return redirect()->route('login.form')
                ->with('error', 'Session expired.');
        }

        $otp = rand(100000, 999999);
        $expiresAt = now()->addMinutes(5);

        Cache::put('otp_' . $email, $otp, $expiresAt);
        Cache::put('otp_expires_' . $email, $expiresAt, $expiresAt);

        Mail::to($email)->send(new TestMail($otp));

        return back()->with('success', 'A new OTP has been sent.');
    }


    
}

