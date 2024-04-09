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

        return new AngResource(true,'success create new divisi',$data);
    }
    public function destroy($id){
        $data = Divisi::find($id);
        $data-> delete();
        
        return new AngResource(true,'Deleted', $data);
    }
}
