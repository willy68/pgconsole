<?php

namespace Application\Console;

use Psr\Container\ContainerInterface;

trait DatabaseCommandTrait
{

    /**
     * Undocumented function
     *
     * @param string $query
     * @return \PDOStatement|bool
     */
    protected function getTables(string $query)
    {
        $tables = $this->dao->query($query);
        return $tables;
    }
  
    /**
     * Undocumented function
     *
     * @param string $query
     * @return \PDOStatement|bool
     */
    protected function getColumns(string $query)
    {
        $columns = $this->dao->query($query);
        return $columns;
    }
  
    /**
     * Undocumented function
     *
     * @param string $db
     * @param string $table
     * @return string
     */
    protected function getColumnsQuery(string $db, string $table): string
    {
        return "SELECT COLUMN_NAME
      FROM INFORMATION_SCHEMA.COLUMNS
      WHERE TABLE_SCHEMA = '{$db}' AND TABLE_NAME = '{$table}'
      AND COLUMN_NAME NOT IN ('id','created_at','updated_at','password')";
    }

    /**
     * 
     *
     * @param string $db
     * @param string $table
     * @return string
     */
    protected function getForeignKeyQuery(string $db, string $table): string
    {
        return "SELECT i.TABLE_NAME, k.REFERENCED_TABLE_NAME, k.REFERENCED_COLUMN_NAME 
        FROM information_schema.TABLE_CONSTRAINTS i 
        LEFT JOIN information_schema.KEY_COLUMN_USAGE k
        ON i.CONSTRAINT_NAME = k.CONSTRAINT_NAME 
        WHERE i.CONSTRAINT_TYPE = 'FOREIGN KEY' 
        AND i.TABLE_SCHEMA = '{$db}'
        AND i.TABLE_NAME = '{$table}'";
    }

    /**
     * 
     *
     * @param string $db
     * @param string $table
     * @return string
     */
    protected function getChildRelationQuery(string $db, string $table): string
    {
        return "SELECT 
        TABLE_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME
      FROM
        INFORMATION_SCHEMA.KEY_COLUMN_USAGE
      WHERE
        REFERENCED_TABLE_SCHEMA = '{$db}' AND
        REFERENCED_TABLE_NAME = '{$table}'";
    }

    /**
     *
     *
     * @param string $sql
     * @return array
     */
    protected function getColumnsArray(string $sql): array
    {
        $columns = $this->getColumns($sql);
        $cols = [];
        while ($column = $columns->fetch(\PDO::FETCH_ASSOC)) {
            $cols[] = $column['COLUMN_NAME'];
        }
        return $cols;
    }

    /**
     * get unnamed columns
     *
     * @param string $sql
     * @return array
     */
    protected function getFetchColumns(string $sql): array
    {
        $columns = $this->getColumns($sql);
        $cols = [];
        while ($column = $columns->fetch(\PDO::FETCH_ASSOC)) {
            $cols[] = $column;
        }
        return $cols;
    }

    /**
     * 
     *
     * @param \Psr\Container\ContainerInterface $c
     * @return \PDO
     */
    protected function getDao(ContainerInterface $c): \PDO
    {
        if (!$this->dao) {
            $this->dao = $c->get(\PDO::class);
        }
        return $this->dao;
    }
}
