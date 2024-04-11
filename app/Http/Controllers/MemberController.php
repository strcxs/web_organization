<?php

namespace App\Http\Controllers;

use App\Models\Users;
use App\Models\Anggota;
use Illuminate\Http\Request;
use App\Http\Resources\AngResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MemberController extends Controller
{
    public function index(){
        $data = Anggota::get();

        return new AngResource(true,'data members',$data);
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'nama'=>'required',
            'nim'=>'required',
            'tahun_akt'=>'required'
        ]);
        if ($validator->fails()) {
            return new AngResource(false,'failed to add new members',$validator);
        }
        Anggota::create([
            'nama'=> $request->nama,
            'nim'=> $request->nim,
            'tahun_akt'=> $request->tahun_akt,
        ]);
        $data = Anggota::orderBy('created_at','desc')
        ->first();

        return new AngResource(true,'success add new member',$data); 
    }
    public function destroy($id){
        $image = Anggota::find($id)->user_id;
        Storage::delete('public/images/users-images/'.Users::find($image)->avatar);
        
        $data = Anggota::find($id);
        $data-> delete();
        
        return new AngResource(true,'success delete a member', $data);
    }
}
