<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

use App\Models\Provinsi as mProvinsi;
use App\Models\Kota as mKota;
use App\Models\Kecamatan as mKecamatan;
use App\Models\Kelurahan as mKelurahan;

class APIController extends Controller
{
    // CONTROLLER JUST FOR TESTING !!

    public function tes1(Request $request){
        
        return compose("SUCCESS", "ok");
    }

    public function tes2(Request $request){

    }
    
    public function tes3(Request $request){

    }

    public function tes4(Request $request){

    }

    public function tes5(Request $request){

    }


}
