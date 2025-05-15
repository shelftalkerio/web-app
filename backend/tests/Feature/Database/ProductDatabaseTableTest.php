<?php

namespace Tests\Feature\Database;

use Tests\DatabaseTableTestCase;

class ProductDatabaseTableTest extends DatabaseTableTestCase
{

    protected function setUp(): void
    {
        parent::setUp();

        $timestamps = ['created_at', 'updated_at', 'deleted_at'];

        $this->setTables([
            'products' => [
                'id',
                'name',
                'sku',
                'price',
                'stock',
                'synced_at',
                'description',
                'store_id',
                ...$timestamps,
            ],
        ]);
    }
}
