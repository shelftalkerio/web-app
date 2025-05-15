<?php

namespace Tests\Feature\Database;

use Tests\DatabaseTableTestCase;

class ModuleDatabaseTableTest extends DatabaseTableTestCase
{

    protected function setUp(): void
    {
        parent::setUp();

        $timestamps = ['created_at', 'updated_at', 'deleted_at'];

        $this->setTables([
            'modules' => [
                'id',
                'name',
                'description',
                'type',
                'vendor',
                'config',
                'active',
                'status',
                'last_synced_at',
                'store_id',
                ...$timestamps,
            ],
        ]);
    }
}
