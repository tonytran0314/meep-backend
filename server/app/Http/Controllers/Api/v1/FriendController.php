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

    /* -------------------------------------------------------------------------- */
    /*                               Search friends                               */
    /* -------------------------------------------------------------------------- */
    public function search(Request $request) {
        $username = $request->query('username');
        $myself = Auth::user()->username;
        
        $friends = User::where('username', 'LIKE', "%$username%")->where('username', '!=', $myself)->get();

        return $this->success($friends);
    }

    /* -------------------------------------------------------------------------- */
    /*                           Send add friend request                          */
    /* -------------------------------------------------------------------------- */
    public function add() {

    }

    /* -------------------------------------------------------------------------- */
    /*                          Accept add friend request                         */
    /* -------------------------------------------------------------------------- */
    public function accept() {

    }

    /* -------------------------------------------------------------------------- */
    /*                          Reject add friend request                         */
    /* -------------------------------------------------------------------------- */
    public function reject() {

    }

    /* -------------------------------------------------------------------------- */
    /*                    Remove a friend (delete a chat room)                    */
    /* -------------------------------------------------------------------------- */
    public function remove(){

    }
}
