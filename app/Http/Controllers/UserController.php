<?php

namespace App\Http\Controllers;

use App\Http\Resources\AngResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(){
        $users = User::pluck("username");
        return new AngResource(true,"data username", $users);
    }
}
