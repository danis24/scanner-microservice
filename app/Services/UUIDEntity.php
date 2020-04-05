<?php

namespace App\Services;

use Uuid;

trait UUIDEntity
{
    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if ($model->hasCast($model->getKeyName(), "uuid")) {
                $model->{$model->getKeyName()} = Uuid::generate(4)->string;
            }
        });
    }

    /**
     * Set a given attribute on the model.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return $this
     */
    public function setAttribute($key, $value)
    {
        parent::setAttribute($key, $value);
        if ($this->hasCast($key, "uuid")) {
            $this->attributes[$key] = Uuid::import($value)->bytes;
        }
        return $this;
    }

    public function findByUuid($id)
    {
        $id = Uuid::import($id)->bytes;
        return $this->find($id);
    }

    public function destroyByUuid($id)
    {
        $id = Uuid::import($id)->bytes;
        return static::destroy($id);
    }


    public function getUuidAttributeNames()
    {
        return array_keys(array_filter($this->casts, function ($value) {
            return $value == "uuid";
        }));
    }
}
