<?php

namespace App\Controller;

use App\Entity\Pokemon;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/pokemons")
 */
class PokemonController extends AbstractApiController
{
    /**
     * @Route("/",  methods={"GET"}, name="pokemon_list")
     */
    public function list(Request $request)
    {
        return JsonResponse::fromJsonString(
            $this->serializer->serialize(
                $this->em->getRepository(Pokemon::class)->filterPokemons(
                    $request->query->get('page') ?? 1,
                    $request->query->get('order') ?? 'asc'
                ),
                ['pokemon_read']
            ),
            self::OK
        );
    }

    /**
     * @Route("/{id}",  methods={"GET"}, name="pokemon_read")
     */
    public function read(Pokemon $pokemon)
    {
        return JsonResponse::fromJsonString(
            $this->serializer->serialize(
                $pokemon,
                ['pokemon_read']
            ),
            self::OK
        );
    }

    /**
     * @Route("",  methods={"POST"}, name="pokemon_create")
     */
    public function create(Request $request)
    {
        try {
            $data = json_decode($request->getContent(), true);
            $this->em->getRepository(Pokemon::class)->createOrUpdate($data);
            $response['success'] = 'Pokemon Added';
        } catch (Exception $e) {
            $status = $e->getCode();
            $response['errors'] = $e->getMessage();
        }

        return JsonResponse::fromJsonString(
            $this->serializer->serialize(
                $response,
                ['pokemon_read']
            ),
            $status ?? self::CREATED
        );
    }

    /**
     * @Route("/{id}",  methods={"PUT"}, name="pokemon_edit")
     */
    public function edit(Request $request, Pokemon $pokemon)
    {
        try {
            $data = json_decode($request->getContent(), true);
            $this->em->getRepository(Pokemon::class)->createOrUpdate($data, $pokemon);
            $response['success'] = 'Pokemon updated';
        } catch (Exception $e) {
            $status = $e->getCode();
            $response['errors'] = $e->getMessage();
        }

        return JsonResponse::fromJsonString(
            $this->serializer->serialize(
                $response,
                ['pokemon_read']
            ),
            $status ?? self::OK
        );
    }

    /**
     * @Route("/{id}",  methods={"DELETE"}, name="pokemon_delete")
     */
    public function delete(Pokemon $pokemon)
    {
        try {
            $this->em->remove($pokemon);
            $this->em->flush();

            $response['success'] = 'Pokemon deleted';
        } catch (Exception $e) {
            $status = $e->getCode();
            $response['errors'] = $e->getMessage();
        }

        return new JsonResponse($response, $status ?? self::OK);
    }
}
