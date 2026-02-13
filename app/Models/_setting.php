<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class _setting extends Model
{
    protected $table = "_setting";
    protected $primaryKey = "S_ID";
    public $incrementing = false;
    protected $keyType = "string";
    public $timestamps = false;
    protected $fillable = [
        "S_ID",
        "S_VALUE",
        "S_INFO",
    ];
}
