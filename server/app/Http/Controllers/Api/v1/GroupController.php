<?php

namespace App\Http\Controllers\Api\v1;

use App\Events\v1\NewGroupChat;
use Exception;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\NewGroupRequest;
use App\Models\Room;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GroupController extends Controller
{

    use HttpResponses;

    /**
     * Store a newly created resource in storage.
     */
    public function store(NewGroupRequest $request) {
        try {
            $groupCreatorId = Auth::user()->id;
            
            $newGroup = $this->createNewGroup($request->name);
            
            // Retrieve memberIds as an array
            $memberIds = $request->input('memberIds', []); 

            // Ensure it's an array before modifying it
            if (!is_array($memberIds)) {
                $memberIds = [];
            }

            // Add group creator to the members
            $memberIds[] = $groupCreatorId;

            $this->addUsersToGroup($newGroup->id, $memberIds);

            // broadcast the new room event for the members
            broadcast(new NewGroupChat($newGroup, $memberIds));

            return $this->success(null);
        } catch (Exception $error) {
            return $this->error(null, 'Failed to create new group', 500);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    private function createNewGroup($groupName) {
        $newRoom = Room::create([
            'avatar' => null,
            'is_group' => true,
            'name' => $groupName
        ]);

        return $newRoom;
    }

    private function addUsersToGroup($roomId, $memberIds) {
        foreach($memberIds as $memberId) {
            DB::table('room_user')->insert([
                'room_id' => $roomId,
                'user_id' => $memberId
            ]);
        }
    }
}
