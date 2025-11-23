<?php

use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\ServiceApiController;
use App\Http\Controllers\Api\SubcategoryApiController;
use App\Http\Controllers\Api\WeddingApiController;
use App\Http\Controllers\Api\ReviewApiController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']); //LOGIN PUBLICO
Route::post('/register', [AuthController::class, 'register']); //REGISTRO PUBLICO
Route::get('/users/{id}', [UserApiController::class, 'show']); //VER USUARIO PUBLICO
Route::get('/categories', [CategoryApiController::class, 'index']);//INDICE CATEGORIAS PUBLICO
Route::get('/services', [ServiceApiController::class, 'index']);//INDICE SERVICIOS PUBLICO
Route::get('/services/{id}', [ServiceApiController::class, 'show']);//VER SERVICIO PUBLICO
Route::get('/services/{id}/images', [ServiceApiController::class, 'images']);//IMAGENES SERVICIO PUBLICO
Route::get('/subcategories', [SubcategoryApiController::class, 'index']);//INDICE SUBCATEGORIAS PUBLICO
Route::get('/subcategories/{id}', [SubcategoryApiController::class, 'show']);//VER SUBCATEGORIAS PUBLICO
Route::get('/reviews', [ReviewApiController::class, 'index']);//INDICE REVIEWS PUBLICO
Route::get('/reviews/{id}', [ReviewApiController::class, 'show']);//VER REVIEW PUBLICO


Route::middleware('auth:sanctum')->group(function() {
    //LOGOUT
    Route::post('/logout', [AuthController::class, 'logout']);
    //SERVICIOS DEL USUARIO AUTENTICADO (BUSINESS/ADMIN)
    Route::get('/my/services', [ServiceApiController::class, 'myServices']);
    //CRUD USUARIOS
    Route::apiResource('users', UserApiController::class)->only(['index', 'store', 'update', 'destroy']);
    //CRUD CATEGORIAS
    Route::apiResource('categories', CategoryApiController::class)->only(['store', 'update', 'destroy']);
    //CRUD SERVICIOS
    Route::apiResource('services', ServiceApiController::class)->only(['store', 'update', 'destroy']);
    //IMAGENES DE SERVICIOS
    Route::post('/services/{id}/images', [ServiceApiController::class, 'addImage']);
    Route::delete('/services/{service}/images/{image}', [ServiceApiController::class, 'deleteImage']);
    //CRUD SUBCATEGORIAS
    Route::apiResource('subcategories', SubcategoryApiController::class)->only(['store', 'update', 'destroy']);
    //CRUD REVIEWS
    Route::apiResource('reviews', ReviewApiController::class)->only(['store', 'update', 'destroy']);
    //CRUD WEDDINGS
    Route::apiResource('weddings', WeddingApiController::class)->only(['index', 'store', 'show', 'update', 'destroy']);

    //SERVICIOS DE UNA BODA
    Route::get('/weddings/{wedding}/services', [WeddingApiController::class, 'services']);
    Route::post('/weddings/{wedding}/services', [WeddingApiController::class, 'attachService']);
    Route::put('/weddings/{wedding}/services/{service}', [WeddingApiController::class, 'updateService']);
    Route::delete('/weddings/{wedding}/services/{service}', [WeddingApiController::class, 'detachService']);
});


//php artisan route:list --path=api
