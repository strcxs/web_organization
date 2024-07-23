<?php

namespace App\Models;
use App\Models\Divisi;
use App\Models\Program;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Connection extends Model
{
    use HasFactory;
    protected $table = 'connection';
    protected $guarded = [];
    public function dataDivisi(){
        // return $this->hasOne(divisi::class,'id','divisi_id');
        return $this->hasOne(divisi::class,'id','divisi_id');
    }
    public function dataProgram(){
        return $this->hasOne(Program::class,'id','program_id');
    }
}
