<?php

namespace App\Command;

use App\Entity\Pokemon;
use App\Entity\Type;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class LoadPokemonCsvCommand extends Command
{
    private $em;
    private $t = 0;
    const PATH = '%kernel.root_dir%/../src/Data/pokemon.csv';

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();

        $this->em = $em;
    }

    protected static $defaultName = 'load:pokemon';

    protected function configure()
    {
        $this
            ->setDescription('Permit to import pokemon from csv file')
            ->setHelp('You need to place pokemon/csv in src/Data directory')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $csv = Reader::createFromPath(self::PATH, 'r');
        $csv->setHeaderOffset(0);

        $io = new SymfonyStyle($input, $output);
        $io->progressStart(iterator_count($csv));

        $types = [];

        $this->em->getConnection()->getConfiguration()->setSQLLogger(null);

        foreach ($csv->getRecords() as $index => $records) {
            $types = $this->saveType($records['Type 1'], $types);
            $types = $this->saveType($records['Type 2'], $types);

            $pokemon = $this->savePokemon($records, $types);
            $this->em->persist($pokemon);
            $this->em->flush();

            if ($index % 25) {
                $this->em->clear();
            }

            $io->progressAdvance();
        }

        $io->progressFinish();
        $io->success('Command exited cleanly!');

        return 0;
    }

    /**
     * save type if not exist.
     *
     * @param array  $types    list of types already inserted
     * @param string $typeName type of current line and column in csv
     *
     * @return array $types updated if value no exist
     */
    private function saveType(string $typeName, array $types): array
    {
        if (array_key_exists($typeName, $types)) {
            return $types;
        }

        $type = (new Type())
            ->setName($typeName)
            ->setCreatedAt(new \DateTime());

        $this->em->persist($type);
        $types[$typeName] = $type;

        return $types;
    }

    private function savePokemon(array $records, array $types): Pokemon
    {
        $pokemon = (new Pokemon())
                    ->setName($records['Name'])
                    ->addType(
                        $types[$records['Type 1']]
                    )
                    ->addType(
                        $types[$records['Type 2']]
                    )
                    ->setTotal($records['Total'])
                    ->setHp($records['HP'])
                    ->setAttack($records['Attack'])
                    ->setDefense($records['Defense'])
                    ->setAttackSp($records['Sp. Atk'])
                    ->setDefenseSp($records['Sp. Def'])
                    ->setSpeed($records['Speed'])
                    ->setGeneration($records['Generation'])
                    ->setLegendary($records['Legendary'])
                    ->setCreatedAt(new \DateTime());
        $this->em->persist($pokemon);
        ++$this->t;

        return $pokemon;
    }
}
