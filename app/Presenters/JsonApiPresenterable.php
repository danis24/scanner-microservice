<?php

namespace App\Presenters;

interface JsonApiPresenterable {

    /**
     * Transform to json api presenter array
     */
    public function transform();

    /**
     * Get entity type
     */
    public function entityType();
}
