<?php

namespace MicroPHP\Model\SQL;

use function PHPSTORM_META\type;

class SQL
{
    private $table;
    private $distinct = false;
    private $columns = ['*'];
    private $where;
    private $groupBy = [];
    private $having = [];
    private $orderBy = [];
    private $limit = 0;
    private $offset = 0;
    public function __construct(
        $table,
        $distinct = false,
        $columns = ['*'],
        $where = [],
        $groupBy = [],
        $having = [],
        $orderBy = [],
        $limit = 0,
        $offset = 0
    ) {
        $this->table = $table;
        $this->distinct = $distinct;
        $this->columns = $columns;
        $this->where = new Where($where);
        $this->groupBy = $groupBy;
        $this->having = new Where($having);
        $this->orderBy = $orderBy;
        $this->limit = $limit;
        $this->offset = $offset;
    }
    public function execute($type = 'select')
    {
        $sql = [];
        $value = [];
        switch ($type) {
            case 'select':
                $sql[] = "SELECT";
                $sql[] = implode(', ', $this->columns);
                $sql[] = 'FROM';
                $sql[] = $this->table;
                break;
            case 'delete':
                $sql[] = "DELETE FROM";
                $sql[] = $this->table;
                break;
            case 'update':
                $sql[] = "UPDATE";
                $sql[] = $this->table;
                $sql[] = "SET";
                $set = (new Where($this->columns))->execute(prefix: '', separator: ',');
                $sql[] = $set['sql'];
                while (count($set['value'])) {
                    array_push($value, array_shift($set['value']));
                }
                break;
        }
        $where = $this->where->execute();
        $sql[] = $where['sql'];
        while (count($where['value'])) {
            array_push($value, array_shift($where['value']));
        }
        if (empty($this->groupBy) === false) {
            $sql[] = 'group by ' . implode(', ', $this->groupBy);
        }
        $having = $this->having->execute('having');
        $sql[] = $having['sql'];
        while (count($having['value'])) {
            array_push($having, array_shift($having['value']));
        }
        if (empty($this->orderBy) === false) {
            $sql[] = 'order by ' . implode(', ', $this->orderBy);
        }
        if ($this->limit) {
            if ($this->offset) {
                $sql[] = "limit {$this->limit}, {$this->offset}";
            } else {
                $sql[] = "limit {$this->limit}";
            }
        }
        return [
            'sql' => implode(' ', $sql),
            'value' => $value,
        ];
    }
}
