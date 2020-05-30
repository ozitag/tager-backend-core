<?php

namespace OZiTAG\Tager\Backend\Core\Repositories;

use Illuminate\Database\Eloquent\Model;

interface IEloquentRepository
{
    /**
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes): Model;

    /**
     * @param $id
     * @return Model
     */
    public function find($id): ?Model;

    /**
     * @return Model|null
     */
    public function first(): ?Model;

    /**
     * Returns all the records.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all();

    /**
     * Returns the count of all the records.
     *
     * @return int
     */
    public function count();

    /**
     * @param $attributes
     * @return Model
     */
    public function fillAndSave($attributes);

    /**
     * @param $attributes
     */
    public function update($attributes);
}
