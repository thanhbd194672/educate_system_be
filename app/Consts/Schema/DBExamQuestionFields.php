<?php

namespace App\Consts\Schema;
use App\Consts\DbTypes;

abstract class DBExamQuestionFields
{
	const EXAM_QUESTION = [
		'mark' => [
			'type' => DbTypes::FLOAT,
			'cache' => true,
		],
		'created_at' => [
			'type' => DbTypes::STRING,
			'cache' => true,
		],
		'updated_at' => [
			'type' => DbTypes::STRING,
			'cache' => true,
		],
		'id_question' => [
			'type' => DbTypes::STRING,
			'cache' => true,
		],
		'id_exam' => [
			'type' => DbTypes::STRING,
			'cache' => true,
		],
	];
}