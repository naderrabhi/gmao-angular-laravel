<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\OrdresDeTravail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class OrdresDeTravailController extends Controller
{

    public function index()
    {

        $ordresDeTravail = OrdresDeTravail::with('equipement', 'user')->get();

        $responseData = [];

        foreach ($ordresDeTravail as $ordreDeTravail) {
            $data = [
                'id' => $ordreDeTravail->id,
                'titre' => $ordreDeTravail->titre,
                'description' => $ordreDeTravail->description,
                'urgent' => $ordreDeTravail->urgent,
                'statut' => $ordreDeTravail->statut,
                'utilisateur_id' => $ordreDeTravail->utilisateur_id,
                'equipement_id' => $ordreDeTravail->equipement_id,
            ];

            if ($ordreDeTravail->equipement) {
                $data['equipement'] = $ordreDeTravail->equipement->toArray();
            } else {
                $data['equipement'] = null;
            }

            if ($ordreDeTravail->equipement->emplacement) {
                $data['emplacement'] = $ordreDeTravail->equipement->emplacement->toArray();
            } else {
                $data['emplacement'] = null;
            }

            if ($ordreDeTravail->user) {
                $data['user'] = $ordreDeTravail->user->toArray();
            } else {
                $data['user'] = null;
            }

            $responseData[] = $data;
        }

        return response()->json([
            'status' => 200,
            'message' => 'Ordres de travail récupérés avec succès',
            'data' => $responseData,
        ], 200);
    }
    
    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'titre' => 'required|string',
    //         'description' => 'required|string',
    //         'statut' => 'required|string', // (exp: En panne, En attente, En cours, Réparé)
    //         'utilisateur_id' => 'required|string',
    //         'equipement_id' => 'required|string',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json($validator->errors(), 422);
    //     }

    //     $ordresDeTravail = OrdresDeTravail::create([
    //         'titre' =>$request->titre,
    //         'description' =>$request->description,
    //         'urgent' =>$request->urgent,
    //         'statut' =>$request->statut,
    //         'utilisateur_id' =>$request->utilisateur_id,
    //         'equipement_id' =>$request->equipement_id,
    //     ]);
        
    //     if($ordresDeTravail){
    //         return response()->json([
    //             'status'=>200,
    //             'message'=> "Ordres de travail créer avec succés"
    //         ],200);
    //     }else{
    //         return response()->json([
    //             'status'=>500,
    //             'message'=> "something went wrong"
    //         ],500);

    //     }
    // }

    public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'titre' => 'required|string',
        'description' => 'required|string',
        'statut' => 'required|string', // (exp: En panne, En attente, En cours, Réparé)
        'utilisateur_id' => 'required|string',
        'equipement_id' => 'required|string',
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    // Check if equipment with the provided ID exists
    $existingOrdre = OrdresDeTravail::where('equipement_id', $request->equipement_id)->exists();

    if (!$existingOrdre) {
        // Create a new order if equipment doesn't exist
        $ordresDeTravail = OrdresDeTravail::create([
            'titre' => $request->titre,
            'description' => $request->description,
            'urgent' => $request->urgent,
            'statut' => $request->statut,
            'utilisateur_id' => $request->utilisateur_id,
            'equipement_id' => $request->equipement_id,
        ]);

        if ($ordresDeTravail) {
            return response()->json([
                'status' => 200,
                'message' => "Ordres de travail créé avec succès",
            ], 200);
        } else {
            return response()->json([
                'status' => 500,
                'message' => "Une erreur est survenue lors de la création de l'ordre de travail",
            ], 500);
        }
    } else {
        // Check if existing order with that equipment has status other than "Réparé"
        $existingActiveOrdre = OrdresDeTravail::where('equipement_id', $request->equipement_id)
            ->where('statut', '!=', 'Réparé')
            ->exists();

        if ($existingActiveOrdre) {
            return response()->json([
                'status' => 422,
                'message' => "Un ordre de travail est déjà en cours pour cet équipement",
            ], 422);
        } else {
            // Create a new order if existing equipment has "Réparé" status
            $ordresDeTravail = OrdresDeTravail::create([
                'titre' => $request->titre,
                'description' => $request->description,
                'urgent' => $request->urgent,
                'statut' => $request->statut,
                'utilisateur_id' => $request->utilisateur_id,
                'equipement_id' => $request->equipement_id,
            ]);

            if ($ordresDeTravail) {
                return response()->json([
                    'status' => 200,
                    'message' => "Ordres de travail créé avec succès",
                ], 200);
            } else {
                return response()->json([
                    'status' => 500,
                    'message' => "Une erreur est survenue lors de la création de l'ordre de travail",
                ], 500);
            }
        }
    }
}

    public function show($id)
    {
        $ordreDeTravail = OrdresDeTravail::with('equipement', 'user')->find($id);

        if (!$ordreDeTravail) {
            return response()->json([
                'status' => 404,
                'message' => "ordre de travail non trouvée",
            ], 404);
        }

        $responseData = [
            'id' => $ordreDeTravail->id,
            'titre' => $ordreDeTravail->titre,
            'description' => $ordreDeTravail->description,
            'urgent' => $ordreDeTravail->urgent,
            'statut' => $ordreDeTravail->statut,
            'utilisateur_id' => $ordreDeTravail->utilisateur_id,
            'equipement_id' => $ordreDeTravail->equipement_id,
        ];

        if ($ordreDeTravail->equipement) {
            $responseData['equipement'] = $ordreDeTravail->equipement->toArray();
        } else {
            $responseData['equipement'] = null;
        }

        if ($ordreDeTravail->equipement->emplacement) {
            $responseData['emplacement'] = $ordreDeTravail->equipement->emplacement->toArray();
        } else {
            $responseData['emplacement'] = null;
        }

        if ($ordreDeTravail->user) {
            $responseData['user'] = $ordreDeTravail->user->toArray();
        } else {
            $responseData['user'] = null;
        }


        return response()->json([
            'status' => 200,
            'message' => 'Ordre de travail récupérés avec succès ffff',
            'data' => $responseData,
        ], 200);
    }


    public function getByReportedUser($id)
{
    $ordresDeTravail = OrdresDeTravail::with('user')
        ->where('utilisateur_id', $id)
        ->get();

    if ($ordresDeTravail->isEmpty()) {
        return response()->json([
            'status' => 404,
            'message' => 'Aucun ordre de travail signalé par cet utilisateur',
        ], 404);
    }

    $responseData = [];
    foreach ($ordresDeTravail as $ordreDeTravail) {
        $data = [
            'id' => $ordreDeTravail->id,
            'titre' => $ordreDeTravail->titre,
            'description' => $ordreDeTravail->description,
            'urgent' => $ordreDeTravail->urgent,
            'statut' => $ordreDeTravail->statut,
            'utilisateur_id' => $ordreDeTravail->utilisateur_id,
            'equipement_id' => $ordreDeTravail->equipement_id,
        ];

        if ($ordreDeTravail->equipement) {
            // Include emplacement_id in equipement data
            $data['equipement'] = $ordreDeTravail->equipement->toArray();
        } else {
            $data['equipement'] = null;
        }

        if ($ordreDeTravail->equipement->emplacement) {
            // Include emplacement_id in equipement data
            $data['emplacement'] = $ordreDeTravail->equipement->emplacement->toArray();
        } else {
            $data['emplacement'] = null;
        }

        if ($ordreDeTravail->user) {
            $data['user'] = $ordreDeTravail->user->toArray();
        } else {
            $data['user'] = null;
        }

        $responseData[] = $data;
    }

    return response()->json([
        'status' => 200,
        'message' => 'Ordres de travail récupérés avec succès',
        'data' => $responseData,
    ], 200);
}

    public function edit($id)
    {
        $ordreDeTravail=OrdresDeTravail::find($id);
        if($ordreDeTravail){
            return response()->json([
                'status'=>200,
                'message'=>$ordreDeTravail
            ],200);
        }else{
            return response()->json([
                'status'=>404,
                'message'=>"Ordre de travail n'est pas trouver"
            ],404);
        }
    }
    

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'titre' => 'required|string',
            'description' => 'required|string',
            'statut' => 'required|string', // (exp: Normale, En panne, En attente, En cours, Réparé)
            'utilisateur_id' => 'required|string',
            'equipement_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $ordreDeTravail = OrdresDeTravail::find($id);

        if (!$ordreDeTravail) {
            return response()->json(['message' => "Ordre de travail n'est pas trouver"], 404);
        }

        $ordreDeTravail->update($request->all());
        return response()->json([
            'status' => 200,
            'message' => "Ordre de travail modifiée avec succés",
        ], 200);
    }

    public function destroy($id)
    {
        $ordreDeTravail = OrdresDeTravail::find($id);

        if (!$ordreDeTravail) {
            return response()->json(['message' => "Ordre de travail n'est pas trouver"], 404);
        }

        $ordreDeTravail->delete();

        return response()->json(['message' => 'Ordre de travail supprimer avec succés']);
    }
}
