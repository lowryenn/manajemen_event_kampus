<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email|max:150',
            'password' => 'required|string|min:6',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your account is inactive.',
                ]);
            }

            return $this->redirectBasedOnRole($user);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegisterForm()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|unique:users|max:150',
            'password' => 'required|string|confirmed|min:6',
            'phone' => 'nullable|string|max:20',
        ]);

        $otp = rand(100000, 999999);
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
        ];

        session([
            'register_otp' => $otp,
            'register_data' => $data,
            'register_otp_expires_at' => now()->addMinutes(15)
        ]);

        try {
            Mail::send([], [], function ($message) use ($otp, $data) {
                $message->to($data['email'])
                    ->subject('EventKampus - OTP Verification Code')
                    ->html("
                        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e0e0e0; border-radius: 8px; background-color: #ffffff;'>
                            <div style='text-align: center; border-bottom: 2px solid #6366f1; padding-bottom: 10px; margin-bottom: 20px;'>
                                <h2 style='color: #6366f1; margin: 0;'>EventKampus</h2>
                                <p style='color: #6b7280; font-size: 14px; margin: 5px 0 0 0;'>Campus Event Community</p>
                            </div>
                            <p style='color: #374151; font-size: 16px;'>Hello <strong>" . htmlspecialchars($data['name']) . "</strong>,</p>
                            <p style='color: #374151; font-size: 16px; line-height: 1.5;'>Thank you for registering at EventKampus. To complete your registration, please verify your email address by entering the following One-Time Password (OTP):</p>
                            <div style='text-align: center; margin: 30px 0;'>
                                <span style='display: inline-block; font-size: 32px; font-weight: 800; letter-spacing: 5px; color: #111827; background-color: #f3f4f6; padding: 12px 30px; border-radius: 8px; border: 1px dashed #6366f1;'>" . $otp . "</span>
                            </div>
                            <p style='color: #ef4444; font-size: 14px;'><strong>Note:</strong> This code is valid for 15 minutes. Please do not share this code with anyone.</p>
                            <hr style='border: 0; border-top: 1px solid #e5e7eb; margin: 25px 0;'>
                            <p style='color: #9ca3af; font-size: 12px; text-align: center;'>This is an automated email. Please do not reply.</p>
                        </div>
                    ");
            });
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Registration OTP email sending failed (proceeding in debug/bypass mode): ' . $e->getMessage());
        }

        return redirect()->route('register.otp');
    }

    public function showOtpForm()
    {
        if (!session()->has('register_otp') || !session()->has('register_data')) {
            return redirect()->route('register')->withErrors(['email' => 'Please fill in the registration details first.']);
        }

        $email = session('register_data')['email'];
        return view('auth.otp', compact('email'));
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        if (!session()->has('register_otp') || !session()->has('register_data')) {
            return redirect()->route('register')->withErrors(['email' => 'Session expired. Please register again.']);
        }

        $sessionOtp = session('register_otp');
        $expiresAt = session('register_otp_expires_at');

        if ($expiresAt && now()->greaterThan($expiresAt)) {
            return redirect()->route('register')->withErrors(['email' => 'OTP has expired. Please try registering again.']);
        }

        if ($request->otp != $sessionOtp) {
            return back()->withErrors(['otp' => 'The verification code you entered is incorrect.']);
        }

        $data = session('register_data');

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'phone' => $data['phone'],
            'role' => 'user',
            'is_active' => true,
        ]);

        // Clear verification session data
        session()->forget(['register_otp', 'register_data', 'register_otp_expires_at']);

        Auth::login($user);

        return $this->redirectBasedOnRole($user);
    }

    public function resendOtp(Request $request)
    {
        if (!session()->has('register_otp') || !session()->has('register_data')) {
            return redirect()->route('register')->withErrors(['email' => 'Session expired. Please register again.']);
        }

        $otp = rand(100000, 999999);
        $data = session('register_data');

        session([
            'register_otp' => $otp,
            'register_otp_expires_at' => now()->addMinutes(15)
        ]);

        try {
            Mail::send([], [], function ($message) use ($otp, $data) {
                $message->to($data['email'])
                    ->subject('EventKampus - OTP Verification Code')
                    ->html("
                        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e0e0e0; border-radius: 8px; background-color: #ffffff;'>
                            <div style='text-align: center; border-bottom: 2px solid #6366f1; padding-bottom: 10px; margin-bottom: 20px;'>
                                <h2 style='color: #6366f1; margin: 0;'>EventKampus</h2>
                                <p style='color: #6b7280; font-size: 14px; margin: 5px 0 0 0;'>Campus Event Community</p>
                            </div>
                            <p style='color: #374151; font-size: 16px;'>Hello <strong>" . htmlspecialchars($data['name']) . "</strong>,</p>
                            <p style='color: #374151; font-size: 16px; line-height: 1.5;'>Thank you for registering at EventKampus. To complete your registration, please verify your email address by entering the following One-Time Password (OTP):</p>
                            <div style='text-align: center; margin: 30px 0;'>
                                <span style='display: inline-block; font-size: 32px; font-weight: 800; letter-spacing: 5px; color: #111827; background-color: #f3f4f6; padding: 12px 30px; border-radius: 8px; border: 1px dashed #6366f1;'>" . $otp . "</span>
                            </div>
                            <p style='color: #ef4444; font-size: 14px;'><strong>Note:</strong> This code is valid for 15 minutes. Please do not share this code with anyone.</p>
                            <hr style='border: 0; border-top: 1px solid #e5e7eb; margin: 25px 0;'>
                            <p style='color: #9ca3af; font-size: 12px; text-align: center;'>This is an automated email. Please do not reply.</p>
                        </div>
                    ");
            });
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Registration OTP resend email sending failed (proceeding in debug/bypass mode): ' . $e->getMessage());
        }

        return redirect()->route('register.otp')->with('success', 'Kode OTP baru telah berhasil dikirim!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    /**
     * Helper to redirect users based on role.
     */
    private function redirectBasedOnRole($user)
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('user.home');
    }
}
