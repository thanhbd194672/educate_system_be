<?php

namespace App\Models\V2\Course;

use App\Structs\V2\CourseStruct;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CourseModel extends Model
{
    use HasFactory, HasUlids;

    protected $connection = 'pgsql_main';
    protected $table      = 'main.course';

    public static function doGetCourse(array $filter) : LengthAwarePaginator|Collection
    {
        $query = self::query()
            ->orderBy($filter['sort_by'] ?? 'created_at',$filter['sort'] ?? 'desc')
            ->where(function ($query) use ($filter){
                if($filter['search_by']){
                    $query->where($filter['search_by'] , 'LIKE',$filter['key']);
                }
//                $query->where('user_id', $filter['user_id']);
                $query->whereNot('status',0);
            });
        if (empty($filter['limit'])) {

            return $query->get($filter['fields']);
        } else {

            return $query->paginate($filter['limit'], $filter['fields'], "{$filter['page']}", $filter['page']);
        }
    }
    public static function doAddCourse(array $data): bool
    {
        return self::query()->insert($data);
    }

    public static function doEitCourse(array $data, CourseModel $course) : bool
    {
        $course->forceFill($data);

        return $course->save();
    }

    public static function doDeleteCourse(CourseModel $course) :bool
    {
        $course->forceFill(['status' => 0]);

        return $course->save();
    }

    public function struct() :CourseStruct
    {
        return new CourseStruct($this->getAttributes());
    }

}
