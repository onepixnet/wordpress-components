<?php

declare(strict_types=1);

namespace OnePix\WordPressComponents;

use OnePix\WordPressContracts\DbTable;

final class DbTableCreator implements \OnePix\WordPressContracts\DbTableCreator
{
    /**
     * @param  callable(non-empty-string):mixed  $dbDelta
     */
    public function __construct(
        private readonly string $charsetCollate,
        private $dbDelta
    ) {
    }

    public function createTable(DbTable $table): void
    {
        $sql = sprintf('CREATE TABLE {%s} (
            %s
        ) %s', $table->getTableName(), $table->getColumnsDefinition(), $this->charsetCollate);

        call_user_func($this->dbDelta, $sql);
    }
}
