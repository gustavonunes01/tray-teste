<?php

namespace App\Models\Traits;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait UuidTrait {

    public static function boot()
    {
        parent::boot();
        static::creating(function ($obj) {
            if( strlen($obj->external_id) == 0)
                $obj->external_id =  (string)Uuid::uuid4();
        });
    }

    protected function externalId(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->getExternalId($value)
        );
    }

    private function getExternalId($value){
        if(!isset($value) || strlen($value) == 0){
            $value =  (string)Uuid::uuid4();
            $this->external_id = $value;
        }
        return $value;
    }


}
