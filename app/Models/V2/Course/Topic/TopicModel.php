<?php

namespace App\Models\V2\Course\Topic;

use App\Structs\V2\TopicStruct;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class TopicModel extends Model
{
    use HasFactory,HasUlids;

    protected $connection = 'pgsql_main';
    protected $table      = 'main.topic';

    public static function doGetTopic(array $filter) : LengthAwarePaginator|Collection
    {
        $query = self::query()
            ->orderBy($filter['sort_by'] ?? 'created_at',$filter['sort'] ?? 'desc')
            ->where(function ($query) use ($filter){
                if($filter['search_by']){
                    $query->where($filter['search_by'] , 'LIKE',$filter['key']);
                }
                $query->where('id_course', $filter['id_course']);
                $query->whereNot('status',0);
            });
        if (empty($filter['limit'])) {

            return $query->get($filter['fields']);
        } else {

            return $query->paginate($filter['limit'], $filter['fields'], "{$filter['page']}", $filter['page']);
        }
    }
    public static function doAddTopic(array $data): bool
    {
        return self::query()->insert($data);
    }
    public function struct() :TopicStruct
    {
        return new TopicStruct($this->getAttributes());
    }
}
