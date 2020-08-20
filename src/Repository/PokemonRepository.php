<?php

namespace App\Repository;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Pokemon;
use App\Entity\Type;
use App\Service\PaginatorService;
use Datetime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

/**
 * @method Pokemon|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pokemon|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pokemon[]    findAll()
 * @method Pokemon[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PokemonRepository extends ServiceEntityRepository
{
    private $validator;
    private $paginator;

    public function __construct(ManagerRegistry $registry, ValidatorInterface $validator, PaginatorService $paginator)
    {
        parent::__construct($registry, Pokemon::class);
        $this->validator = $validator;
        $this->paginator = $paginator;
    }

    /**
     * Undocumented function.
     *
     * @param Pokemon $pokemon
     *
     * @return void
     */
    public function createOrUpdate(array $data, Pokemon $pokemon = null)
    {
        $types = $this->typesByName($data['types'] ?? null);

        $pokemon = (!empty($pokemon)) ? $pokemon : new Pokemon();
        $pokemon->setName($data['name'] ?? null)
            ->setTotal($data['total'] ?? null)
            ->setHp($data['hp'] ?? null)
            ->setAttack($data['attack'] ?? null)
            ->setDefense($data['defense'] ?? null)
            ->setAttackSp($data['attackSp'] ?? null)
            ->setDefenseSp($data['defenseSp'] ?? null)
            ->setGeneration($data['generation'] ?? null)
            ->setSpeed($data['speed'] ?? null)
            ->setLegendary($data['legendary'] ?? null)
            ->setCreatedAt(new Datetime())
            ->setTypes($types);

        $errors = $this->validator->validate($pokemon) ?? [];
        if (count($errors) > 0) {
            throw new Exception((string) $errors, 400);
        }
        $this->getEntityManager()->persist($pokemon);
        $this->getEntityManager()->flush();
    }

    /**
     * find entities types in database with list of types string.
     */
    private function typesByName(?array $names): ?array
    {
        if (empty($names)) {
            return [];
        }
        $names = array_map('ucfirst', $names);

        return $this->getEntityManager()->getRepository(Type::class)->findBy(['name' => $names]);
    }

    public function filterPokemons($page = 1, ?string $order = 'asc'): Paginator
    {
        $query = $this->createQueryBuilder('p');

        $query->orderBy('p.name', strtoupper($order))
            ->getQuery();

        return $this->paginator->paginate($query, $page);
    }
}
