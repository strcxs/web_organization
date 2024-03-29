<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Announcement;
use Illuminate\Http\Request;
use App\Http\Resources\AngResource;
use Illuminate\Support\Facades\Validator;

class AnnController extends Controller
{
    public function index(Request $request){
        $pagination = $request->input('pagination',false);
        if ($pagination) {
            $perpage =$request->input('perPage',10);
            $ann = Announcement::orderBy('created_at','desc')
            ->join('data_anggota','data_anggota.user_id','=','announcement.user_id')
            ->select('announcement.id','data_anggota.avatar','announcement.user_id','data_anggota.nama','announcement.title','announcement.created_at')
            ->paginate($perpage);
        }else{
            $perpage =$request->input('perPage',10);
            $ann = Announcement::orderBy('created_at','desc')
            ->join('data_anggota','data_anggota.user_id','=','announcement.user_id')
            ->select('announcement.id','data_anggota.avatar','announcement.user_id','data_anggota.nama','announcement.title','announcement.created_at')
            ->get();
        }
        
        foreach ($ann as $anns) {
            $anns->formatted_created_at=Carbon::parse($anns->created_at)->diffForHumans();
        }

        return new AngResource(true,'data forum', $ann);
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required', 
            'content' => 'required', 
        ]);
        if ($validator->fails()) {
            return new AngResource(false,"input is missing", null);
        }
        Announcement::create([
            'user_id'=> $request->user_id,
            'title'=> $request->content
        ]);

        if ($request->user_id != 64) {
            return new AngResource(false,"You're not an admin.", null);
        }

        $announcement = Announcement::join('data_anggota','data_anggota.user_id','=','announcement.user_id')
        ->select('announcement.id','data_anggota.avatar','announcement.user_id','data_anggota.nama','announcement.title','announcement.created_at')
        ->where('announcement.user_id', $request->user_id)
        ->orderBy('created_at','desc')
        ->first();

        $announcement->formatted_created_at=Carbon::parse($announcement->created_at)->diffForHumans();

        $this->push('announcement', 'sent-announcement', ['data' => $announcement]);

        return new AngResource(true,"announcement send successfully", $announcement);
    }
    public function destroy($id){
        $data = Announcement::find($id);
        $data-> delete();
        
        $this->push('announcement', 'delete-announcement', ['data' => $data]);

        return new AngResource(true,'Data berhasil dihapus', $data);
    }
}
