<?php

namespace App\Structs\V2;

use App\Libs\Serializer\Normalize;
use App\Models\V2\Image\UseImage;
use App\Structs\Struct;
use Illuminate\Support\Carbon;

class VideoStruct extends Struct
{
	public ?object $video;
	public ?int $status;
	public ?Carbon $created_at;
	public ?Carbon $updated_at;
	public ?string $id_topic;
	public ?string $name;
	public ?string $id;
	public function __construct(object|array $data)
	{
		if (is_object($data)) {
			$data = (array)$data;
		}

		$this->video = Normalize::initObject($data, 'video');
		$this->status = Normalize::initInt($data, 'status');
		$this->created_at = Normalize::initCarbon($data, 'created_at');
		$this->updated_at = Normalize::initCarbon($data, 'updated_at');
		$this->id_topic = Normalize::initString($data, 'id_topic');
		$this->name = Normalize::initString($data, 'name');
		$this->id = Normalize::initString($data, 'id');

	}
    public function getVideo(): ?string
    {
        $video = null;

        if ($this->video) {
            $video = UseImage::getAssetVideo(json_encode($this->video));
        }
        return $video;
    }
}