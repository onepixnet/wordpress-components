<?php

declare(strict_types=1);

namespace OnePix\WordPressComponents;

use OnePix\WordPressContracts\DbTable;
use WP_UnitTestCase;

class DbTableCreatorTest extends WP_UnitTestCase
{
    public static function setUpBeforeClass(): void
    {
        if (! function_exists('dbDelta')) {
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        }
    }

    public function testCreateTableWithRightUsageOfDbTable(): void
    {
        $dbDeltaMock    = fn(string $sql): string => $sql;
        $dbTableMock    = $this->createMock(DbTable::class);
        $dbTableCreator = new DbTableCreator('CHARSET_COLLATE', $dbDeltaMock(...));

        $dbTableMock
            ->expects($this->once())
            ->method('getTableName')
            ->willReturn('test_table');

        $dbTableMock
            ->expects($this->once())
            ->method('getColumnsDefinition')
            ->willReturn('id INT, name VARCHAR(255)');

        $dbTableCreator->createTable($dbTableMock);
    }

    public function testCreateTableTableCreated(): void
    {
        global $wpdb;

        $dbTableMock    = $this->createMock(DbTable::class);
        $dbTableCreator = new DbTableCreator($wpdb->get_charset_collate(), dbDelta(...));

        $dbTableMock
            ->method('getTableName')
            ->willReturn('test_table');

        $dbTableMock
            ->method('getColumnsDefinition')
            ->willReturn('id INT, name VARCHAR(255)');

        $dbTableCreator->createTable($dbTableMock);

        $this->assertTrue(
            $wpdb->get_var(sprintf("SHOW TABLES LIKE '%s'", $dbTableMock->getTableName())) === $dbTableMock->getTableName()
        );

        $wpdb->get_var(sprintf('DROP TABLE %s', $dbTableMock->getTableName()));
    }
}
