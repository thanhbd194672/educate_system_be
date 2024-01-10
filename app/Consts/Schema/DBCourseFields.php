<?php

namespace App\Consts\Schema;
use App\Consts\DbTypes;

abstract class DBCourseFields
{
	const COURSE = [
		'status' => [
			'type' => DbTypes::INT,
			'cache' => true,
		],
		'image' => [
			'type' => DbTypes::JSON,
			'cache' => true,
		],
		'time_to_learn' => [
			'type' => DbTypes::STRING,
			'cache' => true,
		],
		'price' => [
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
		'name_course' => [
			'type' => DbTypes::STRING,
			'cache' => true,
		],
		'description' => [
			'type' => DbTypes::STRING,
			'cache' => true,
		],
		'subject' => [
			'type' => DbTypes::STRING,
			'cache' => true,
		],
		'teacher_id' => [
			'type' => DbTypes::STRING,
			'cache' => true,
		],
		'id' => [
			'type' => DbTypes::STRING,
			'cache' => true,
		],
	];
}