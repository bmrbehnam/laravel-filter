<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BaseResourceCollection extends AnonymousResourceCollection
{
    public $collects;

    public function __construct($resource, $collects)
    {
        $this->collects = $collects;
        parent::__construct($resource, $collects);
    }

    public function paginationInformation($request, $paginated, $default): array
    {
        return [
            'pagination' => [
                'current_page' => $default['meta']['current_page'],
                'per_page' => $default['meta']['per_page'],
                'total' => $default['meta']['total'],
            ],
        ];
    }
}
