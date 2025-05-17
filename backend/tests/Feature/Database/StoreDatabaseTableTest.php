<?php

namespace Tests\Feature\Database;

use Tests\DatabaseTableTestCase;

class StoreDatabaseTableTest extends DatabaseTableTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $timestamps = ['created_at', 'updated_at', 'deleted_at'];

        $this->setTables([
            'stores' => [
                'id',
                'name',
                'address',
                'address_2',
                'city',
                'postcode',
                'phone',
                'company_id',
                ...$timestamps,
            ],
        ]);
    }
}
