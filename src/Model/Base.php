<?php

namespace MicroPHP\Model;

use MicroPHP\Library\Configure;
use MicroPHP\Model\MySQL;
use MicroPHP\Model\SQL\SQL;
use PDO;

class Base
{
    const LEFT = 1;
    const RIGHT = 2;
    const INNER = 3;
    const CROSS = 4;
    const FULL = 5;
    protected $handle;
    protected $driver;
    protected $fields = ['*'];
    protected $order = [];
    protected $limit = [];
    protected $where = [];
    protected $join = [];
    protected $on = [];
    protected $config;
    protected $table;
    public function __construct()
    {
        $this->driver ??= Configure::read('PDO.driver') ?? 'MySQL';
        $this->config = Configure::read($this->driver);
        $this->driver = 'MicroPHP\\Model\\' . $this->driver;
        $this->handle = new PDO($this->driver::dsn($this->config), $this->config['username'] ?? null, $this->config['password'] ?? null);
        if (empty($this->table)) {
            $this->table = explode('\\', strtolower(preg_replace('/[A-Z]/', '_\\0', get_called_class())));
            $this->table = trim(array_pop($this->table), '_');
        }
    }

    public function prepare(string $sql)
    {
        return $this->handle->prepare($sql);
    }

    public function insert($data)
    {
        $sql = "INSERT INTO {$this->table} (`" . implode("`,`", array_keys($data)) . "`) VALUES (" . implode(', ', array_fill(0, count($data), '?')) . ");";
        $sth = $this->prepare($sql);
        $sth->execute(array_values($data));
        return $this->handle->lastInsertId();
    }

    public function update(
        $distinct = false,
        $update = [],
        $where = [],
        $groupBy = [],
        $having = [],
        $orderBy = [],
        $limit = 0,
        $offset = 0
    ) {
        return $this->query(
            distinct: $distinct,
            columns: $update,
            where: $where,
            groupBy: $groupBy,
            having: $having,
            orderBy: $orderBy,
            limit: $limit,
            offset: $offset,
            type: 'update'
        )->rowCount();
    }

    public function delete(
        $where = [],
        $groupBy = [],
        $having = [],
        $orderBy = [],
        $limit = 0,
        $offset = 0
    ) {
        return $this->query(
            where: $where,
            groupBy: $groupBy,
            having: $having,
            orderBy: $orderBy,
            limit: $limit,
            offset: $offset,
            type: 'delete'
        )->rowCount();
    }

    public function get(
        $distinct = false,
        $columns = ['*'],
        $where = '',
        $groupBy = [],
        $having = [],
        $orderBy = []
    ) {
        return $this->query(
            distinct: $distinct,
            columns: $columns,
            where: $where,
            groupBy: $groupBy,
            having: $having,
            orderBy: $orderBy,
            limit: 0,
            offset: 1
        )->fetchAll(PDO::FETCH_ASSOC)[0] ?? null;
    }

    public function gets(
        $distinct = false,
        $columns = ['*'],
        $where = '',
        $groupBy = [],
        $having = [],
        $orderBy = [],
        $limit = 0,
        $offset = 0
    ) {
        return $this->query(
            distinct: $distinct,
            columns: $columns,
            where: $where,
            groupBy: $groupBy,
            having: $having,
            orderBy: $orderBy,
            limit: $limit,
            offset: $offset
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    public function query(
        $distinct = false,
        $columns = ['*'],
        $where = '',
        $groupBy = [],
        $having = [],
        $orderBy = [],
        $limit = 0,
        $offset = 0,
        $type = 'select',
    ) {
        $sql = new SQL(
            $this->table,
            $distinct,
            $columns,
            $where,
            $groupBy,
            $having,
            $orderBy,
            $limit,
            $offset
        );
        $sql = $sql->execute($type);
        $sth = $this->prepare($sql['sql']);
        $sth->execute($sql['value']);
        return $sth;
    }
}
