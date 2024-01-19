<?php

namespace App\Consts\Schema;
use App\Consts\DbTypes;

abstract class DBAccountFields
{
	const ACCOUNTS = [
		'status' => [
			'type' => DbTypes::INT,
			'cache' => true,
		],
		'email_verified_at' => [
			'type' => DbTypes::STRING,
			'cache' => true,
		],
		'social_network' => [
			'type' => DbTypes::STRING,
			'cache' => true,
		],
		'avatar' => [
			'type' => DbTypes::JSON,
			'cache' => true,
		],
		'role' => [
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
		'tel_number' => [
			'type' => DbTypes::STRING,
			'cache' => true,
		],
		'remember_token' => [
			'type' => DbTypes::STRING,
			'cache' => true,
		],
		'password' => [
			'type' => DbTypes::STRING,
			'cache' => true,
		],
		'id' => [
			'type' => DbTypes::STRING,
			'cache' => true,
		],
		'username' => [
			'type' => DbTypes::STRING,
			'cache' => true,
		],
		'first_name' => [
			'type' => DbTypes::STRING,
			'cache' => true,
		],
		'last_name' => [
			'type' => DbTypes::STRING,
			'cache' => true,
		],
		'gender' => [
			'type' => DbTypes::STRING,
			'cache' => true,
		],
		'address' => [
			'type' => DbTypes::STRING,
			'cache' => true,
		],
		'email' => [
			'type' => DbTypes::STRING,
			'cache' => true,
		],
	];
}