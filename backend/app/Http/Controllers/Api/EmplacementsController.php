<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Emplacements;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class EmplacementsController extends Controller
{
        public function index()
        {
            $emplacements=emplacements::all();

            if($emplacements->count() >0) {

            return response()->json([
                'status' => 200,
                'emplacements' =>$emplacements,
            ],200) ;
            }else{
                return response()->json([
                    'status' => 404,
                    'message' =>"Emplacements n'est pas trouver",
                ],404) ;

            }
        }
        public function store(Request $request)
        {    
            $validator = Validator::make($request->all(), [
                'nom'=> 'required|string|max:191',
                'description'=> 'string',
            ]);
        
            if ($validator->fails()) {
                return response()->json([
                    'status'=> 422,
                    'errors'=> $validator->messages()
                ], 422);
            } else {
                $emplacement = Emplacements::create([
                    'nom' => $request->nom,
                    'description' => $request->description,
                ]);
        
                if ($emplacement) {
                    return response()->json([
                        'status' => 200,
                        'message' => "Emplacement créé avec succès"
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
            $emplacement=emplacements::find($id);
            if($emplacement){
                return response()->json([
                    'status'=>200,
                    'message'=>$emplacement
                ],200);
            }else{
                return response()->json([
                    'status'=>404,
                    'message'=>"Emplacement n'est pas trouver"
                ],404);
            }
        }
        public function edit($id)
        {
            $emplacement=emplacement::find($id);
            if($emplacement){
                return response()->json([
                    'status'=>200,
                    'message'=>$emplacement
                ],200);
            }else{
                return response()->json([
                    'status'=>404,
                    'message'=>"emplacement n'est pas trouver"
                ],404);
            }
        }                  
        public function update(Request $request, int $id)
        {
            $validator = Validator::make($request->all(), [
                'nom'=> 'required|string|max:191',
                'description'=> 'string',
            ]);
    
            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }
    
            $emplacement = Emplacements::find($id);
    
            if (!$emplacement) {
                return response()->json(['message' => "Emplacement n'est trouvé"], 404);
            }
    
            $emplacement->update($request->all());
            return response()->json([
                'status' => 200,
                'message' => "Emplacement modifiée avec succés",
            ], 200);
        }   
        public function destroy($id)
        {
            $emplacement= emplacements::find($id);
            if($emplacement)
            {
             $emplacement->delete();
             return response()->json([
                'status'=>200,
                'message'=> "Emplacement supprimer avec succés"
            ],200);
            }else{
                return response()->json([
                    'status'=>404,
                    'message'=> "something went wrong"
                ],404);
            }
        }

}
