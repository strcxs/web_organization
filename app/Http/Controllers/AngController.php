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
        $member = $request->input('member',false);
        if (!$member) {
            $datas = Users::with('dataAnggota')
            ->with('dataDivisi.dataConnection')
            ->with('dataProgram.dataConnection')->get();
            
            foreach ($datas as $data) {
                $data->dataAnggota->nama = ucwords(strtolower($data->dataAnggota->nama));
            }
        }else{
            $datas = Anggota::with('dataUsers.dataDivisi')
            ->get();
    
            foreach ($datas as $data) {
                $data->nama = ucwords(strtolower($data->nama));
            }
        }

        if (is_null($datas)){
            return new AngResource(false, 'tidak ada data', $datas);
        }
        return new AngResource(true, 'data anggota', $datas);
    }
    public function show($detail){
        $join = Users::with('dataAnggota')
        ->with('dataDivisi')
        ->with('dataProgram')
        ->where('Users.id',$detail)
        ->first();

        $join->dataAnggota->nama = ucwords(strtolower($join->dataAnggota->nama));

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
        $old_data = Users::find($id);
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
                    $avatar->storeAs('public/images/users-images/', $avatar->hashName());
                    
                    //delete old image
                    Storage::delete('public/images/users-images/'.Users::find($id)->avatar);
                    
                    // update post with new image
                    Users::find($id)->update([
                        "avatar" => $avatar->hashName(),
                        'updated_at' => now(),
                    ]);
                    return new AngResource(true, "data berhasil di ubah", Users::find($id));
                }else{
                    if ($key[$i]=='username' or $key[$i]=='program_id' or $key[$i]=='divisi_id') {
                        if ($new_data[$key[$i]]!=null) {
                            $update_user = Users::find($id);
                            $update_user->update([
                                $key[$i]=> $request->get($key[$i]),
                            ]);
                        }
                    }else{
                        if ($new_data[$key[$i]]!=null) {
                            $update_user = Anggota::select('*')
                            ->where('user_id','=',$id)->first();
                            $update_user->update([
                                $key[$i]=> $request->get($key[$i]),
                            ]);
                        }
                    }
                }
            }
        };
        return new AngResource(true, "data berhasil di ubah", Users::find($id));
    }
    public function destroy($id){
        $data = Users::find($id);

        Storage::delete('storage/images/users-images'.$data->avatar);

        $data-> delete();
        
        return new AngResource(true,'Data berhasil dihapus', $data);
    }
}
