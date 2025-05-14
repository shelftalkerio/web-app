<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * A helper test case for testing the existence of tables
 * and columns in the database.
 *
 * Checks are performed against a $tables property defined
 * in your own test class.
 *
 * Usage :
 * 1. Extend your test class by DatabaseTablesTestCase
 * 2. Include the setup() method as below in your test class
 * 3. Call the setTables() and setFeaturePrefix() methods in your setup method
 *    to define your tables and columns.
 *
 *    eg. $this->setTables(['my_table_name' => ['id', 'name', 'date']]);
 *        $this->setFeaturePrefix('my_');
 *
 * Once complete the following tests will be run from your class
 *   testTablesExist()
 *   testUnknownTablesDontExist()
 *   testColumnsExistInTables
 *   testUnknownColumnsDontExistInTables
 */
abstract class DatabaseTableTestCase extends TestCase
{
    use RefreshDatabase;

    /**
     * Define the tables and columns expected to be seen in the database
     *
     * eg. ['my_table_name' => ['id', 'name', 'date']]
     */
    protected array $tables = [];

    /**
     * If your feature tables share a prefix enter it here to enable
     * unknown table tests.
     *
     * eg. 'pepe_'
     */
    protected string $tableFeaturePrefix = '';

    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Test the tables specified in $tables exist in the database
     */
    public function test_tables_exist(): void
    {
        $missingTables = $this->getKnownTables()
            ->reject(fn (string $table): bool => Schema::hasTable($table));

        if ($missingTables->count() > 0) {
            $this->fail('Required database tables '.$missingTables->implode(', ').' are missing.');
        }

        $this->assertTrue(true);
    }

    /**
     * Test unknown tables do not exist in the database based on the
     * $tableFeaturePrefix property, if this is empty this test
     * is not performed.
     *
     * If a set of feature tables share a common prefix this can be enabled
     * by setting the $tableFeaturePrefix property.
     */
    public function test_unknown_tables_dont_exist(): void
    {
        if (property_exists($this, 'tableFeaturePrefix') && $this->tableFeaturePrefix !== '') {
            $unknownTables = $this->getTables()
                ->filter(fn (string $table): bool => str_starts_with($table, $this->tableFeaturePrefix))
                ->reject(fn (string $table): bool => $this->getKnownTables()->contains($table));

            if ($unknownTables->count() > 0) {
                $this->fail('unknown '.$this->tableFeaturePrefix.' tables found in the database - '.$unknownTables->implode(', '));
            }
        }

        $this->assertTrue(true);
    }

    /**
     * Test the columns defined in the $tables property exist in the database.
     */
    public function test_columns_exist_in_tables(): void
    {
        $this->getKnownTables()
            ->each(function (string $table) {
                $this->getKnownColumns($table)
                    ->each(function (string $column) use ($table) {
                        if (! Schema::hasColumn($table, $column)) {
                            $this->fail("Required column '{$column}' does not exist in the table '{$table}'.");
                        }
                    });
            });

        $this->assertTrue(true);
    }

    /**
     * Test unknown table columns do not exist in the known tables.
     */
    public function test_unknown_columns_dont_exist_in_tables(): void
    {
        $this->getKnownTables()
            ->each(function (string $table) {
                $this->getTableColumns($table)
                    ->each(function (string $column) use ($table) {
                        if (! $this->getKnownColumns($table)->contains($column)) {
                            $this->fail("Unexpected column '{$column}' found in the table '{$table}'.");
                        }
                    });
            });

        $this->assertTrue(true);
    }

    /**
     * Get the known tables
     *
     * @return Collection<string>
     */
    protected function getKnownTables(): Collection
    {
        return collect(array_keys($this->tables));
    }

    /**
     * Get the known columns for the specified table
     *
     * @return Collection<string>
     */
    protected function getKnownColumns(string $table): Collection
    {
        return collect($this->tables[$table]);
    }

    /**
     * Get all the database tables
     *
     * @return Collection<string>
     */
    protected function getTables(): Collection
    {
        return collect(DB::select('SHOW TABLES'))
            ->map(fn (object $table): array => (array) $table)
            ->map(fn (array $table): string => $table[array_key_first($table)]);
    }

    /**
     * Get the database table column listings for the specified table
     *
     * @return Collection<string>
     */
    protected function getTableColumns(string $table): Collection
    {
        return collect(Schema::getColumnListing($table));
    }

    /**
     * Set the tables array
     */
    public function setTables(array $tables): self
    {
        $this->tables = $tables;

        return $this;
    }

    /**
     * Sets the feature prefix
     */
    public function setFeaturePrefix(string $prefix): self
    {
        $this->tableFeaturePrefix = $prefix;

        return $this;
    }
}
