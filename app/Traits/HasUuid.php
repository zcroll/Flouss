<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
trait HasUuid
{
    public static function bootHasUuid(): void
    {

        static::creating(static function ( Model $model): void {
            $model->uuid = Uuid::uuid4()->toString();
        });
    }
}
