<?php

namespace App\Http\Controllers\V2\Auth;

use App\Http\Controllers\V2\BaseController;
use App\Models\V2\User;
use App\Structs\Struct;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends BaseController
{
    public ?User $user;

    public function index(Request $request): JsonResponse
    {
        $rule = [
            'username' => 'required|between:3,100',
            'password' => 'required|between:6,100',
        ];
        $message = [
            'username.required' => trans('v2/auth.error_username_required'),
            'password.required' => trans('v2/auth.error_password_required'),
        ];

        $validator = $this->_validate($request, $rule, $message);

        if ($validator->errors()->count()) {
            $json = [
                'error' => firstError($validator->getMessageBag()->toArray())
            ];
        } else {
            $user_struct = $this->user->struct();

            $token = $this->user->createNewToken($request->header('platform', 'unknown'), ['*'], $expiresAt ?? null);

            $access_token = [
                'token'      => $token->plainTextToken,
                'token_type' => 'bearer',
                'expires_at' => $token->accessToken->getAttribute('expires_at'),
                'created_at' => $token->accessToken->getAttribute('created_at'),

            ];
            $json = [
                'data' => [
                    ...$user_struct->toArray([
                        Struct::OPT_IGNORE => [
                            'status',
                            'password'
                        ]
                    ]),
                    'access_token' => $access_token
                ]
            ];

        }
        return resJson($json);
    }

    protected function _validate($request, $rule, $message): \Illuminate\Validation\Validator
    {
        $validator = Validator::make($request->all(), $rule, $message);
        if (!$validator->fails()) {
            $username = $request->input('username');

            $user_info = User::getUserByName($username, ['*']);

            if (!$user_info) {
                $validator->errors()->add('username', trans('v2/auth.error_username_not_exist'));
            } else {
                if (!$user_info->getAttribute('status')) {
                    $validator->errors()->add('username', trans('v2/auth.error_username_status'));
                } elseif (!Hash::check($request->input('password'), $user_info->getAttribute('password'))) {
                    $validator->errors()->add('password', trans('v2/auth.error_password_incorrect'));
                }
                if ($user_info instanceof User) {
                    $this->user = $user_info;
                }
            }
        }
        return $validator;
    }
}
