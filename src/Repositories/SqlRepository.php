<?php

namespace OZiTAG\Tager\Backend\Core\Repositories;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class SqlRepository
{
    public function select(string $sql, array $params = []): array
    {
        return (array)DB::select($sql, $params);
    }

    public function first(string $sql, array $params = []): mixed
    {
        return Arr::first($this->select($sql, $params));
    }

    public function count(string $sql, array $params): int
    {
        return (int)(((array)$this->first($sql, $params))['count'] ?? 0);
    }
}
