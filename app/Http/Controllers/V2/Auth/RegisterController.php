<?php

namespace App\Http\Controllers\V2\Auth;

use App\Consts\DateFormat;
use App\Consts\RoleAccount;
use App\Consts\Schema\DBAccountFields;
use App\Http\Controllers\V2\BaseController;
use App\Models\V2\User;
use App\Structs\Struct;
use App\Structs\V2\AccountStruct;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends BaseController
{
    public ?User $user;

    public function index(Request $request): JsonResponse
    {
        $rule = [
            'username'   => 'required|between:3,100',
            'password'   => 'required|between:6,100',
            'confirm'    => 'required|same:password',
            'first_name' => 'required|between:3,50',
            'last_name'  => 'required|between:3,50',
            'gender'     => 'required|in:male,female,other',
            'email'      => 'required',
            'tel_number' => 'required|between:9,10',
            'role'       => 'required|in:student,teacher',
            'address'    => 'between:4,100',
            'avatar'     => 'image',
        ];

        $message = [

        ];

        $validator = $this->_validate($request, $rule, $message);

        if ($validator->errors()->count()) {
            $json = [
                'error' => firstError($validator->getMessageBag()->toArray())
            ];
        } else {
            if ($request->file('avatar')) {
                $avatar = doImage($request->file('avatar'), 960, 540);
            }
            $data = [
                'id'             => generateUlid(),
                'first_name'     => $request->input('first_name'),
                'last_name'      => $request->input('last_name'),
                'gender'         => $request->input('gender'),
                'username'       => $request->input('username'),
                'email'          => $request->input('email'),
                'address'        => $request->input('address') ?? null,
                'tel_number'     => $request->input('tel_number') ?? null,
                'social_network' => $request->input('social_network') ?? null,
                'role'           => $request->input('role') == 'student' ? RoleAccount::STUDENT : RoleAccount::TEACHER,
                'avatar'         => $avatar ?? null,
                'password'       => Hash::make($request->input('password')),
                'created_at'     => now()->format(DateFormat::TIMESTAMP_DB),
                'status'         => 1,
            ];

            $data = normalizeToSQLViaArray($data, DBAccountFields::ACCOUNTS);

            if ($data && User::addUser($data)) {
                $user_struct = new AccountStruct($data);
                $data_response = $user_struct->toArray([
                    Struct::OPT_CHANGE => [
                        'user_agent' => ['handleDeviceAction'],
                    ],
                    Struct::OPT_IGNORE => [
                        'password'
                    ]
                ]);

                $json = [
                    'data' => $data_response,
                    'code' => 200,
                ];
            } else {
                $json = [
                    'code'  => 200, //400,
                    'error' => [
                        'warning' => trans('v1/default.error_insert'),
                    ]
                ];
            }
        }
        return resJson($json);
    }

    protected function _validate($request, $rule, $message): \Illuminate\Validation\Validator
    {
        $validator = Validator::make($request->all(), $rule, $message);
        if (!$validator->fails()) {

            if (User::checkExist([
                'username' => $request->input('username')
            ])) {
                $validator->errors()->add('account', trans('v1/account.username_exist'));
            }

            if (User::checkExist([
                'email' => $request->input('email')
            ])) {
                $validator->errors()->add('email', trans('v1/account.email_exist'));
            }

        }
        return $validator;
    }
}
