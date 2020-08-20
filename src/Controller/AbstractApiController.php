<?php

namespace App\Controller;

use App\Serializer\JsonSerializer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractApiController
{
    const OK = Response::HTTP_OK;
    const CREATED = Response::HTTP_CREATED;

    protected $em;
    protected $serializer;

    public function __construct(
        EntityManagerInterface $em,
        JsonSerializer $serializer
    ) {
        $this->em = $em;
        $this->serializer = $serializer;
    }
}
