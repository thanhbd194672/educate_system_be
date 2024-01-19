<?php

namespace App\Models\V2;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Consts\AccessTokenState;
use App\Structs\V2\AccountStruct;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasUlids, HasApiTokens, HasFactory, Notifiable;

    protected $connection = "pgsql_main";
    protected $table      = "main.accounts";
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function struct(): AccountStruct
    {
        return new AccountStruct($this->getAttributes());
    }

    public function createNewToken(string $name, array $abilities = ['*'], DateTimeInterface $expiresAt = null): \Laravel\Sanctum\NewAccessToken
    {
        $token = $this->createToken($name, $abilities);

        $token->accessToken->state = AccessTokenState::ACTIVE;
        $token->accessToken->save();

        return $token;
    }
    public static function addUser(array $data): bool
    {
        return self::query()->insertGetId($data);
    }

    public static function checkExist(array $credentials) :bool{
        return self::query()
            ->where(function ($query) use ($credentials) {
                foreach ($credentials as $key => $value) {
                    $query->orWhere($key, $value);
                }
            })
            ->exists();
    }
    public static function getUserByName(string $name, array $filter): Builder|Model|null
    {
        return self::query()
            ->where(function ($query) use ($name) {
                $query
                    ->orWhere('username', $name)
                    ->orWhere('email', $name);
            })
            ->distinct()
            ->first($filter);
    }
    public static function doGetTeacher(array $filter) : LengthAwarePaginator|Collection
    {
        $query = self::query()
            ->orderBy($filter['sort_by'] ?? 'created_at',$filter['sort'] ?? 'desc')
            ->where(function ($query) use ($filter){
                if($filter['search_by']){
                    $query->where($filter['search_by'] , 'LIKE',$filter['key']);
                }
                $query->where('role', 2);
//                $query->whereNot('status',0);
            });
        if (empty($filter['limit'])) {

            return $query->get($filter['fields']);
        } else {

            return $query->paginate($filter['limit'], $filter['fields'], "{$filter['page']}", $filter['page']);
        }
    }
}
