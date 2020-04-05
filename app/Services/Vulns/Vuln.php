<?php

namespace App\Services\Vulns;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use App\Services\UUIDEntity;
use App\Presenters\JsonApiPresenterable as Presenterable;
use Uuid;

class Vuln extends Model implements AuthenticatableContract, AuthorizableContract, Presenterable
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
        'project_id',
        'scan_id',
        'vuln_id',
        'confidence',
        'wascid',
        'cweid',
        'risk',
        'reference',
        'name',
        'solution',
        'param',
        'evidence',
        'sourceid',
        'pluginId',
        'other',
        'attack',
        'messageId',
        'method',
        'alert',
        'ids',
        'description',
        'req_res',
        'note',
        'rtt',
        'tags',
        'timestamp',
        'responseHeader',
        'requestBody',
        'responseBody',
        'requestHeader',
        'cookieParams',
        'res_type',
        'res_id',
        'date_time',
        'false_positive'
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
        return "vulns";
    }
}
