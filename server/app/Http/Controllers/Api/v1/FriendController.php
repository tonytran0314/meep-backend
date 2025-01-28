<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    use HttpResponses;

    public function search(Request $request) {
        $username = $request->query('username');
        $myself = Auth::user()->username;
        
        $friends = User::where('username', 'LIKE', "%$username%")->where('username', '!=', $myself)->get();

        return $this->success($friends);
    }

    public function add() {

    }

    public function remove(){

    }
}
