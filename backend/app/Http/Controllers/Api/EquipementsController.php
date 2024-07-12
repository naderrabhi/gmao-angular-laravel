<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Equipements;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class EquipementsController extends Controller
{

    public function index()
    {
        $equipementsWithEmplacements = Equipements::with('emplacement')->get();

        $responseData = [];

        foreach ($equipementsWithEmplacements as $equipement) {
            $data = [
                'id' => $equipement->id,
                'nom' => $equipement->nom,
                'description' => $equipement->description,
                'numero_serie' => $equipement->numero_serie,
                'modele' => $equipement->modele,
                'marque' => $equipement->marque,
                'coleur' => $equipement->coleur,
            ];

            if ($equipement->emplacement) {
                $data['emplacement'] = [
                    'id' => $equipement->emplacement->id,
                    'nom' => $equipement->emplacement->nom,
                    'description' => $equipement->emplacement->description,
                ];
            }

            $responseData[] = $data;
        }

        return response()->json([
            'status' => 200,
            'message' => 'Equipements trouvés',
            'data' => $responseData,
        ], 200);
    }   
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string',
            'description' => 'required|string',
            'numero_serie' => 'required|string',
            'modele' => 'required|string',
            'marque' => 'required|string',
            'coleur' => 'required|string',
            'emplacement_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $equipement = Equipements::create([
            'nom' =>$request->nom,
            'description' =>$request->description,
            'numero_serie' =>$request->numero_serie,
            'modele' =>$request->modele,
            'marque' =>$request->marque,
            'coleur' =>$request->coleur,
            'emplacement_id' =>$request->emplacement_id,
        ]);
        
        if($equipement){
            return response()->json([
                'status'=>200,
                'message'=> "Equipement créer avec succés"
            ],200);
        }else{
            return response()->json([
                'status'=>500,
                'message'=> "something went wrong"
            ],500);

        }
    }
    public function show($id)
    {
        $equipementWithEmplacement = Equipements::with('emplacement')->find($id);

        if (!$equipementWithEmplacement) {
            return response()->json([
                'status' => 404,
                'message' => "Equipement non trouvée",
            ], 404);
        }

        $responseData = [
            'id' => $equipementWithEmplacement->id,
                'nom' => $equipementWithEmplacement->nom,
                'description' => $equipementWithEmplacement->description,
                'numero_serie' => $equipementWithEmplacement->numero_serie,
                'modele' => $equipementWithEmplacement->modele,
                'marque' => $equipementWithEmplacement->marque,
                'coleur' => $equipementWithEmplacement->coleur,
        ];

        if ($equipementWithEmplacement->emplacement) {
            $responseData['emplacement'] = [
                'id' => $equipementWithEmplacement->emplacement->id,
                'nom' => $equipementWithEmplacement->emplacement->nom,
                'description' => $equipementWithEmplacement->emplacement->description,
            ];
        }

        return response()->json([
            'status' => 200,
            'message' => 'Equipement trouvés',
            'data' => $responseData,
        ], 200);
    }
    public function edit($id)
    {
        $equipement=Equipements::find($id);
        if($equipement){
            return response()->json([
                'status'=>200,
                'message'=>$equipement
            ],200);
        }else{
            return response()->json([
                'status'=>404,
                'message'=>"n'est pas trouver"
            ],404);
        }
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string',
            'description' => 'required|string',
            'numero_serie' => 'required|string',
            'modele' => 'required|string',
            'marque' => 'required|string',
            'coleur' => 'required|string',
            'emplacement_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $equipement = Equipements::find($id);

        if (!$equipement) {
            return response()->json(['message' => "Equipement n'est pas trouver"], 404);
        }

        $equipement->update($request->all());

        return response()->json([
            'status' => 200,
            'message' => "Equipement modifiée avec succés",
        ], 200);
    }
    public function destroy($id)
    {
        $equipement = Equipements::find($id);

        if (!$equipement) {
            return response()->json(['message' => "Equipement n'est pas trouver"], 404);
        }

        $equipement->delete();

        return response()->json(['message' => 'Equipement supprimer avec succés']);
    }

    public function getAllEquipementsWithEmplacement(Request $request)
    {
        $idEmplacement = $request->route('id');

        $equipements = Equipements::where('emplacement_id', $idEmplacement)->get();

        $responseData = [];

        foreach ($equipements as $equipement) {
            $data = [
                'id' => $equipement->id,
                'nom' => $equipement->nom,
                'description' => $equipement->description,
                'numero_serie' => $equipement->numero_serie,
                'modele' => $equipement->modele,
                'marque' => $equipement->marque,
                'coleur' => $equipement->coleur,
            ];

            if ($equipement->emplacement) {
                $data['emplacement'] = [
                    'id' => $equipement->emplacement->id,
                    'nom' => $equipement->emplacement->nom,
                    'description' => $equipement->emplacement->description,
                ];
            }

            $responseData[] = $data;
        }
      
        if ($responseData) {
          return response()->json([
            'data' => $responseData,  // Return only the equipment ID
          ], 200);
        } else {
          return response()->json([
            'message' => 'Aucun équipement trouvé',  // Inform if no equipment found
          ], 404);
        }

        // $equipementsWithEmplacements = Equipements::with('emplacement');

        // if ($idEmplacement) {
        //     $equipementsWithEmplacements = $equipementsWithEmplacements->whereHas('emplacement', function ($query) use ($idEmplacement) {
        //     $query->where('id', $idEmplacement);
        //     });
        // }

        // $equipementsWithEmplacements = $equipementsWithEmplacements->get();

        // $responseData = [];

        // foreach ($equipementsWithEmplacements as $equipement) {
        //     $data = [
        //     'id' => $equipement->id,
        //     'nom' => $equipement->nom,
        //     'description' => $equipement->description,
        //     'numero_serie' => $equipement->numero_serie,
        //     'modele' => $equipement->modele,
        //     'marque' => $equipement->marque,
        //     'coleur' => $equipement->coleur,
        //     ];

        //     if ($equipement->emplacement) {
        //     $data['emplacement'] = [
        //         'id' => $equipement->emplacement->id,
        //         'nom' => $equipement->emplacement->nom,
        //         'description' => $equipement->emplacement->description,
        //     ];
        //     }

        //     $responseData[] = $data;
        // }

        // return response()->json([
        //     'status' => 200,
        //     'message' => 'Equipements trouvés',
        //     'data' => $responseData,
        // ], 200);
    }

}
