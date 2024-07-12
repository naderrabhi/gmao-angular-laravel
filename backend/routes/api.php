<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\EmplacementsController;
use App\Http\Controllers\Api\EquipementsController;
use App\Http\Controllers\Api\OrdresDeTravailController;
use App\Http\Controllers\Api\AffectationDesOrdresController;
use App\Http\Controllers\Api\LoginController;

Route::post('login', [LoginController::class, 'login']);
Route::post('register', [UserController::class, 'store']);
Route::get('get-current-user', [UserController::class, 'getCurrentUser']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('emplacements', [EmplacementsController::class, 'store']);
    Route::get('emplacements', [EmplacementsController::class, 'index']);
    Route::get('emplacements/{id}', [EmplacementsController::class, 'show']);
    Route::put('emplacements/{id}/modifier', [EmplacementsController::class, 'update']);
    Route::delete('emplacements/{id}/supprimer', [EmplacementsController::class, 'destroy']);

    Route::post('equipements', [EquipementsController::class, 'store']);
    Route::get('equipements', [EquipementsController::class, 'index']);
    Route::get('equipements/emplacements/{id}', [EquipementsController::class, 'getAllEquipementsWithEmplacement']);
    Route::get('equipements/{id}', [EquipementsController::class, 'show']);
    Route::put('equipements/{id}/modifier', [EquipementsController::class, 'update']);
    Route::delete('equipements/{id}/supprimer', [EquipementsController::class, 'destroy']);

    Route::post('ordres-de-travail', [OrdresDeTravailController::class, 'store']);
    Route::get('ordres-de-travail', [OrdresDeTravailController::class, 'index']);
    Route::get('ordres-de-travail/{id}', [OrdresDeTravailController::class, 'show']);
    Route::get('ordres-de-travail/rapporter-par/{id}', [OrdresDeTravailController::class, 'getByReportedUser']);
    Route::put('ordres-de-travail/{id}/modifier', [OrdresDeTravailController::class, 'update']);
    Route::delete('ordres-de-travail/{id}/supprimer', [OrdresDeTravailController::class, 'destroy']);

    Route::get('users', [UserController::class, 'index']);
    Route::post('users', [UserController::class, 'store']);
    Route::get('users/{id}', [UserController::class, 'show']);
    Route::put('users/{id}/modifier', [UserController::class, 'update']);
    Route::delete('users/{id}/supprimer', [UserController::class, 'destroy']);

    Route::post('affectation-des-ordres', [AffectationDesOrdresController::class, 'store']);
    Route::get('affectation-des-ordres', [AffectationDesOrdresController::class, 'index']);
    Route::get('affectation-des-ordres/{id}', [AffectationDesOrdresController::class, 'show']);
    Route::get('affectation-des-ordres/technicien/{id}', [AffectationDesOrdresController::class, 'getByTechnician']);
    Route::get('affectation-des-ordres/ordres-de-travail/{id}', [AffectationDesOrdresController::class, 'getByPeripheral']);
    Route::put('affectation-des-ordres/{id}/modifier', [AffectationDesOrdresController::class, 'update']);
    Route::delete('affectation-des-ordres/{id}/supprimer', [AffectationDesOrdresController::class, 'destroy']);

});



