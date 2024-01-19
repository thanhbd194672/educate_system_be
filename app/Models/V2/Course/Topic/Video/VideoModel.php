<?php

namespace App\Models\V2\Course\Topic\Video;

use App\Structs\V2\VideoStruct;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class VideoModel extends Model
{
    use HasFactory,HasUlids;

    protected $connection = 'pgsql_main';
    protected $table      = 'main.video';

    public static function doGetVideo(array $filter) : LengthAwarePaginator|Collection
    {
        $query = self::query()
            ->orderBy($filter['sort_by'] ?? 'created_at',$filter['sort'] ?? 'desc')
            ->where(function ($query) use ($filter){
                if($filter['search_by']){
                    $query->where($filter['search_by'] , 'LIKE',$filter['key']);
                }
                $query->where('id_topic', $filter['id_topic']);
                $query->whereNot('status',0);
            });
        if (empty($filter['limit'])) {

            return $query->get($filter['fields']);
        } else {

            return $query->paginate($filter['limit'], $filter['fields'], "{$filter['page']}", $filter['page']);
        }
    }
    public static function doAddVideo(array $data): bool
    {
        return self::query()->insert($data);
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
    public function struct() :VideoStruct
    {
        return new VideoStruct($this->getAttributes());
    }
}
