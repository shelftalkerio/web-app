<?php

namespace Tests\Feature\Database;

use Tests\DatabaseTableTestCase;

class UserDatabaseTableTest extends DatabaseTableTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $timestamps = ['created_at', 'updated_at'];

        $this->setTables([
            'users' => [
                'id',
                'name',
                'email',
                'password',
                'company_id',
                'role',
                'email_verified_at',
                'password',
                'approved',
                'remember_token',
                ...$timestamps,
            ],
        ]);
    }
}
