<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $table = "exam";
    protected $primaryKey = "EXAM_ID";
    public $timestamps = false;
    protected $fillable = [
        'EXAM_JENIS', // Akademik/Psikotest
        'EXAM_NO',
        'EXAM_KET',
        'EXAM_A',
        'EXAM_A_BOBOT',
        'EXAM_B',
        'EXAM_B_BOBOT',
        'EXAM_C',
        'EXAM_C_BOBOT',
        'EXAM_D',
        'EXAM_D_BOBOT',
        'EXAM_KUNCI',
    ];


    // RELATION ------------------------------------------------------------------------------------------------------

    //
    public function pengerjaanJawaban(){
        return $this->hasMany(PengerjaanJawaban::class, "PGRJN_ID", "PGRJN_ID");
    }

    // CUSTOM ---------------------------------------------------------------------------------------------------------

    

}
