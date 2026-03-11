<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\web\AuthController;
use App\Http\Controllers\web\WebController;
use App\Http\Controllers\web\MasterController;
use App\Http\Controllers\web\DaftarController;
use App\Http\Controllers\web\BeritaController;
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

Route::get("/", [WebController::class, "home"]);

/*

*/

Route::prefix("auth")->group(function () {
    Route::get("login", [AuthController::class, "login"])->name("login");
    Route::post("login", [AuthController::class, "doLogin"])->name("doLogin");
    Route::get("register", [AuthController::class, "register"])->name("register");
    Route::post("register", [AuthController::class, "doRegister"])->name("doRegister");
    Route::get("logout", [AuthController::class, "logout"])->name("logout");
});

Route::prefix("berita")->name("berita.")->group(function () {
    Route::get("/", [BeritaController::class, "index"])->name("index");
    Route::get("{slug}", [BeritaController::class, "show"])->name("show");
});

// -------------------------------------------------------------------------

Route::middleware("loggedin")->group(function () {

    Route::get("profil", [AuthController::class, "profil"]);
    
    Route::prefix("daftar")->group(function () {
        Route::get("/", [DaftarController::class, "daftar"]);
        Route::post("/", [DaftarController::class, "doDaftar"]);
        Route::put("/", [DaftarController::class, "updateDaftar"]);
    });

    Route::prefix("inbox")->group(function () {
        Route::get("/", [WebController::class, "inbox"]);
    });

    Route::prefix("siswa")->group(function () {
        Route::get("/", [DaftarController::class, "siswa"]);
        Route::get("/{siswaId}", [DaftarController::class, "siswaDetail"]);
    });

    Route::prefix("kartu")->group(function () {
        Route::get("/", [WebController::class, "kartu"]);
    });

});


// -------------------------------------------------------------------------
Route::prefix("public")->group(function () {

    Route::prefix("excel")->group(function () {
        Route::get("kartu-pendaftaran", [ExcelController::class, 'kartuPendaftaran']);
    });

});