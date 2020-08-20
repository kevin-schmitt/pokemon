<?php

namespace App\Service;

use Doctrine\ORM\Tools\Pagination\Paginator;

class PaginatorService
{
    const OFFSET = 60;

    public function paginate($dql, $page = 1): Paginator
    {
        $paginator = new Paginator($dql);

        $paginator->getQuery()
            ->setFirstResult(self::OFFSET * ($page - 1)) // Offset
            ->setMaxResults(self::OFFSET); // Limit

        return $paginator;
    }
}
