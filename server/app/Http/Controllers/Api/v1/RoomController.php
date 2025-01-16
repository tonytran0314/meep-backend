<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\v1\RoomResource;

class RoomController extends Controller
{
    use HttpResponses;

    /**
     * List rooms of the current user
     */
    public function index()
    {
        $user = Auth::user();
        return $this->success(RoomResource::collection($user->rooms));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
