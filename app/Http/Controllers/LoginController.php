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
        $username = Users::where('username','=',$request->username)->first();
        $member_id = Users::where('member_id','=',$request->username)->first();

        if ($username!=null) {
            $decrypt_pass = $username->password;
            if (password_verify(md5($request->password), $decrypt_pass)){
                return new AngResource(true, 'login success',$username, $username->id,$username->role_id);
            }
            return new AngResource(false, 'Invalid password',null,null);
        }
        if ($member_id!=null) {
            $decrypt_pass = $member_id->password;
            if (password_verify(md5($request->password), $decrypt_pass)){
                return new AngResource(true, 'login success',$member_id, $member_id->id,$member_id->role_id);
            }
            return new AngResource(false, 'Invalid password',null,null);
        }

        return new AngResource(false, 'Username or your ID is not found',null,null);
    }

    public function index(Request $request) {
        $pages = $request->pages;
        return view($pages);
    }
}
