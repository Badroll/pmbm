<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengerjaan extends Model
{
    protected $table = "pengerjaan";
    protected $primaryKey = "PGRJN_ID";
    public $timestamps = false;

    protected $fillable = [
        "SISWA_ID",
        "PGRJN_JENIS", // Akademik/Psikotest
        "PGRJN_MULAI",
        "PGRJN_SELESAI",
        "PGRJN_NILAI",
    ];

    // RELASI -------------------------------------------------------------------------------------------------------------

    public function siswa(){
        return $this->belongsTo(Siswa::class, "SISWA_ID", "SISWA_ID");
    }

    public function pengerjaanJawaban(){
        return $this->hasMany(PengerjaanJawaban::class, "PGRJN_ID", "PGRJN_ID");
    }


    // CRUD -------------------------------------------------------------------------------------------------------------


    // CUSTOM -------------------------------------------------------------------------------------------------------------

    
}
