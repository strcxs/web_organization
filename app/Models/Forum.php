<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Forum extends Model
{
    use HasFactory; 

    protected $table = 'forum';
    protected $guarded = [];
    public function dataUsers(){
        return $this->hasOne(Users::class,'id','user_id');
    }
}
