<?php

namespace App\Services\Scanners;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use App\Services\UUIDEntity;
use App\Presenters\JsonApiPresenterable as Presenterable;
use Uuid;

class Scanner extends Model implements AuthenticatableContract, AuthorizableContract, Presenterable
{
    use Authenticatable, Authorizable, UUIDEntity;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        "scan_url",
        "project_id",
        "scan_scanid",
        "vul_status",
        "total_vul",
        "high_vul",
        "medium_vul",
        "low_vul"
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */

    protected $casts = [
        "id" => "uuid",
    ];


    /**
     * @{inheritDoc}
     */
    public function transform()
    {
        $transformed = $this->toArray();
        foreach ($this->getUuidAttributeNames() as $uuidAttributeName) {
            $value = $this->getAttribute($uuidAttributeName);
            $transformed[$uuidAttributeName] = Uuid::import($value)->string;
        }
        return $transformed;
    }


    /**
     * @{inheritDoc}
     */
    public function entityType()
    {
        return "scanners";
    }
}
