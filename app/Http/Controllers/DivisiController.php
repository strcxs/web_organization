<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use Illuminate\Http\Request;
use App\Http\Resources\AngResource;
use Illuminate\Support\Facades\Validator;

class DivisiController extends Controller
{
    public function index(){
        $data = Divisi::get();

        return new AngResource(true,'data Divisi',$data);
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'divisi' => 'required', 
            'leader_id' => 'required', 
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        Divisi::create([
            'divisi'=>$request->divisi,
            'leader_id'=>$request->leader_id
        ]);
        $data = Divisi::orderBy('created_at','desc')
        ->first();

        $this->push('divisi', 'new-divisi', ['data' => $data]);
        return new AngResource(true,'success create new divisi',$data);
    }
    public function update(Request $request, $id){
        $update = Divisi::find($id)->update([
            'leader_id'=>$request->leader_id
        ]);

        $this->push('divisi', 'update-divisi', ['data' => $update]);
        return new AngResource(true,'data update',$update);
    }
    public  function show($id){
        $data = Divisi::with('dataUsers.dataAnggota')
        ->find($id);

        $data->dataUsers->dataAnggota->nama = ucwords(strtolower($data->dataUsers->dataAnggota->nama));

        return new AngResource(true,'data divisi',$data);
    }
    public function destroy($id){
        $data = Divisi::find($id);
        $data-> delete();
        
        $this->push('divisi', 'delete-divisi', ['data' => $data]);
        return new AngResource(true,'Deleted', $data);
    }
}
