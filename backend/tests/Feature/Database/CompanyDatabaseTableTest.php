<?php

namespace Tests\Feature\Database;

use Tests\DatabaseTableTestCase;

class CompanyDatabaseTableTest extends DatabaseTableTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $timestamps = ['created_at', 'updated_at', 'deleted_at'];

        $this->setTables([
            'companies' => [
                'id',
                'name',
                'user_id',
                'email',
                'website',
                ...$timestamps,
            ],
        ]);
    }
}
