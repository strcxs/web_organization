<?php

namespace App\Http\Controllers;

use App\Http\Resources\AngResource;
use App\Models\anggota;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function store(Request $request) {
        $validator = Validator::make($request->all(),[
            'username'     => 'required',
            'password'     => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(),442);
        }
        $old_data = Users::select('*')->where('username',$request->username)->first();
        if(is_null($old_data)){
            return new AngResource(false,"username tidak terdaftar", $old_data);
        }
        else{
            $join = Anggota::join('users', 'data_anggota.user_id', '=', 'users.id')
            ->select('data_anggota.*')
            ->where('data_anggota.user_id',$old_data->id)
            ->first();
            $decrypt_pass = $old_data->password;
            if (password_verify(md5($request->password), $decrypt_pass)){
                
                return new AngResource(true,"berhasil login", $join,$old_data->id);
            }
            else{
                return new AngResource(false,"password anda salah", $join);
            }
        }
    }

    public function index(Request $request) {
        $pages = $request->pages;
        return view($pages);
    }
}
