<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class LoginController extends Controller
// {
//     public function login(Request $request)
//     {
//         // $validator = Validator::make($request->all(), [
//         //     'email' => 'required|email',
//         //     'password' => 'required'
//         // ]);

//         // if ($validator->fails()) {
//         //     return response()->json($validator->errors(), 422);
//         // }

//         if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

//             $user = Auth::user();
//             $token = $user->createToken('auth_token')->plainTextToken;
//             $user->remember_token = $token;
//             $user->save();

//             return response()->json([
//                 'status' => 200,
//                 'message' => 'Login successful',
//                 'token' => $token,
//                 'user' => $user,
//             ]);
            
//         } else {
//             return response()->json([
//                 'status' => 401,
//                 'message' => 'Invalid credentials'
//             ], 401);
//         }
//     }
// }
{
    public function login(Request $request)
    {
        // Attempt to authenticate the user
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            // Check if the user is accepted
            if ($user->isAccepted) {
                // If the user is accepted, generate a token and proceed with login
                $token = $user->createToken('auth_token')->plainTextToken;
                $user->remember_token = $token;
                $user->save();

                return response()->json([
                    'status' => 200,
                    'message' => 'Login avec succès',
                    'token' => $token,
                    'user' => $user,
                ]);
            } else {
                // If the user is not accepted, return an error response
                return response()->json([
                    'status' => 401,
                    'message' => "User n'est pas accepté"
                ], 401);
            }
        } else {
            // If authentication fails, return an error response
            return response()->json([
                'status' => 401,
                'message' => "Informations d'identification non valides"
            ], 401);
        }
    }
}
