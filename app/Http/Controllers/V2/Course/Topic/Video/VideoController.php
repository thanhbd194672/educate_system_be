<?php

namespace App\Http\Controllers\V2\Course\Topic\Video;

use App\Consts\DateFormat;
use App\Consts\Schema\DBVideoFields;
use App\Http\Controllers\V2\BaseController;
use App\Libs\IDs\C_ULID;
use App\Libs\QueryFields;
use App\Models\V2\Course\Topic\Video\VideoModel;
use App\Models\V2\User;
use App\Structs\Struct;
use App\Structs\V2\VideoStruct;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class VideoController extends BaseController
{
    public function addVideo(Request $request): JsonResponse
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
            if ($request->file('video')) {
                $video = doVideo($request->file('video'));
            }

            $data = [
                'id'         => $image['id'] ?? C_ULID::generate()->toString(),
                'created_at' => now()->format(DateFormat::TIMESTAMP_DB),
                'id_topic'   => $request->input('id_topic'),
                'name'       => $request->input('name'),
                'video'      => $video ?? null,
                'status'     => 1,
            ];
            $video_struct = new VideoStruct($data);
            $data = normalizeToSQLViaArray($data, DBVideoFields::VIDEO);

            if ($data && VideoModel::doAddVideo($data)) {
                $json = [
                    'data' => $video_struct->toArray([
                        Struct::OPT_CHANGE => [
                            'video' => ['getVideo']  // process image by function inside struct
                        ],
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

    public function getVideos(Request $request, string $id_topic): JsonResponse
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
            $fields_video = new QueryFields($request, DBVideoFields::VIDEO);
            $user_access = self::getCurrentUser($request)->struct();
//            $required_access_permission = VideoPermissionType::ACCESS_INFO;

            $filter_data = [
                'fields'    => $fields_video->select,
                ...pageLimit($request),
                'user_id'   => $user_access->id,
                'sort_by'   => $request->input('sort_by') ?? null,
                'sort'      => $request->input('sort') ?? 'asc',
                'search_by' => $request->input('search_by') ?? null,
                'key'       => "%{$request->input('key')}%" ?? '%%',
                'id_topic'  => $id_topic,
            ];

            if ($query = VideoModel::doGetVideo($filter_data)) {
                foreach ($query as $video) {
                    $video_struct = $video->struct();
//                    if (VideoPermissionModel::checkPermission($Video_struct, $user_access->id, $required_access_permission)) {
                    $data[] = $video_struct->toArray([
                        Struct::OPT_CHANGE => [
                            'video' => ['getVideo']  // process image by function inside struct
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
    public function getVideo(Request $request,string $id) :JsonResponse{
        $fields_video = new QueryFields($request, DBVideoFields::VIDEO);

        if ($query = VideoModel::doGetById($id,$fields_video->select)) {
            $json = [
                'data' => $query->struct()->toArray([
                    Struct::OPT_CHANGE => [
                        'video' => ['getVideo']  // process image by function inside struct
                    ],
                ])
            ];
        }
        else{
            $json = [
                'code'  => 200, //400,
                'error' => [
                    'id|user_id' => trans('v1/default.error_id_exists')
                ]
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
