<?php

namespace App\Services\Scanners;

use Illuminate\Contracts\Support\Arrayable;
use Uuid;

class ScannerService
{
    private function newScanner()
    {
        return new Scanner;
    }

    public function browse()
    {
        return $this->newScanner()->paginate();
    }

    public function findScannerByScannerId($id)
    {
        return $this->newScanner()->where("scan_scanid", $id)->first();
    }

    public function updateOrCreate($id = [], $payload = [])
    {
        return $this->newScanner()->updateOrCreate($id, $payload);
    }

    public function read($id)
    {
        return $this->newScanner()->findByUuid($id);
    }

    public function add($payload)
    {
        return $this->newScanner()->create($payload);
    }

    public function update($id, $payload)
    {
        $target = $this->read($id);
        $target->fill($payload)->save();
        return $target;
    }
}
