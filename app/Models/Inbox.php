<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inbox extends Model
{
    protected $table = "inbox";
    protected $primaryKey = "INBOX_ID";
    public $timestamps = false;
    protected $guarded = [];
    protected $hidden = [
    ];


    // RELATION ------------------------------------------------------------------------------------------------------

    //
    public function user(){
        return $this->hasOne(User::class, "U_ID", "U_ID");
    }

    // CUSTOM ---------------------------------------------------------------------------------------------------------

    

}
