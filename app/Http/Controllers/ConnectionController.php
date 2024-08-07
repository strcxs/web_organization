<?php

namespace App\Http\Controllers;

use App\Models\Connection;
use Illuminate\Http\Request;
use App\Http\Resources\AngResource;
use Illuminate\Support\Facades\Validator;

class ConnectionController extends Controller
{
    public function index(){
        $data = Connection::with('dataDivisi.dataUsers.dataAnggota')
        ->with('dataProgram.dataUsers.dataAnggota')
        ->get();

        return new AngResource(true,'data Connection',$data);
    }
    public function store(Request $request){
        if ($request->divisi_id != null ) {
            Connection::create([
                'divisi_id'=>$request->divisi_id
            ]);
        }
        elseif ($request->program_id != null ){
            Connection::create([
                'program_id'=>$request->program_id
            ]);
        }

        $data = Connection::with('dataDivisi.dataUsers')
        ->with('dataProgram.dataUsers')
        ->orderBy('created_at','desc')
        ->first();
        
        $this->push('connection', 'new-connection', ['data' => $data]);
        return new AngResource(true,'success create Connection',$data);
    }
    public function show($id){
        $data = Connection::with('dataDivisi.dataUsers.dataAnggota')
        ->with('dataProgram.dataUsers.dataAnggota')
        ->where('id',$id)
        ->first();

        return new AngResource(true,'data Connection',$data);
    }
}
