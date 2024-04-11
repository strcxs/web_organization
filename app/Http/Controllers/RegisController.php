<?php

namespace App\Http\Controllers;

use App\Http\Resources\AngResource;
use App\Models\anggota;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisController extends Controller
{
    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'nim'     => 'required',
            'username'     => 'required',
            're_password'     => 'required',
            'password'     => 'required',
        ]);
        if ($validator->fails()){
            return response()->json($validator->errors(),442);
        }
        $check_id = Anggota::where('id','=',$request->nim)->first();
        $available_id = Users::where('member_id','=',$request->nim)->first();
        $samePassword = $request->password === $request->re_password;
        $available_username = Users::where('username','=',$request->username)->first();

        if ($check_id == null) {
            return new AngResource(false,"sorry, your ID is not Available", $check_id);
        }
        if (!$samePassword) {
            return new AngResource(false,"password do not match", $samePassword);
        }
        if ($available_id != null) {
            return new AngResource(false,"your ID already exist", $available_id);
        }
        if ($available_username != null) {
            return new AngResource(false,"username already exist", $available_id);
        }
        $hash_pass = md5($request->password);
        $create_user = users::create([
            'member_id'=>$request->nim,
            'role_id'=> 2,
            'divisi_id'=> 1,
            'program_id'=> 1,
            'username'=> $request->username,
            'password'=> password_hash($hash_pass, PASSWORD_DEFAULT),
            'updated_at' => now(),
            'created_at' => now(),
        ]);
        return new AngResource(true,"registration success!", $create_user);
    }
}
