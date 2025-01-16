<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Http\Resources\v1\UserResource;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    use HttpResponses;

    public function show()
    {
        return $this->success(new UserResource(Auth::user()));
    }

    public function update(Request $request, string $id)
    {
        //
    }
}
