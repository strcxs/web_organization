<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Users;
use Illuminate\Http\Request;
use App\Http\Resources\AngResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class AngController extends Controller
{
    public function index(Request $request)
    {   
        $datas = Anggota::join('divisi', 'data_anggota.divisi', '=', 'divisi.id')
            ->select('data_anggota.*','divisi.divisi as nama_divisi')
            ->get();

        foreach ($datas as $data) {
            $data->nama = ucwords(strtolower($data->nama));
        }

        if (is_null($datas)){
            return new AngResource(false, 'tidak ada data', $datas);
        }
        return new AngResource(true, 'data anggota', $datas);
    }
    public function store(Request $request)
    {
        $data_user = Anggota::select('*')->where('nim',$request->nim)->first();
        $validator = Validator::make($request->all(),[
            'avatar'     => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'nama'     => 'required',
            'nim'   => 'required',
            'tanggal_lahir'     => 'required',
            'tempat_lahir'   => 'required',
            'tahun_akt'     => 'required',
            'no_telp'   => 'required',
        ]);

        if ($validator->fails()){
            return response()->json($validator->errors(),442);
        }

        if (is_null($data_user)){
            if ($request->hasFile('avatar')){
                $avatar = $request->file('avatar');
                $avatar->storeAs('public/images', $avatar->hashName());
    
                $upload = Anggota::create([
                    "avatar" => $avatar->hashName(),
                    'nama'     => $request->nama,
                    'nim'   => $request->nim,
                    'tanggal_lahir'     => $request->tanggal_lahir,
                    'tempat_lahir'   => $request->tempat_lahir,
                    'tahun_akt'     => $request->tahun_akt,
                    'no_telp'   => $request->no_telp,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]);
                return new AngResource(true, 'data anggota', $upload);
    
            }
            $upload = Anggota::create([
                'nama'     => $request->nama,
                'nim'   => $request->nim,
                'tanggal_lahir'     => $request->tanggal_lahir,
                'tempat_lahir'   => $request->tempat_lahir,
                'tahun_akt'     => $request->tahun_akt,
                'no_telp'   => $request->no_telp,
                'updated_at' => now(),
                'created_at' => now(),
            ]);
    
            // return $upload;
            return new AngResource(true, 'data anggota', $upload);
        }
        return new AngResource(false, 'duplicate NIM', null);
    }
    public function show($detail){
        $join = Anggota::join('users', 'data_anggota.user_id', '=', 'users.id')
        ->join('divisi', 'divisi.id','=','data_anggota.divisi')
        ->select('data_anggota.*','users.username','divisi.divisi as nama_divisi')
        ->where('data_anggota.user_id',$detail)
        ->first();

        if(is_null($join)){
            $join = Anggota::join('users', 'data_anggota.user_id', '=', 'users.id')
            ->select('data_anggota.*')
            ->where('data_anggota.user_id',$detail)
            ->first();
        }
        $join->nama = ucwords(strtolower($join->nama));

        if (is_null($join)){
            return new AngResource(true, 'tidak ada data', null);

        }
        return new AngResource(true, 'data anggota', $join);
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'username'     => 'unique:users,username',
        ]);
        if ($validator->fails()){
            return new AngResource(false, "username tidak tersedia", null);

        }

        $user = Anggota::join('users', 'data_anggota.user_id', '=', 'users.id')
                ->select('data_anggota.id')
                ->where('data_anggota.user_id',$id)
                ->first();
        
        $old_data = Anggota::find($user['id']);
        $new_data = $request->all();
        $key = collect($new_data)->keys();

        for ($i=0;$i<count($key);$i++) { 
            if ($old_data[$key[$i]]!=$new_data[$key[$i]] || $new_data[$key[$i]]!=null){
                if ($request->hasFile('avatar')) {
                    $validator = Validator::make($request->all(),[
                        'avatar'     => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                    ]);
            
                    if ($validator->fails()){
                        return response()->json($validator->errors(),442);
                    }
                    //upload image
                    $avatar = $request->file('avatar');

                    $validator = Validator::make($request->all(),[
                        'avatar'     => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                    ]);
            
                    if ($validator->fails()){
                        return response()->json($validator->errors(),442);
                    }
                    
                    $avatar->storeAs('public/images/users-images/', $avatar->hashName());
                    
                    //delete old image
                    Storage::delete('public/images/users-images/'.$old_data->avatar);
                    
                    // update post with new image
                    $old_data->update([
                        "avatar" => $avatar->hashName(),
                        'updated_at' => now(),
                    ]);
                    return new AngResource(true, "data berhasil di ubah", $old_data);
                }
                else{
                    if ($key[$i]=='username') {
                        if ($new_data['username']!=null) {
                            $update_user = Users::find($id);
                            $update_user->update([
                                'username'=> $request->get('username'),
                            ]);
                        }
                    }
                    if ($key[$i]=='avatar') {
                        if ($new_data['avatar']=='delete') {
                            Storage::delete('public/images/users-images/'.$old_data->avatar);
                            $old_data->update([
                                'avatar'    => null,
                                'updated_at' => now(),
                            ]);
                        }
                    }
                    if ($new_data[$key[$i]]!=null) {
                        if ($key[$i]!='avatar') {
                            $old_data->update([
                                $key[$i]    => $request->get($key[$i]),
                                'updated_at' => now(),
                            ]);
                        }
                    }
                    
                }
            }
        };
        return new AngResource(true, "data berhasil di ubah", $old_data);
    }
    public function destroy($id){
        $data = Anggota::find($id);

        Storage::delete('storage/images/users-images'.$data->avatar);

        $data-> delete();
        
        return new AngResource(true,'Data berhasil dihapus', $data);
    }
}
