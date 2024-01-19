<?php

namespace App\Structs\V2;

use App\Libs\Serializer\Normalize;
use App\Structs\Struct;
use Illuminate\Support\Carbon;

class ExamQuestionStruct extends Struct
{
	public ?float $mark;
	public ?Carbon $created_at;
	public ?Carbon $updated_at;
	public ?string $id_question;
	public ?string $id_exam;
	public function __construct(object|array $data)
	{
		if (is_object($data)) {
			$data = (array)$data;
		}

		$this->mark = Normalize::initFloat($data, 'mark');
		$this->created_at = Normalize::initCarbon($data, 'created_at');
		$this->updated_at = Normalize::initCarbon($data, 'updated_at');
		$this->id_question = Normalize::initString($data, 'id_question');
		$this->id_exam = Normalize::initString($data, 'id_exam');

	}
}