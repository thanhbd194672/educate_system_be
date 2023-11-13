<?php

namespace App\Http\Controllers\V2\Account;

use App\Http\Controllers\V2\BaseController;
use App\Models\V2\User;
use App\Structs\Struct;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    public ?User $user;

    public function getMe(Request $request): JsonResponse
    {
        if ($this->getCurrentUser($request)) {
            $user_struct = $this->user->struct();
        } else {
            resJson([
                'error' => [
                    'account' => trans('v2/auth.error_username_not_exist')
                ]
            ]);
        }
        return resJson([
            'data' => $user_struct->toArray([
                Struct::OPT_IGNORE => [
                    'status',
                    'password'
                ]
            ])
        ]);

    }
}
