<?php

namespace App\Http\Controllers\V2\Question;

use App\Consts\DateFormat;
use App\Consts\Schema\DBQuestionFields;
use App\Http\Controllers\V2\BaseController;
use App\Libs\IDs\C_ULID;
use App\Libs\QueryFields;
use App\Models\V2\Course\Topic\Doc\DocModel;
use App\Models\V2\Course\Topic\Exam\ExamModel;
use App\Models\V2\Course\Topic\Exam\Question\QuestionModel;
use App\Models\V2\Course\Topic\Video\VideoModel;
use App\Models\V2\User;
use App\Structs\Struct;
use App\Structs\V2\QuestionStruct;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuestionController extends BaseController
{
    public function addQuestion(Request $request): JsonResponse
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
            $image = [];
            if ($files = $request->file('image')) {
                foreach ($files as $file) {
                    $image[] = doImage($file, 960, 540);
                }

            }
            $data = [
                'id'             => $image['id'] ?? C_ULID::generate()->toString(),
                'created_at'     => now()->format(DateFormat::TIMESTAMP_DB),
                'content'        => $request->input('content'),
                'answer'         => $request->input('answer'),
                'correct_answer' => $request->input('correct_answer'),
                'type'           => $request->input('type'),
                'teacher_id'     => $request->input('teacher_id'),
                'image'          => json_encode($image),
                'status'         => 1,
            ];

            $question_struct = new QuestionStruct($data);
            $data = normalizeToSQLViaArray($data, DBQuestionFields::QUESTION);

            if ($data && QuestionModel::doAddQuestion($data)) {
                $json = [
                    'data' => $question_struct->toArray([
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

    public function getQuestions(Request $request, string $teacher_id): JsonResponse
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
            $fields_question = new QueryFields($request, DBQuestionFields::QUESTION);
            $user_access = self::getCurrentUser($request)->struct();
//            $required_access_permission = topicPermissionType::ACCESS_INFO;

            $filter_data = [
                'fields'    => $fields_question->select,
                ...pageLimit($request),
                'user_id'   => $user_access->id,
                'sort_by'   => $request->input('sort_by') ?? null,
                'sort'      => $request->input('sort') ?? 'asc',
                'search_by' => $request->input('search_by') ?? null,
                'key'       => "%{$request->input('key')}%" ?? '%%',
                'teacher_id' => $teacher_id,
            ];

            if ($query = QuestionModel::doGetQuestion($filter_data)) {
                foreach ($query as $question) {
                    $question_struct = $question->struct();
//                    if (questionPermissionModel::checkPermission($question_struct, $user_access->id, $required_access_permission)) {
                    $data[] = $question_struct->toArray([
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
