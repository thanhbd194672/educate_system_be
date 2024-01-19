<?php

namespace App\Consts\Schema;
use App\Consts\DbTypes;

abstract class DBVideoFields
{
	const VIDEO = [
		'video' => [
			'type' => DbTypes::JSON,
			'cache' => true,
		],
		'status' => [
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
		'id_topic' => [
			'type' => DbTypes::STRING,
			'cache' => true,
		],
		'name' => [
			'type' => DbTypes::STRING,
			'cache' => true,
		],
		'id' => [
			'type' => DbTypes::STRING,
			'cache' => true,
		],
	];
}