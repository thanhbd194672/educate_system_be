<?php

namespace App\Structs\V2;

use App\Libs\Serializer\Normalize;
use App\Models\V2\Image\UseImage;
use App\Structs\Struct;
use Illuminate\Support\Carbon;

class CourseStruct extends Struct
{
    public ?int    $status;
    public ?object $image;
    public ?string $time_to_learn;
    public ?float  $price;
    public ?Carbon $created_at;
    public ?Carbon $updated_at;
    public ?string $name_course;
    public ?string $description;
    public ?string $subject;
    public ?string $teacher_id;
    public ?string $id;

    public function __construct(object|array $data)
    {
        if (is_object($data)) {
            $data = (array)$data;
        }

        $this->status = Normalize::initInt($data, 'status');
        $this->image = Normalize::initObject($data, 'image');
        $this->time_to_learn = Normalize::initString($data, 'time_to_learn');
        $this->price = Normalize::initFloat($data, 'price');
        $this->created_at = Normalize::initCarbon($data, 'created_at');
        $this->updated_at = Normalize::initCarbon($data, 'updated_at');
        $this->name_course = Normalize::initString($data, 'name_course');
        $this->description = Normalize::initString($data, 'description');
        $this->subject = Normalize::initString($data, 'subject');
        $this->teacher_id = Normalize::initString($data, 'teacher_id');
        $this->id = Normalize::initString($data, 'id');

    }

    public function getImage(): ?string
    {
        $image = null;
        if ($this->image) {
            $image = UseImage::getAsset(json_encode($this->image), 960, 540);
        }
        return $image;
    }
}