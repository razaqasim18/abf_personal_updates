<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class TeamController extends Controller
{
    public function index($id)
    {
        $id = Crypt::decryptString($id);
        $user = User::find($id);
        $team = User::select(DB::raw("*, CONCAT('ABF-', id) AS ABFid"))->where('sponserid', $id)->get();
        return view('user.team.index', [
            "user" => $user,
            "team" => $team,
        ]);
    }
}
