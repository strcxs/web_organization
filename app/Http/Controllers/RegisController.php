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
            'username'     => 'required|unique:users,username',
            're_password'     => 'required',
            'password'     => 'required',
        ]);
        if ($validator->fails()){
            return response()->json($validator->errors(),442);
        }
        $data_user = anggota::select('*')->where('nim',$request->nim)->first();
        if(!is_null($data_user)){
            if ($request->password === $request->re_password){
                $username = Users::select('*')->where('username',$request->username)->first();
                if(is_null($data_user->user_id)){
                    if(is_null($username)){    
                        $hash_pass = md5($request->password);
                        users::create([
                            'username'=> $request->username,
                            'password'=> password_hash($hash_pass, PASSWORD_DEFAULT),
                            'updated_at' => now(),
                            'created_at' => now(),
                        ]);
                        $user_id = Users::select('*')->where('username',$request->username)->first();

                        $data_user->update([
                            'user_id'=> $user_id->id,
                        ]);
                        return new AngResource(true,"berhasil regis silahkan regis ulang", $data_user);
                    }
                    else{
                        return new AngResource(false,"username tidak tersedia", null);
                    }
                }
                else{
                    return new AngResource(false,"anda sudah memiliki akun", null);
                }
            }
            else{
                return new AngResource(false,"password tidak cocok", null);
            };
        }
        else{
            return new AngResource(false,"maaf, anda belum terdaftar silahkan hubungi admin", $data_user);
        }
    }
}
