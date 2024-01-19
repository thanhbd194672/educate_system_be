<?php

namespace App\Models\V2\Course\Topic\Exam\Question;

use App\Structs\V2\QuestionStruct;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class QuestionModel extends Model
{
    use HasFactory,HasUlids;

    protected $connection = 'pgsql_main';
    protected $table      = 'main.question';

    public static function doGetQuestion(array $filter) : LengthAwarePaginator|Collection
    {
        $query = self::query()
            ->orderBy($filter['sort_by'] ?? 'created_at',$filter['sort'] ?? 'desc')
            ->where(function ($query) use ($filter){
                if($filter['search_by']){
                    $query->where($filter['search_by'] , 'LIKE',$filter['key']);
                }
                if(isset($filter['id_topic'] ))
                    $query->where('id_topic', $filter['id_topic']);
                if(isset($filter['teacher_id']))
                    $query->where('teacher_id', $filter['teacher_id']);
                $query->whereNot('status',0);
            });
        if (empty($filter['limit'])) {

            return $query->get($filter['fields']);
        } else {

            return $query->paginate($filter['limit'], $filter['fields'], "{$filter['page']}", $filter['page']);
        }
    }
    public static function doGetById(string $id, array $filter, ?string $ref = null): Model|Builder|null
    {
        return self::query()
            ->where(function ($query) use ($id, $ref) {
                $query->where('id', $id);
                if ($ref) {
                    $query->where('user_id', $ref);
                }
            })
            ->distinct()
            ->first($filter);
    }
    public static function doAddQuestion(array $data): bool
    {
        return self::query()->insert($data);
    }
    public function struct() :QuestionStruct
    {
        return new QuestionStruct($this->getAttributes());
    }
}
