<?php

namespace App\Http\Controllers;


use Exception;
use App\Models\User;
use App\Mail\OTPMail;
use App\Helper\JWTToken;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function UserRegistration(Request $request)
    {

        try {

            User::create([
                'firstName' => $request->input('firstName'),
                'lastName' => $request->input('lastName'),
                'email' => $request->input('email'),
                'mobile' => $request->input('mobile'),
                'password' => $request->input('password'),

            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'registration successfull'
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Registration Failed'
            ], 401);
        }
    }

    public function login(Request $request)
    {
        $count = User::where("email", $request->input("email"))
            ->where("password", $request->input("password"))
            ->select('id')->first();

        if ($count !== null) {
            $token = JWTToken::createToken($request->input("email"), $count->id);
            return response()->json([
                'status' => 'success',
                'message' => 'login successfull',
                'token' => $token
            ], 200)->cookie('token', $token, 60 * 24 * 30);
        } else {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Login failed'
            ], 200);
        }

    }



    public function sendOTP(Request $request)
    {
        $email = $request->input('email');
        $otp = rand(1000, 9999);
        $count = User::where('email', $email)->count();

        if ($count == 1) {
            Mail::to($email)->send(new OTPMail($otp));
            User::where('email', $email)->update([
                'otp' => $otp
            ]);

            return response()->json([
                'status' => 'success',
                'message' => '4 digit OTP send successfully'
            ], 200);
        } else {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Request failed'
            ], 200);
        }


    }

    public function verifyOTP(Request $request)
    {
        $email = $request->input('email');
        $otp = $request->input('otp');
        $count = User::where('email', $email)
            ->where('otp', '=', $otp)->count();
        if ($count == 1) {
            $token = JWTToken::createTokenForResetPassword($request->input("email"));
            User::where('email', $email)->update([
                'otp' => "0"
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'otp varification successfull',
                'token' => $token
            ], 200)->cookie("token", $token, 60 * 24 * 30);
        } else {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Request failed'
            ], 200);
        }

    }

    public function resetPassword(Request $request)
    {
        try {

            $email = $request->header('email');
            $password = $request->input('password');
            User::where('email', $email)->update([
                'password' => $password
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Request successfull'
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Registration Failed'
            ], 401);
        }

    }
    public function logout()
    {
        return redirect('/login')->cookie('token', "", -1);

    }

    public function getProfile(Request $request)
    {
        $email = $request->header('email');
        $user = User::where("email", $email)->first();
        return response()->json([
            'status' => 'success',
            'message' => 'Request successfull',
            'profileData' => $user
        ], 200);

    }

    public function updateProfile(Request $request)
    {
        try {

            $email = $request->header('email');

            $firstName = $request->input('firstName');
            $lastName = $request->input('lastName');
            $mobile = $request->input('mobile');
            $password = $request->input('password');

            User::where('email', $email)->update([
                'firstName' => $firstName,
                'lastName' => $lastName,
                'mobile' => $mobile,
                'password' => $password
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Request successfull'
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Request Failed'
            ], 401);
        }



    }

    // page route =====================================
    function LoginPage(): View
    {
        return view('pages.auth.login-page');
    }
    function RegistrationPage(): View
    {
        return view('pages.auth.registration-page');
    }
    function SendOtpPage(): View
    {
        return view('pages.auth.send-otp-page');
    }
    function VerifyOTPPage(): View
    {
        return view('pages.auth.verify-otp-page');
    }
    function ResetPasswordPage(): View
    {
        return view('pages.auth.reset-pass-page');
    }

}
