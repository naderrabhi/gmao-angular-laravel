<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function index()
        {
            $users=user::all();

            if($users->count() >0) {

            return response()->json([
                'status' => 200,
                'user' =>$users,
            ],200) ;
        }else{
            return response()->json([
                'status' => 404,
                'message' =>"Users n'est pas trouver",
            ],404) ;

        }
    }

    public function store(Request $request)
    {    
        $validator = Validator::make($request->all(), [
            'nom'=> 'required|string|max:191',
            'prenom'=> 'required|string|max:191',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|string',
            'isAvailable' => 'boolean',
            'isAccepted' => 'boolean',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status'=> 422,
                'errors'=> $validator->messages()
            ], 422);
        } else {
            $user = User::create([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'isAvailable' => $request->isAvailable,
                'isAccepted' => $request->isAccepted,
            ]);
    
            if ($user) {
                return response()->json([
                    'status' => 200,
                    'message' => "User créé avec succès"
                ], 200);
            } else {
                return response()->json([
                    'status' => 500,
                    'message' => "Une erreur s'est produite"
                ], 500);
            }
        }
    }

                   public function show($id)
                   {
                    $user=user::find($id);
                    if($user){
                        return response()->json([
                            'status'=>200,
                            'message'=>$user
                        ],200);
                    }else{
                        return response()->json([
                            'status'=>404,
                            'message'=>"User n'est pas trouver"
                        ],404);
                    }
                   }

                   public function edit($id)
                   {
                    $user=user::find($id);
                    if($user){
                        return response()->json([
                            'status'=>200,
                            'message'=>$user
                        ],200);
                    }else{
                        return response()->json([
                            'status'=>404,
                            'message'=>"User n'est pas trouver"
                        ],404);
                    }

                }

                   
                public function update(Request $request, int $id)
                {
                    $validator = Validator::make($request->all(), [
                        'nom' => 'required|string|max:191',
                        'prenom' => 'required|string|max:191',
                        'email' => 'required|email',
                        'password' => 'nullable|string|min:6',
                        'role' => 'required|string',
                        'isAvailable' => 'boolean',
                        'isAccepted' => 'boolean',
                    ]);
                
                    if ($validator->fails()) {
                        return response()->json([
                            'status' => 422,
                            'errors' => $validator->messages(),
                        ], 422);
                    }
                
                    $userToUpdate = User::find($id);
                
                    if (!$userToUpdate) {
                        return response()->json([
                            'status' => 404,
                            'message' => "User n'est pas trouver avec ID: $id",
                        ], 404);
                    }
                
                    $newEmail = $request->input('email');
                
                    if ($newEmail === $userToUpdate->email) {
                        $userToUpdate->update($request->only([
                            'nom',
                            'prenom',
                            'password',
                            'role',
                            'isAvailable',
                            'isAccepted',
                        ]));
                
                        return response()->json([
                            'status' => 200,
                            'message' => "User modifiée avec succés",
                        ], 200);
                    } else {
                        $existingUser = User::where('email', $newEmail)->first();
                
                        if ($existingUser) {
                            return response()->json([
                                'status' => 422,
                                'errors' => ['email' => ['Email est déja existe']],
                            ], 422);
                        } else {
                            $userToUpdate->update(array_merge($request->all(), ['email' => $newEmail]));
                
                            return response()->json([
                                'status' => 200,
                                'message' => "User modifiée avec succés (email changé)",
                            ], 200);
                        }
                    }
                
        }    
        public function destroy($id)
        {
            $user= user::find($id);
            if($user)
            {
             $user->delete();
             return response()->json([
                'status'=>200,
                'message'=> "User supprimer avec succés"
            ],200);
            }else{
                return response()->json([
                    'status'=>404,
                    'message'=> "something went wrong"
                ],404);
            }
        }

        public function getCurrentUser(Request $request)
        {
            // Get the token from the request headers
            $token = $request->header('Authorization');
    
            // Remove 'Bearer ' prefix from token if present
            $token = str_replace('Bearer ', '', $token);
    
            // Search for the user with the given token
            $user = User::where('remember_token', $token)->first();
    
            if ($user) {
                return response()->json([
                    'status' => 'success',
                    'user' => $user
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => "User n'est pas trouvée"
                ], 404);
            }
        }
}
