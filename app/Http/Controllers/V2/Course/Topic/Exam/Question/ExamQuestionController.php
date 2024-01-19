<?php

namespace App\Http\Controllers\V2\Course\Topic\Exam\Question;

use App\Consts\DateFormat;
use App\Consts\Schema\DBExamQuestionFields;
use App\Consts\Schema\DBQuestionFields;
use App\Http\Controllers\V2\BaseController;
use App\Libs\QueryFields;
use App\Models\V2\Course\Topic\Exam\Question\ExamQuestionModel;
use App\Models\V2\Course\Topic\Exam\Question\QuestionModel;
use App\Models\V2\User;
use App\Structs\Struct;
use App\Structs\V2\ExamQuestionStruct;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExamQuestionController extends BaseController
{
    public function addExamQuestion(Request $request): JsonResponse
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
                'created_at'  => now()->format(DateFormat::TIMESTAMP_DB),
                'id_exam'     => $request->input('id_exam'),
                'id_question' => $request->input('id_question'),
                'mark'        => $request->input('mark'),
            ];
            $exam_question_struct = new ExamQuestionStruct($data);
            $data = normalizeToSQLViaArray($data, DBExamQuestionFields::EXAM_QUESTION);

            if ($data && ExamQuestionModel::doAddExamQuestion($data)) {
                $json = [
                    'data' => $exam_question_struct->toArray(),
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

    public function getExamQuestions(Request $request, string $id_exam): JsonResponse
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
            $fields_exam_question = new QueryFields($request, DBExamQuestionFields::EXAM_QUESTION);
            $user_access = self::getCurrentUser($request)->struct();
//            $required_access_permission = exam_questionPermissionType::ACCESS_INFO;

            $filter_data = [
                'fields'    => $fields_exam_question->select,
                ...pageLimit($request),
                'user_id'   => $user_access->id,
                'sort_by'   => $request->input('sort_by') ?? null,
                'sort'      => $request->input('sort') ?? 'asc',
                'search_by' => $request->input('search_by') ?? null,
                'key'       => "%{$request->input('key')}%" ?? '%%',
                'id_exam'   => $id_exam,
            ];

            if ($query = ExamQuestionModel::doGetExamQuestion($filter_data)) {

                foreach ($query as $exam_question) {
                    $exam_question_struct = $exam_question->struct();
                    $fields_question = new QueryFields($request, DBQuestionFields::QUESTION);
                    $detail  = QuestionModel::doGetById($exam_question_struct->id_question,$fields_question->select);
                    $question = $detail->struct();
//                    if (exam_questionPermissionModel::checkPermission($exam_question_struct, $user_access->id, $required_access_permission)) {
                    $data[] = $exam_question_struct->toArray([
                        Struct::OPT_EXTRA => [
                            'detail' => $question->toArray([
                                Struct::OPT_CHANGE => [
                                    'image' => ['getImage']  // process image by function inside struct
                                ],
                            ])
                        ]
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

    public function getDetailInExamQuestion(Request $request, string $id_exam_question): JsonResponse
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
//            $required_access_permission = exam_questionPermissionType::ACCESS_INFO;

            $filter_data = [
                'fields'           => ["name", "id_exam_question", "id"],
                ...pageLimit($request),
                'user_id'          => $user_access->id,
                'sort_by'          => $request->input('sort_by') ?? null,
                'sort'             => $request->input('sort') ?? 'asc',
                'search_by'        => $request->input('search_by') ?? null,
                'key'              => "%{$request->input('key')}%" ?? '%%',
                'id_exam_question' => $id_exam_question,
            ];


            if ($query = ExamQuestionModel::doGetExamQuestion($filter_data)) {
                foreach ($query as $exam_question) {
                    $exam_question_struct = $exam_question->struct();
//                    if (exam_questionPermissionModel::checkPermission($exam_question_struct, $user_access->id, $required_access_permission)) {
                    $data[] = $exam_question_struct->toArray([
                        Struct::OPT_CHANGE => [
                            'image' => ['getImage']  // process image by function inside struct
                        ],
                    ]);
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
