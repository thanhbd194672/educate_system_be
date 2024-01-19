<?php

namespace App\Structs\V2;

use App\Libs\Serializer\Normalize;
use App\Structs\Struct;
use Illuminate\Support\Carbon;

class DocStruct extends Struct
{
	public ?int $status;
	public ?Carbon $created_at;
	public ?Carbon $updated_at;
	public ?string $content;
	public ?string $id;
	public ?string $link_doc;
	public ?string $id_topic;
	public ?string $name;
	public function __construct(object|array $data)
	{
		if (is_object($data)) {
			$data = (array)$data;
		}

		$this->status = Normalize::initInt($data, 'status');
		$this->created_at = Normalize::initCarbon($data, 'created_at');
		$this->updated_at = Normalize::initCarbon($data, 'updated_at');
		$this->content = Normalize::initString($data, 'content');
		$this->id = Normalize::initString($data, 'id');
		$this->link_doc = Normalize::initString($data, 'link_doc');
		$this->id_topic = Normalize::initString($data, 'id_topic');
		$this->name = Normalize::initString($data, 'name');

	}
}