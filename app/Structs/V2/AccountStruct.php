<?php

namespace App\Structs\V2;

use App\Consts\RoleAccount;
use App\Libs\Serializer\Normalize;
use App\Structs\Struct;
use Illuminate\Support\Carbon;

class AccountStruct extends Struct
{
    public ?Carbon $updated_at;
    public ?Carbon $email_verified_at;
    public ?object $social_network;
    public ?object $avatar;
    public ?int    $role;
    public ?Carbon $created_at;
    public ?string $email;
    public ?string $tel_number;
    public ?string $remember_token;
    public ?string $password;
    public ?string $id;
    public ?string $username;
    public ?string $first_name;
    public ?string $last_name;
    public ?string $gender;
    public ?string $address;

    public function __construct(object|array $data)
    {
        if (is_object($data)) {
            $data = (array)$data;
        }

        $this->updated_at = Normalize::initCarbon($data, 'updated_at');
        $this->email_verified_at = Normalize::initCarbon($data, 'email_verified_at');
        $this->social_network = Normalize::initObject($data, 'social_network');
        $this->avatar = Normalize::initObject($data, 'avatar');
        $this->role = Normalize::initInt($data, 'role');
        $this->created_at = Normalize::initCarbon($data, 'created_at');
        $this->email = Normalize::initString($data, 'email');
        $this->tel_number = Normalize::initString($data, 'tel_number');
        $this->remember_token = Normalize::initString($data, 'remember_token');
        $this->password = Normalize::initString($data, 'password');
        $this->id = Normalize::initString($data, 'id');
        $this->username = Normalize::initString($data, 'username');
        $this->first_name = Normalize::initString($data, 'first_name');
        $this->last_name = Normalize::initString($data, 'last_name');
        $this->gender = Normalize::initString($data, 'gender');
        $this->address = Normalize::initString($data, 'address');

    }
    public function processRole(): string
    {
        if ($this->role == RoleAccount::STUDENT) {
            return "Student";
        } else {
            return "teacher";
        }
    }
}