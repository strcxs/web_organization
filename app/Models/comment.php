<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class comment extends Model
{
    use HasFactory;

    protected $table = 'comment';
    protected $guarded = [];
    public function dataUsers(){
        return $this->hasOne(Users::class,'id','user_id');
    }
}
