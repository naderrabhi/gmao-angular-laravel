<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\AffectationDesOrdres;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AffectationDesOrdresController extends Controller
{

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ordre_travail_id' => 'required|string',
            'technicien_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Check for existing assignment
        $existingAssignment = AffectationDesOrdres::where('ordre_travail_id', $request->ordre_travail_id)->exists();

        if ($existingAssignment) {
            return response()->json([
                'status' => 422,
                'message' => "Cet ordre de travail est déjà affecté à un technicien",
            ], 422);
        }

        $affectationDeOrdre = AffectationDesOrdres::create([
            'ordre_travail_id' =>$request->ordre_travail_id,
            'technicien_id' =>$request->technicien_id,
        ]);
        
        if($affectationDeOrdre){
            return response()->json([
                'status'=>200,
                'message'=> "Affectation de ordre créer avec succés"
            ],200);
        }else{
            return response()->json([
                'status'=>500,
                'message'=> "Une erreur s'est produite"
            ],500);

        }
    }

    public function index()
    {
        $affectationsDeOrdres = AffectationDesOrdres::with('ordreTravail', 'technicien')->get();

        $responseData = [];

        foreach ($affectationsDeOrdres as $affectationDeOrdre) {
            $data = [
                'id' =>$affectationDeOrdre->id,
                'ordre_travail_id' =>$affectationDeOrdre->ordre_travail_id,
                'technicien_id' =>$affectationDeOrdre->technicien_id,
                'date_resolution' =>$affectationDeOrdre->date_resolution,
                'date_confirmation' =>$affectationDeOrdre->date_confirmation,
                'reparer' =>$affectationDeOrdre->reparer,
                'confirmer' =>$affectationDeOrdre->confirmer,
                'created_at' =>$affectationDeOrdre->created_at,
                'updated_at' =>$affectationDeOrdre->updated_at,
            ];

            if ($affectationDeOrdre->technicien) {
                $data['technicien'] = $affectationDeOrdre->technicien->toArray();
            } else {
                $data['technicien'] = null;
            }

            if ($affectationDeOrdre->ordreTravail) {
                $data['ordreTravail'] = $affectationDeOrdre->ordreTravail->toArray();
            } else {
                $data['ordreTravail'] = null;
            }

            $responseData[] = $data;
        }

        return response()->json([
            'status' => 200,
            'message' => 'Affectation des ordres récupérés avec succès',
            'data' => $responseData,
        ], 200);
    }

    public function show($id)
    {
        $affectationDeOrdre = AffectationDesOrdres::with('ordreTravail', 'technicien')->find($id);

        if (!$affectationDeOrdre) {
            return response()->json(['message' => 'Affectation de ordre non trouvé'], 404);
        }

        return response()->json([
            'id' =>$affectationDeOrdre->id,
            'ordre_travail_id' =>$affectationDeOrdre->ordre_travail_id,
            'technicien_id' =>$affectationDeOrdre->technicien_id,
            'date_resolution' =>$affectationDeOrdre->date_resolution,
            'created_at' =>$affectationDeOrdre->created_at,
            'updated_at' =>$affectationDeOrdre->updated_at,
            'date_confirmation' =>$affectationDeOrdre->date_confirmation,
            'reparer' =>$affectationDeOrdre->reparer,
            'confirmer' =>$affectationDeOrdre->confirmer,
            'technicien' => $affectationDeOrdre->technicien ? $affectationDeOrdre->technicien->toArray() : null,
            'ordreTravail' => $affectationDeOrdre->ordreTravail ? $affectationDeOrdre->ordreTravail->toArray() : null,
        ]);
    }

    public function getByTechnician($id)
    {
        $affectationsDeOrdres = AffectationDesOrdres::with('ordreTravail', 'technicien')->where('technicien_id', $id)->get();

        if ($affectationsDeOrdres->isEmpty()) {
            return response()->json(['message' => "Aucune affectation d'ordre de travail trouvée pour ce technicien"], 404);
        }

        $responseData = [];
        foreach ($affectationsDeOrdres as $affectationDeOrdre) {
            $data = [
                'id' =>$affectationDeOrdre->id,
                'ordre_travail_id' =>$affectationDeOrdre->ordre_travail_id,
                'technicien_id' =>$affectationDeOrdre->technicien_id,
                'date_resolution' =>$affectationDeOrdre->date_resolution,
                'created_at' =>$affectationDeOrdre->created_at,
                'updated_at' =>$affectationDeOrdre->updated_at,
                'date_confirmation' =>$affectationDeOrdre->date_confirmation,
                'reparer' =>$affectationDeOrdre->reparer,
                'confirmer' =>$affectationDeOrdre->confirmer,
                'technicien' => $affectationDeOrdre->technicien ? $affectationDeOrdre->technicien->toArray() : null,
                'ordreTravail' => $affectationDeOrdre->ordreTravail ? $affectationDeOrdre->ordreTravail->toArray() : null,
            ];
            $responseData[] = $data;
        }

        return response()->json($responseData);
    }

    public function getByPeripheral($id)
    {
        $affectationsDeOrdres = AffectationDesOrdres::with('ordreTravail', 'technicien')->where('ordre_travail_id', $id)->get();


        if ($affectationsDeOrdres->isEmpty()) {
            return response()->json(['message' => "Aucune affectation d'ordre de travail trouvée pour ce ordre de travail"], 404);
        }

        $responseData = [];
        foreach ($affectationsDeOrdres as $affectationDeOrdre) {
            $data = [
                'id' =>$affectationDeOrdre->id,
                'ordre_travail_id' =>$affectationDeOrdre->ordre_travail_id,
                'technicien_id' =>$affectationDeOrdre->technicien_id,
                'date_resolution' =>$affectationDeOrdre->date_resolution,
                'created_at' =>$affectationDeOrdre->created_at,
                'updated_at' =>$affectationDeOrdre->updated_at,
                'date_confirmation' =>$affectationDeOrdre->date_confirmation,
                'reparer' =>$affectationDeOrdre->reparer,
                'confirmer' =>$affectationDeOrdre->confirmer,
                'technicien' => $affectationDeOrdre->technicien ? $affectationDeOrdre->technicien->toArray() : null,
                'ordreTravail' => $affectationDeOrdre->ordreTravail ? $affectationDeOrdre->ordreTravail->toArray() : null,
            ];
            $responseData[] = $data;
        }

        return response()->json($responseData);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'ordre_travail_id' => 'required|string',
            'technicien_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $affectationDeOrdre = AffectationDesOrdres::find($id);

        if (!$affectationDeOrdre) {
            return response()->json(['message' => 'Affectation de ordre non trouvé'], 404);
        }

        $affectationDeOrdre->update($request->all());
        return response()->json([
            'status' => 200,
            'message' => "Affectation de ordre modifiée avec succés",
        ], 200);
    }

    public function destroy($id)
    {
        $affectationDeOrdre = AffectationDesOrdres::find($id);

        if (!$affectationDeOrdre) {
            return response()->json(['message' => 'Affectation de ordre non trouvé'], 404);
        }

        $affectationDeOrdre->delete();

        return response()->json(['message' => 'Affectation de ordre supprimer avec succés']);
    }

}
