<?php

namespace App\Http\Controllers;

use App\Http\Resources\AngResource;
use App\Models\Connection;
use Illuminate\Http\Request;

class ConnectionController extends Controller
{
    public function index(){
        $data = Connection::with('dataDivisi')
        ->with('dataProgram')
        ->get();

        return new AngResource(true,'data Connection',$data);
    }
}
