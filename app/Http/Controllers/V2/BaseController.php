<?php

namespace App\Http\Controllers\V2;

use App\Http\Controllers\Controller;
use App\Models\V2\User;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    public ?User $user;

    public  function getCurrentUser(Request $request) :User
    {
        $this->user = $request->user();

        return $this->user;
    }
}
