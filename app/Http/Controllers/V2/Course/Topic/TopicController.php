<?php

namespace App\Http\Controllers\V2\Course\Topic;

use App\Consts\DateFormat;
use App\Consts\Schema\DBTopicFields;
use App\Http\Controllers\V2\BaseController;
use App\Libs\IDs\C_ULID;
use App\Libs\QueryFields;
use App\Models\V2\Course\Topic\TopicModel;
use App\Models\V2\User;
use App\Structs\Struct;
use App\Structs\V2\TopicStruct;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TopicController extends BaseController
{
    public function addTopic(Request $request): JsonResponse
    {
        $rule = [

        ];

        $message = [

        ];

        $validator = $this->_validate($request, $rule, $message);

        if ($validator->errors()->count()) {
            $json = [
                'error' => firstError($validator->getMessageBag()->toArray())
            ];
        } else {
            $data = [
                'id'          => $image['id'] ?? C_ULID::generate()->toString(),
                'created_at'  => now()->format(DateFormat::TIMESTAMP_DB),
                'description' => $request->input('description'),
                'id_course'   => $request->input('id_course'),
                'name'        => $request->input('name'),
                'is_free'     => $request->input('is_free'),
                'status'      => 1,
            ];
            $topic_struct = new TopicStruct($data);
            $data = normalizeToSQLViaArray($data, DBTopicFields::TOPIC);

            if ($data && TopicModel::doAddTopic($data)) {
                $json = [
                    'data' => $topic_struct->toArray([
//                        Struct::OPT_CHANGE => [
//                            'image' => ['getImage']  // process image by function inside struct
//                        ],
                    ]),
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

    public function getTopics(Request $request,string $id_course): JsonResponse
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
            $fields_diary = new QueryFields($request, DBTopicFields::TOPIC);
            $user_access = self::getCurrentUser($request)->struct();
//            $required_access_permission = DiaryPermissionType::ACCESS_INFO;

            $filter_data = [
                'fields'    => $fields_diary->select,
                ...pageLimit($request),
                'user_id'   => $user_access->id,
                'sort_by'   => $request->input('sort_by') ?? null,
                'sort'      => $request->input('sort') ?? 'asc',
                'search_by' => $request->input('search_by') ?? null,
                'key'       => "%{$request->input('key')}%" ?? '%%',
                'id_course' => $id_course,
            ];

            if ($query = TopicModel::doGetTopic($filter_data)) {
                foreach ($query as $diary) {
                    $diary_struct = $diary->struct();
//                    if (DiaryPermissionModel::checkPermission($diary_struct, $user_access->id, $required_access_permission)) {
                    $data[] = $diary_struct->toArray([
                        Struct::OPT_CHANGE => [
                            'image' => ['getImage']  // process image by function inside struct
                        ],
                    ]);
//                    }
                }
            }
            $json = [
                'items' => $data ?? [''],
                'meta'  => ResMetaJson($query),
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
