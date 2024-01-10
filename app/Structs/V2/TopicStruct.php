<?php

namespace App\Structs\V2;

use App\Libs\Serializer\Normalize;
use App\Structs\Struct;
use Illuminate\Support\Carbon;

class TopicStruct extends Struct
{
	public ?int $is_free;
	public ?Carbon $created_at;
	public ?Carbon $updated_at;
	public ?int $status;
	public ?string $id_course;
	public ?string $name;
	public ?string $description;
	public ?string $id;
	public function __construct(object|array $data)
	{
		if (is_object($data)) {
			$data = (array)$data;
		}

		$this->is_free = Normalize::initInt($data, 'is_free');
		$this->created_at = Normalize::initCarbon($data, 'created_at');
		$this->updated_at = Normalize::initCarbon($data, 'updated_at');
		$this->status = Normalize::initInt($data, 'status');
		$this->id_course = Normalize::initString($data, 'id_course');
		$this->name = Normalize::initString($data, 'name');
		$this->description = Normalize::initString($data, 'description');
		$this->id = Normalize::initString($data, 'id');

	}
}