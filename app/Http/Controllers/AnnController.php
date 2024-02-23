<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Announcement;
use Illuminate\Http\Request;
use App\Http\Resources\AngResource;

class AnnController extends Controller
{
    public function index(){
        $ann = Announcement::orderBy('created_at','desc')->get();
        
        foreach ($ann as $anns) {
            // Mengganti format created_at menjadi waktu yang lebih ramah
            // $forums->formatted_created_at = Carbon::parse($forums->created_at)->diffForHumans();
            $anns->formatted_created_at=Carbon::parse($anns->created_at)->diffForHumans();
        }

        return new AngResource(true,'data forum', $ann);
    }
    public function store(Request $request){
        Announcement::create([
            'user_id'=> $request->user_id,
            'title'=> $request->content
        ]);

        $announcement = Announcement::join('data_anggota','data_anggota.user_id','=','announcement.user_id')
        ->select('data_anggota.avatar','data_anggota.nama','announcement.created_at','announcement.title')
        ->where('announcement.user_id', $request->user_id)
        ->orderBy('created_at','desc')
        ->first();

        $announcement->formatted_created_at=Carbon::parse($announcement->created_at)->diffForHumans();

        return new AngResource(true,"announcement send successfully", $announcement);
    }
    public function destroy($id){
        $data = Announcement::find($id);
        $data-> delete();
        
        return new AngResource(true,'Data berhasil dihapus', $data);
    }
}
