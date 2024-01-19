<?php

namespace App\Structs\V2;

use App\Libs\Serializer\Normalize;
use App\Models\V2\Image\UseImage;
use App\Structs\Struct;
use Illuminate\Support\Carbon;

class QuestionStruct extends Struct
{
	public ?Carbon $created_at;
	public ?object $correct_answer;
	public ?int $status;
	public ?object $image;
	public ?object $answer;
	public ?Carbon $updated_at;
	public ?string $type;
	public ?string $content;
	public ?string $id;
	public ?string $teacher_id;
	public function __construct(object|array $data)
	{
		if (is_object($data)) {
			$data = (array)$data;
		}

		$this->created_at = Normalize::initCarbon($data, 'created_at');
		$this->correct_answer = Normalize::initObject($data, 'correct_answer');
		$this->status = Normalize::initInt($data, 'status');
		$this->image = Normalize::initObject($data, 'image');
		$this->answer = Normalize::initObject($data, 'answer');
		$this->updated_at = Normalize::initCarbon($data, 'updated_at');
		$this->type = Normalize::initString($data, 'type');
		$this->content = Normalize::initString($data, 'content');
		$this->id = Normalize::initString($data, 'id');
		$this->teacher_id = Normalize::initString($data, 'teacher_id');

	}
    public function getImage(): ?array
    {
        $data_image = [];
        if ($this->image) {
            foreach ($this->image as $image) {
                $data_image[] =  UseImage::getAsset(json_encode($image), 960, 540);
            }
            return $data_image;
        }
        return null;
    }
}