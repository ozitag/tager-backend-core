<?php

namespace OZiTAG\Tager\Backend\Core\Repositories;


use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class SqlRepository
{
    /**
     * @param string $sql
     * @param array $params
     * @return array
     */
    public function select(string $sql, array $params = []) {
        return (array) DB::select($sql, $params);
    }

    /**
     * @param string $sql
     * @param array $params
     * @return mixed
     */
    public function first(string $sql, array $params = []) {
        return Arr::first($this->select($sql, $params));
    }

    /**
     * @param string $sql
     * @param array $params
     * @return int
     */
    public function count(string $sql, array $params) {
        return (int) (((array) $this->first($sql, $params))['count'] ?? 0);
    }
}
