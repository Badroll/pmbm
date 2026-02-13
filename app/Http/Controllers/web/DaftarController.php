<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Siswa as mSiswa;
use App\Models\User as mUser;

class DaftarController extends Controller
{

    public function daftar(Request $request){
        $req = $request->all();

        $viewData = [];
        return view("daftar", $viewData);
    }

    public function examDetail($examId, Request $request){
        $req = $request->all();

        $exam = null;
        $soalPG = [];
        $soalEssay = [];
        $pembelajaran = null;
        $ujian = null;

        if($examId != "0"){
            $exam = mExam::find($examId);
            $soalPG = mExamSoal::getPG($examId);
            $soalEssay = mExamSoal::getEssay($examId);
        }
        if(isset($req["pblId"])){
            $pembelajaran = mPembelajaran::find($req["pblId"]);
        }
        if(isset($req["ujnId"])){
            $ujian = mUjian::find($req["ujnId"]);
        }

        $refJenis = getReferences("TUGAS_ASESMEN");

        $viewData = [
            "newMode" => $examId == 0,
            "examId" => $examId,
            "exam" => $exam,
            "soalPG" => $soalPG,
            "soalEssay" => $soalEssay,
            "pembelajaran" => $pembelajaran,
            "ujian" => $ujian,
            "refJenis" => $refJenis,
        ];
        if(isset($req["json"])){
            dd($viewData["exam"]->akses);
        }
        //dd($ujian);
        return view("exam-detail", $viewData);
    }

    public function examDetailSiswa($examId, $siswaId, Request $request){
        $req = $request->all();

        $exam = mExam::find($examId);
        $siswa = mSiswa::find($siswaId);
        $pengerjaan = mPengerjaan::getByExamAndSiswa($examId, $siswaId);

        $viewData = [
            "exam" => $exam,
            "siswa" => $siswa,
            "pengerjaan" => $pengerjaan,
        ];
        if(isset($req["json"])) dd($viewData);
        return view("exam-detail-siswa", $viewData);
    }

}
