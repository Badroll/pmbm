<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengerjaanJawaban extends Model
{
    protected $table = "pengerjaan_jawaban";
    protected $primaryKey = "JWB_ID";
    public $timestamps = false;

    protected $fillable = [
        "PGRJN_ID",
        "EXAM_ID",
        "JWB_KET",
        "JWB_WAKTU",
    ];

    // RELASI -------------------------------------------------------------------------------------------------------------

    public function pengerjaan(){
        return $this->belongsTo(Pengerjaan::class, "PGRJN_ID", "PGRJN_ID");
    }

    public function exam(){
        return $this->belongsTo(Exam::class, "EXAM_ID", "EXAM_ID");
    }


    // CRUD -------------------------------------------------------------------------------------------------------------


    // CUSTOM -------------------------------------------------------------------------------------------------------------


    
}
