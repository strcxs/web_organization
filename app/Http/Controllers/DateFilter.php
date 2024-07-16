<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Users;
use Carbon\Carbon;
use App\Models\Forum;
use Illuminate\Http\Request;
use App\Http\Resources\AngResource;

class DateFilter extends Controller
{
    public function index(Request $request){
        if ($request->filter == "dateFilter") {
            $forums = Forum::get();
            $comments = Comment::get();
    
            foreach ($forums as $forum) {
                $forum->bulan = Carbon::parse($forum->created_at)->format('F Y');
            }
            foreach ($comments as $comment) {
                $comment->bulan = Carbon::parse($comment->created_at)->format('F Y');
            }
    
            return new AngResource(true,'data forum', ['forums' => $forums, 'comments' => $comments]);
        }else if ($request->filter == "memberFilter") {
            $users = Users::with(['dataAnggota'])
            ->get();

            $forums = Forum::with('dataUsers.dataAnggota')
            ->get();
            $comments = Comment::with('dataUsers.dataAnggota')
            ->get();

            $countForum = [];
            $countComment = [];
            foreach ($forums as $forum) {
                if(!isset($countForum[$forum->dataUsers->username])){
                    $countForum[$forum->dataUsers->username] = 1; 
                } else {
                    $countForum[$forum->dataUsers->username] += 1; 
                }
            }
            foreach ($comments as $comment) {
                if(!isset($countComment[$comment->dataUsers->username])){
                    $countComment[$comment->dataUsers->username] = 1; 
                } else {
                    $countComment[$comment->dataUsers->username] += 1; 
                }
            }
            foreach ($users as $user) {
                foreach ($countForum as $username => $count) {
                    if ($user->username == $username) {
                        $user->countForum = $count;
                    }
                }
                foreach ($countComment as $username => $count) {
                    if ($user->username == $username) {
                        $user->countComment = $count;
                    }
                }
            }

            return new AngResource(true,'data forum', $users);
        }
    }
}
