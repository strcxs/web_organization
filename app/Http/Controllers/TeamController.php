<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use App\Http\Resources\AngResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TeamController extends Controller
{
    public function index(){
        $data = Team::with('dataVote')
        ->with('dataCandidate.dataUsers.dataAnggota')
        ->get();

        return new AngResource(true,'data team',$data);;
    }
    public function show($id){
        $data = Team::with('dataVote')
        ->with('dataCandidate.dataUsers.dataAnggota')
        ->where('id_vote','=',$id)
        ->get();

        return new AngResource(true,'data team',$data);;
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'id_vote' => 'required',
            'name' => 'required', 
            'visi' => 'required',
            'misi' => 'required',
        ]);
        if ($validator->fails()) {
            return new AngResource(false,"input is missing", null);
        }
        if ($request->hasFile('banner')) {
            $validator = Validator::make($request->all(),[
                'banner'     => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            if ($validator->fails()){
                return response()->json($validator->errors(),442);
            }
            //upload image
            $banner = $request->file('banner');
            $banner->storeAs('public/images/banner/', $banner->hashName());
            //delete old image
            // Storage::delete('public/images/users-images/'.Users::find($id)->banner);
            
            // update post with new image
            // Vote::find($id)->update([
            //     "banner" => $banner->hashName(),
            //     'updated_at' => now(),
            // ]);
            // return new AngResource(true, "data berhasil di ubah", Vote::find($id));
        }
        $data = Team::create([
            'id_vote'=> $request->id_vote,
            'name'=> $request->name,
            'visi'=> $request->visi,
            'misi'=> $request->misi,
            'banner_image'=> $banner->hashName(),
        ]);

        return new AngResource(true,"create new team successfully", $data);
    }
    public function updateNih(Request $request, $id){
        $key = collect($request->all())->keys();
        $data = Team::find($id);

        foreach ($key as $value) {
            if ($request->$value != $data->$value) {
                if ($request->hasFile('banner_image')) {
                    $banner = $request->file('banner_image');
                    $banner->storeAs('public/images/banner/', $banner->hashName());
                    Storage::delete('public/images/banner/'.Team::find($id)->banner_image);
                    
                    $data->update([
                        $value => $banner->hashName()
                    ]);
                }elseif($value != 'banner_image'){
                    $data->update([
                        $value => $request->$value
                    ]);
                }
            }
        }

        return new AngResource(true,'success update', $data);
    }
    // public function destroy($id){
    //     $data = Team::where('id_vote','=',$id)->first();

    //     Storage::delete('storage/images/banner/'.$data->banner_image);
    //     $data-> delete();
        
    //     return new AngResource(true,'Data berhasil dihapus', $data);
    // }
}