<?php

namespace MicroPHP\Model\SQL;

class Where
{
    private $where;
    public function __construct($where)
    {
        $this->where = $where;
    }
    private function is_single_dimensional($data)
    {
        return count($data) === count($data, COUNT_RECURSIVE);
    }
    private function is_assoc($array)
    {
        return array_reduce(array_keys($array), fn ($reduce, $item) => $reduce && is_string($item), true);
    }
    private function is_index($array)
    {
        return array_reduce(array_keys($array), fn ($reduce, $item) => $reduce && is_int($item), true);
    }
    public function execute($prefix = 'where', $separator = 'and')
    {
        if ($this->is_single_dimensional($this->where)) {
            if ($this->is_assoc($this->where)) {
                return [
                    'sql' => empty($this->where) ? '' : ($prefix . ' ' . implode(" {$separator} ", array_map(fn ($item) => "`{$item}` = ?", array_keys($this->where)))),
                    'value' => array_values($this->where),
                ];
            }
        }
        return ['sql' => '', 'value' => ''];
    }
}
