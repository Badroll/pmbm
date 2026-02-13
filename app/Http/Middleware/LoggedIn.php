<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Session;

use App\Models\User as mUser;
use App\Models\Siswa as mSiswa;
use App\Models\Guru as mGuru;

class LoggedIn
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(!Session::has("SESSION_U_LOGIN_TOKEN")) {
            Session::flush();
            return redirect("auth/login")->with("warning", "Silakan login terlebih dahulu.");
        }

        $loginUser = mUser::getByLoginToken(Session::get("SESSION_U_LOGIN_TOKEN"));
        if(!$loginUser){
            Session::flush();
            return redirect("auth/login")->with("warning", "Sesi expired, silahkan login ulang.");
        }

        if (Carbon::now()->greaterThan(Carbon::parse($loginUser->U_LOGIN_TOKEN_EXPIRED))) {
            return compose("ERROR", "Sesi login kadaluarsa, silahkan login kembali. (#003)");
        }

        $loginUser->load([
            "siswa"
        ]);

        // sisipkan langsung object
        $request->merge([
            'loginUser' => $loginUser
        ]);

        return $next($request);
    }
}
