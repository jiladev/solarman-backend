<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VariableController;
use App\Mail\ClientCreated;
use App\Models\Client;
use App\Models\ClientEstimate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::post('/login', [AuthController::class, 'login'])->name('login');


Route::get('/clients-estimates', [ClientController::class, 'clientsEstimates']);

Route::prefix('/clients')->group(function () {
    Route::get('/', [ClientController::class, 'index']);
    Route::post('/handle-client-estimate', [ClientController::class, 'handleClientEstimate']);
    Route::get('/{id}', [ClientController::class, 'show']);
    Route::delete('/{id}', [ClientController::class, 'destroy']);
});


//Rotas protegidas para usuários autenticados
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::prefix('/users')->group(function () {
        Route::post('/', [UserController::class, 'store']);
        Route::get('/', [UserController::class, 'index']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::put('/{id}', [UserController::class, 'update']);
    });

    Route::prefix('/reports')->group(function () {
        Route::post('/', [ReportController::class, 'handleReport']);
    });

    Route::prefix('/variables')->group(function () {
        Route::get('/', [VariableController::class, 'index']);
        Route::put('/{id}', [VariableController::class, 'update']);
    });

    Route::prefix('/reports')->group(function () {
        Route::get('/', [ReportController::class, 'index']);
        Route::get('/{id}', [ReportController::class, 'show']);
        Route::delete('/{id}', [ReportController::class, 'destroy']);
        Route::get('/generatePdf/{id}', [ReportController::class, 'generatePdf']);
    });
});

//Preview PDF
// Route::get('/preview-pdf', function () {
//     return view('pdf.report');
// });


