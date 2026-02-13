<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\APIController;
use App\Http\Controllers\API\AuthController;

use App\Http\Controllers\web\ExcelController;

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

Route::get("/tes1", [APIController::class, "tes1"]);
Route::get("/tes2", [APIController::class, "tes2"]);
Route::get("/tes3", [APIController::class, "tes3"]);
Route::get("/tes4", [APIController::class, "tes4"]);
Route::get("/tes5", [APIController::class, "tes5"]);


Route::prefix("auth")->group(function () {
    Route::post("/login", [AuthController::class, "login"]);
    Route::post("/update", [AuthController::class, "update"]);
    Route::post("/delete", [AuthController::class, "delete"]);
    Route::post("/register", [AuthController::class, "register"]);
    Route::middleware("authorized")->group(function () {
        // Route::post("/login", [AuthController::class, "login"]);
        // Route::post("/update", [AuthController::class, "update"]);
        // Route::post("/delete", [AuthController::class, "delete"]);
        // Route::post("/register", [AuthController::class, "register"]);
    });
});

Route::middleware("authorized")->group(function () {

    Route::prefix("semester")->group(function () {
        //Route::post("/create", [SemesterController::class, "createSemester"]);
    });

});


// public
Route::prefix("public")->group(function () {
    Route::prefix("excel")->group(function () {
        //Route::post("wilayah", [ExcelController::class, 'importWilayah2']);
    });

});