<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\web\AuthController;
use App\Http\Controllers\web\WebController;
use App\Http\Controllers\web\MasterController;
use App\Http\Controllers\web\DaftarController;
use App\Http\Controllers\web\BeritaController;
use App\Http\Controllers\web\ExcelController;
use App\Http\Controllers\web\ExamController;


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

// Route::prefix("jurnal")->name("jurnal.")->group(function () {
//     Route::get("/", [WebController::class, "jurnal"])->name("index");
//     Route::get("datatable", [WebController::class, "jurnalDatatable"])->name("datatable");
// });

// -------------------------------------------------------------------------

Route::middleware("loggedin")->group(function () {

    Route::get("profil", [AuthController::class, "profil"]);
    
    Route::prefix("daftar")->group(function () {
        Route::get("/", [DaftarController::class, "daftar"]);
        Route::post("/", [DaftarController::class, "doDaftar"]);
        Route::put("/", [DaftarController::class, "updateDaftar"]);
    });

    Route::prefix("daftar-ulang")->group(function () {
        Route::get("/", [DaftarController::class, "daftarUlang"]);
        Route::post("/", [DaftarController::class, "daftarUlangSave"]);
        // Route::put("/", [DaftarController::class, "updateDaftar"]);
    });

    Route::get("daftar-ulang-view", [DaftarController::class, "daftarUlangView"]);

    Route::get("pengumuman", [DaftarController::class, "pengumuman"]);

    Route::prefix("inbox")->group(function () {
        Route::get("/", [WebController::class, "inbox"]);
    });

    Route::prefix("siswa")->group(function () {
        Route::get("/", [DaftarController::class, "siswa"]);

        Route::get("update-bulk-khusus", [DaftarController::class, "updateBulkKhusus"]);
        Route::get("update-bulk-rollback", [DaftarController::class, "updateBulkRollback"]);

        Route::get("/{siswaId}", [DaftarController::class, "siswaDetail"]);
        Route::post("update-status", [DaftarController::class, "siswaUpdateStatus"]);
    });

    Route::prefix("kartu")->group(function () {
        Route::get("/", [WebController::class, "kartu"]);
    });

    Route::prefix('admin')->name("admin.")->group(function () {
        Route::prefix("manage")->name("manage.")->group(function () {
            Route::get('/',                        [AuthController::class, 'admin'])->name('admin');
            Route::get('datatable',                [AuthController::class, 'datatable'])->name('datatable');
            Route::post('/',                       [AuthController::class, 'createAdmin'])->name('create');
            Route::post('{id}',                    [AuthController::class, 'updateAdmin'])->name('update');
            Route::delete('{id}',                  [AuthController::class, 'deleteAdmin'])->name('deleteAdmin');
        });
        Route::prefix("berita")->name("berita.")->group(function () {
            Route::get('/',                         [BeritaController::class, 'list'])->name('index');
            Route::get('/data',                     [BeritaController::class, 'data'])->name('data');
            Route::get('/create',                   [BeritaController::class, 'create'])->name('create');
            Route::post('/',                        [BeritaController::class, 'store'])->name('store');
            Route::get('/{id}/edit',                [BeritaController::class, 'edit'])->name('edit');
            Route::post('/{id}',                    [BeritaController::class, 'update'])->name('update');
            Route::delete('/{id}',                  [BeritaController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/toggle-status',      [BeritaController::class, 'toggleStatus'])->name('toggleStatus');
        });
        Route::prefix("bta")->name("bta.")->group(function () {
            Route::post('update',                   [DaftarController::class, 'updateBTA'])->name('updateBTA');
        });
    });

    Route::prefix('exam')->name("exam.")->group(function () {
        Route::get('/', [ExamController::class, 'index'])->name('index');
        Route::post('verify-token', [ExamController::class, 'verifyToken'])->name('verify-token');
        Route::post('save-answer', [ExamController::class, 'saveAnswer'])->name('save-answer');
        Route::post('finish', [ExamController::class, 'finish'])->name('finish');
        
        Route::get('monitor', [ExamController::class, 'monitor'])->name('monitor');
        Route::post('refresh-token', [ExamController::class, 'refreshToken'])->name('refreshToken');
    });

});


// -------------------------------------------------------------------------
Route::prefix("public")->group(function () {

    Route::prefix("excel")->group(function () {
        Route::get("kartu-pendaftaran", [ExcelController::class, 'kartuPendaftaran']);
        Route::get("data-pendaftar", [ExcelController::class, 'dataPendaftar']);
        Route::get("daftar-ulang/{id}", [ExcelController::class, 'daftarUlang']);
    });

});