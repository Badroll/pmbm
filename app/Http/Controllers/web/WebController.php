<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use App\Models\Inbox as mInbox;
use App\Models\Siswa as mSiswa;
use App\Models\User as mUser;

class WebController extends Controller
{

    public function inbox(Request $request){
        $loginUser = $request->loginUser; // login user
        $req = $request->all();

        $inbox = mUser::find($loginUser->U_ID)->inbox;

        $viewData = [
            "inbox" => $inbox,
            "siswa" => mSiswa::getByUserId($loginUser->U_ID)
        ];

        if(isset($req["json"])) dd($viewData);
        return view("inbox", $viewData);
    }


    public function kartu(Request $request){
        $loginUser = $request->loginUser; // login user
        $req = $request->all();

        $viewData = [
            "siswa" => mSiswa::getByUserId($loginUser->U_ID)
        ];

        if(isset($req["json"])) dd($viewData);
        return view("kartu", $viewData);
    }

}
