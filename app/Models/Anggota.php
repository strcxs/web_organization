<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class anggota extends Model
{
    use HasFactory;
    protected $table = "data_anggota";
    protected $guarded = ["created_at"];
    // protected $hidden = ["user_id"];

    public function getName(){
        return $this->nama;
    }public function getAvatar(){
        return $this->avatar;
    }public function getNim(){
        return $this->nim;
    }

    public function Divisi(){
        return $this->belongsTo("App\Models\Divisi");
    }
}
