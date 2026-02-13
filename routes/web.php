<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\web\AuthController;
use App\Http\Controllers\web\WebController;
use App\Http\Controllers\web\MasterController;
use App\Http\Controllers\web\DaftarController;
use App\Http\Controllers\web\ExcelController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get("/", function () {
    return view("home");
});

/*

*/

Route::prefix("auth")->group(function () {
    Route::get("login", [AuthController::class, "login"])->name("login");
    Route::post("login", [AuthController::class, "doLogin"])->name("doLogin");
    Route::get("register", [AuthController::class, "register"])->name("register");
    Route::post("register", [AuthController::class, "doRegister"])->name("doRegister");
    Route::get("logout", [AuthController::class, "logout"])->name("logout");
});

// -------------------------------------------------------------------------

Route::middleware("loggedin")->group(function () {

    Route::get("profil", [AuthController::class, "profil"]);
    
    Route::prefix("daftar")->group(function () {
        Route::get("/", [DaftarController::class, "daftar"]);
    });

    Route::get("/inbox", function () {
        return view("inbox");
    });

    Route::get("/kartu", function () {
        return view("kartu");
    });

});


// -------------------------------------------------------------------------
Route::prefix("public")->group(function () {

    // WILAYAH
    Route::get("excel/download/exam/template", [ExcelController::class, 'downloadTemplateExam']);

});