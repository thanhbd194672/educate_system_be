<?php

namespace App\Consts\Schema;
use App\Consts\DbTypes;

abstract class DBExamFields
{
	const EXAM = [
		'time_to_do' => [
			'type' => DbTypes::STRING,
			'cache' => true,
		],
		'status' => [
			'type' => DbTypes::INT,
			'cache' => true,
		],
		'updated_at' => [
			'type' => DbTypes::STRING,
			'cache' => true,
		],
		'created_at' => [
			'type' => DbTypes::STRING,
			'cache' => true,
		],
		'is_final' => [
			'type' => DbTypes::INT,
			'cache' => true,
		],
		'name' => [
			'type' => DbTypes::STRING,
			'cache' => true,
		],
		'id_topic' => [
			'type' => DbTypes::STRING,
			'cache' => true,
		],
		'id' => [
			'type' => DbTypes::STRING,
			'cache' => true,
		],
	];
}