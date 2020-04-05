<?php

namespace App\Services\Vulns;

use Illuminate\Contracts\Support\Arrayable;
use Uuid;

class VulnService
{
    private function newVuln()
    {
        return new Vuln;
    }

    public function browse()
    {
        return $this->newVuln()->paginate();
    }

    public function findByScannerId($id)
    {
        return $this->newVuln()->where("scan_id", $id)->paginate();
    }

    public function updateOrCreate($id = [], $payload = [])
    {
        return $this->newVuln()->updateOrCreate($id, $payload);
    }

    public function read($id)
    {
        return $this->newVuln()->findByUuid($id);
    }

    public function add($payload)
    {
        return $this->newVuln()->create($payload);
    }

    public function update($id, $payload)
    {
        $target = $this->read($id);
        $target->fill($payload)->save();
        return $target;
    }
}
