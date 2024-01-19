<?php

namespace App\Consts\Schema;
use App\Consts\DbTypes;

abstract class DBQuestionFields
{
	const QUESTION = [
		'created_at' => [
			'type' => DbTypes::STRING,
			'cache' => true,
		],
		'correct_answer' => [
			'type' => DbTypes::JSON,
			'cache' => true,
		],
		'status' => [
			'type' => DbTypes::INT,
			'cache' => true,
		],
		'image' => [
			'type' => DbTypes::JSON,
			'cache' => true,
		],
		'answer' => [
			'type' => DbTypes::JSON,
			'cache' => true,
		],
		'updated_at' => [
			'type' => DbTypes::STRING,
			'cache' => true,
		],
		'type' => [
			'type' => DbTypes::STRING,
			'cache' => true,
		],
		'content' => [
			'type' => DbTypes::STRING,
			'cache' => true,
		],
		'id' => [
			'type' => DbTypes::STRING,
			'cache' => true,
		],
		'teacher_id' => [
			'type' => DbTypes::STRING,
			'cache' => true,
		],
	];
}