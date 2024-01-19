<?php

namespace App\Structs\V2;

use App\Libs\Serializer\Normalize;
use App\Structs\Struct;
use Illuminate\Support\Carbon;

class ExamStruct extends Struct
{
	public ?string $time_to_do;
	public ?int $status;
	public ?Carbon $updated_at;
	public ?Carbon $created_at;
	public ?int $is_final;
	public ?string $name;
	public ?string $id_topic;
	public ?string $id;
	public function __construct(object|array $data)
	{
		if (is_object($data)) {
			$data = (array)$data;
		}

		$this->time_to_do = Normalize::initString($data, 'time_to_do');
		$this->status = Normalize::initInt($data, 'status');
		$this->updated_at = Normalize::initCarbon($data, 'updated_at');
		$this->created_at = Normalize::initCarbon($data, 'created_at');
		$this->is_final = Normalize::initInt($data, 'is_final');
		$this->name = Normalize::initString($data, 'name');
		$this->id_topic = Normalize::initString($data, 'id_topic');
		$this->id = Normalize::initString($data, 'id');

	}
}