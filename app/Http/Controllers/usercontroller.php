<?php

namespace App\Http\Controllers;

use App\Mail\contactemail;
use App\Models\contact;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class usercontroller extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
                'password' => 'required'
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'error' => $validator->errors()->first(),
            ], 401);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'error' => "Email Doesn't Exist"
            ], 401);
        } else {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('api_token')->plainTextToken;;

                return response()->json([
                    'status' => true,
                    'token' => $token,
                    'user' => $user,
                    'success' => 'Logged In Successfully'
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'error' => "Password didn't match"
                ], 401);
            }
        }
    }

    public function logout(Request $request)
    {
        $user = User::findorfail($request->id);
        $user->tokens()->delete();

        return response()->json([
            'status' => true,
            'success' => 'Logout Successfull'
        ], 200);
    }

    public function contact(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:30',
            'email' => 'required|email',
            'number' => 'required|digits:10',
            'subject' => 'required|max:500',
            'message' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'error' => $validator->errors()->first(),
            ], 401);
        }

        contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'number' => $request->number,
            'subject' => $request->subject,
            'message' => $request->message
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'number' => $request->number,
            'subject' => $request->subject,
            'message' => $request->message
        ];

        $mail = Mail::to($request->email)->send(new contactemail($data));

        if ($mail) {
            return response()->json([
                'status' => true,
                'success' => 'Thank You for Contacting Us, Our Team will get in touch shortly !!'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'error' => 'There is Some error !! But we got you message, Kindly wait till our team contact you'
            ]);
        }
    }
}
