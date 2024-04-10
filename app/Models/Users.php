<?php

namespace App\Models;

use App\Models\Anggota;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Users extends Model
{
    use HasFactory;

    protected $table = 'users';
    protected $guarded = ['id'];
    protected $hidden = ['password'];
    public function dataAnggota(){
        return $this->belongsTo(Anggota::class,'id','user_id')->select('user_id','nama','nim','tahun_akt','tanggal_lahir','tempat_lahir');
    }
    public function dataDivisi(){
        return $this->belongsTo(divisi::class,'divisi_id','id')->select('id','divisi','leader_id');
    }
    public function dataProgram(){
        return $this->belongsTo(Program::class,'program_id','id')->select('id','program','leader_id');
    }
}
