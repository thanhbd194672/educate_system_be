<?php

namespace App\Consts\Schema;
use App\Consts\DbTypes;

abstract class DBTopicFields
{
	const TOPIC = [
		'is_free' => [
			'type' => DbTypes::INT,
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
		'status' => [
			'type' => DbTypes::INT,
			'cache' => true,
		],
		'id_course' => [
			'type' => DbTypes::STRING,
			'cache' => true,
		],
		'name' => [
			'type' => DbTypes::STRING,
			'cache' => true,
		],
		'description' => [
			'type' => DbTypes::STRING,
			'cache' => true,
		],
		'id' => [
			'type' => DbTypes::STRING,
			'cache' => true,
		],
	];
}