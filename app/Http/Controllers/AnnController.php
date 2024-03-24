<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Pusher\Pusher;
use App\Models\Announcement;
use Illuminate\Http\Request;
use App\Http\Resources\AngResource;

class AnnController extends Controller
{
    public function index(){
        $ann = Announcement::orderBy('created_at','desc')
        ->join('data_anggota','data_anggota.user_id','=','announcement.user_id')
        ->select('announcement.id','data_anggota.avatar','announcement.user_id','data_anggota.nama','announcement.title','announcement.created_at')
        ->get();
        
        foreach ($ann as $anns) {
            $anns->formatted_created_at=Carbon::parse($anns->created_at)->diffForHumans();
        }

        return new AngResource(true,'data forum', $ann);
    }
    public function store(Request $request){
        Announcement::create([
            'user_id'=> $request->user_id,
            'title'=> $request->content
        ]);

        if ($request->user_id != 64) {
            return new AngResource(false,"only admin", null);
        }

        $announcement = Announcement::join('data_anggota','data_anggota.user_id','=','announcement.user_id')
        ->select('announcement.id','data_anggota.avatar','announcement.user_id','data_anggota.nama','announcement.title','announcement.created_at')
        ->where('announcement.user_id', $request->user_id)
        ->orderBy('created_at','desc')
        ->first();

        $announcement->formatted_created_at=Carbon::parse($announcement->created_at)->diffForHumans();

        $pusher = new Pusher(
            env('PUSHER_APP_KEY','71d8b7362ac9e3875667'),
            env('PUSHER_APP_SECRET','95d19ff4b7689fd7ea49'),
            env('PUSHER_APP_ID','1772919'),
            [
                'cluster' => env('PUSHER_APP_CLUSTER','ap1'),
                'useTLS' => true
            ]
        );
        $pusher->trigger('announcement', 'sent-announcement', ['data' => $announcement]);

        return new AngResource(true,"announcement send successfully", $announcement);
    }
    public function destroy($id){
        $data = Announcement::find($id);
        $data-> delete();
        
        $pusher = new Pusher(
            env('PUSHER_APP_KEY','71d8b7362ac9e3875667'),
            env('PUSHER_APP_SECRET','95d19ff4b7689fd7ea49'),
            env('PUSHER_APP_ID','1772919'),
            [
                'cluster' => env('PUSHER_APP_CLUSTER','ap1'),
                'useTLS' => true
            ]
        );
        $pusher->trigger('announcement', 'delete-announcement', ['data' => $data]);

        return new AngResource(true,'Data berhasil dihapus', $data);
    }
}
