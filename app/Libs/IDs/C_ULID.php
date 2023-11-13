<?php

namespace App\Libs\IDs;

use DateTimeInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Symfony\Component\Uid\Ulid;

class C_ULID
{
    public static function generate(?DateTimeInterface $time = null): C_ULID
    {
        return new C_ULID(Str::ulid($time));
    }

    public function __construct(
        public Ulid $id
    )
    {
        //
    }

    public function toString(): string
    {
        return Str::lower($this->id->toBase32());
    }

    public function toUuid(): string
    {
        return $this->id->toRfc4122();
    }

    public function getDateTime(): Carbon
    {
        return Carbon::parse($this->id->getDateTime())->setTimezone(config('app.timezone'));
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}