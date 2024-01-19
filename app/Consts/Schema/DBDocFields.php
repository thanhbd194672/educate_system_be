<?php

namespace App\Consts\Schema;
use App\Consts\DbTypes;

abstract class DBDocFields
{
	const DOC = [
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
		'content' => [
			'type' => DbTypes::STRING,
			'cache' => true,
		],
		'id' => [
			'type' => DbTypes::STRING,
			'cache' => true,
		],
		'link_doc' => [
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
	];
}