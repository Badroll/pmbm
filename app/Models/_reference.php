<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class _reference extends Model
{
    protected $table = "_reference";
    protected $primaryKey = "R_ID";
    public $incrementing = false;
    protected $keyType = "string";
    public $timestamps = false;
    protected $fillable = [
        "R_CATEGORY",
        "R_ID",
        "R_INFO",
        "R_ORDER",
    ];

    public function getByCategoryLike($category, $like){
        return self::where("R_CATEGORY", $category)
            ->where("R_ID", "LIKE", "{$like}%")
            ->orderBy("R_ORDER", "ASC")
            ->get();
    }
}
