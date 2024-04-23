<?php

namespace App\Http\Controllers;

use App\Http\Resources\AngResource;
use App\Models\Candidate;
use Illuminate\Http\Request;

class CandidateController extends Controller
{
    public function index(){
        $data = Candidate::with('dataUsers.dataAnggota')
        ->with('dataDetail')
        ->with('dataVote')
        ->get();

        return new AngResource(true,'data candidate',$data);
    }
    public function show($id){
        $data = Candidate::with('dataUsers.dataAnggota')
        ->with('dataDetail')
        ->with('dataVote')
        ->find($id);

        return new AngResource(true,'data candidate',$data);
    }
}
