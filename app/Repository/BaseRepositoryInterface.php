<?php

namespace App\Repository;

interface BaseRepositoryInterface
{
    public function paginateRequest(array $requestParams);
}
