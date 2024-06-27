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
            'gender'=>'required',
            'id'=>'required',
            'tahun_akt'=>'required'
        ]);
        if ($validator->fails()) {
            return new AngResource(false,'failed to add new members',$validator);
        }
        Anggota::create([
            'nama'=> $request->nama,
            'gender'=> $request->gender,
            'id'=> $request->id,
            'tahun_akt'=> $request->tahun_akt,
        ]);
        $data = Anggota::orderBy('created_at','desc')
        ->first();

        return new AngResource(true,'success add new member',$data); 

    }
    public function show($id){
        $data = Anggota::with('dataUsers.dataDivisi')
        ->find($id);

        // $data->nama = ucwords(strtolower($data->nama));

        return new AngResource(true,'data members',$data);
    }
    public function destroy($id){
        $data = Anggota::find($id)->id;
        $image = Users::where('member_id','=',$data)->first();
        if ($image != null) {
            Storage::delete('public/images/users-images/'.$image->avatar);   
        }
        $data = Anggota::find($id);
        $data-> delete();
        
        return new AngResource(true,'success delete a member', $data);
    }
    public function update(Request $request, $id){
        $key = collect($request->all())->keys();
        $data = Anggota::find($id);

        foreach ($key as $keys => $value) {
            if ($request->$value != $data->$value) {
                Anggota::find($id)->update([
                    $value => $request->$value
                ]);
            };
        }
        $data = Anggota::find($id);
        return new AngResource(true,'success update member', $data);
    }
}
