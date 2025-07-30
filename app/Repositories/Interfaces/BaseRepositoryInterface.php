<?php

namespace App\Repositories\Interfaces;
use  App\DataTransferObjects\BaseDto;

interface BaseRepositoryInterface
{

    public function index(array $filters = [], int $perPage = 15);

    public function show(int $id);

    public function store(BaseDto $data);

    public function update(BaseDto $data, $id);

    public function destroy(int $id);
}
