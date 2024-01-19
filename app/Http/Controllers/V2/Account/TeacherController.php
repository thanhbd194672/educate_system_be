<?php

namespace App\Http\Controllers\V2\Account;

use App\Http\Controllers\V2\BaseController;
use App\Models\V2\User;
use App\Structs\Struct;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TeacherController extends BaseController
{
    public function getTeacher(Request $request): JsonResponse
    {
        $rule = [
            ''
        ];
        $message = [

        ];
        $validator = $this->_validate($request, $rule, $message);
        if ($validator->errors()->count()) {
            $json = [
                'error' => firstError($validator->getMessageBag()->toArray())
            ];
        } else {
            $user_access = self::getCurrentUser($request)->struct();

            $filter_data = [
                'fields'    => ["first_name","last_name","avatar","gender"],
                ...pageLimit($request),
                'user_id'   => $user_access->id,
                'sort_by'   => $request->input('sort_by') ?? null,
                'sort'      => $request->input('sort') ?? 'asc',
                'search_by' => $request->input('search_by') ?? null,
                'key'       => "%{$request->input('key')}%" ?? '%%',
            ];

            if ($query = User::doGetTeacher($filter_data)) {
                foreach ($query as $teacher) {
                    $teacher_struct = $teacher->struct();
                    $data[] = $teacher_struct->toArray([
                        Struct::OPT_CHANGE => [
                            'avatar' => ['getImage']  // process image by function inside struct
                        ],
                    ]);
                }
            }
            $json = [
                'items' => $data ?? [''],
                'meta' => ResMetaJson($query),
            ];
        }
        return resJson($json);
    }

    protected function _validate(Request $request, ?array $rule = [], ?array $message = []): \Illuminate\Contracts\Validation\Validator|\Illuminate\Validation\Validator
    {
        $validator = Validator::make($request->all(), $rule, $message);
        if (!$validator->fails()) {
            $this->getCurrentUser($request);

            if ($this->user instanceof User) {
                if (!$this->user->getAttribute('status')) {
                    $validator->errors()->add('username', trans('v1/auth.error_username_status'));
                }
            } else {
                $validator->errors()->add('user', trans('v1/auth.error_username_not_exist'));
            }
        }

        return $validator;
    }
}
