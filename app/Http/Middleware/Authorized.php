<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use App\Models\User as mUser;
use App\Models\Siswa as mSiswa;
use App\Models\Guru as mGuru;

class Authorized
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
        $loginToken = $request->header('x-login-token');
        if (!$loginToken || !mUser::isLoginTokenValid($loginToken)) {
            return compose("ERROR", "Sesi login kadaluarsa, silahkan login kembali");
        }

        $user = mUser::getByLoginToken($loginToken);
        $loginUserData = $user ? $user->toArray() : [];

        // cek header siswa
        $siswaId = $request->header('x-siswa-id');
        if (!empty($siswaId)) {
            $siswa = mSiswa::find($siswaId);
            if ($siswa) {
                $loginUserData = array_merge($loginUserData, $siswa->toArray());
            }
        }

        // cek header guru
        $guruId = $request->header('x-guru-id');
        if (!empty($guruId)) {
            $guru = mGuru::find($guruId);
            if ($guru) {
                $loginUserData = array_merge($loginUserData, $guru->toArray());
            }
        }

        // ubah ke object
        $loginUser = json_decode(json_encode($loginUserData));

        // sisipkan ke request
        $request->merge(['loginUser' => $loginUser]);

        return $next($request);
}


}
