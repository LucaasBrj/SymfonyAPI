<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Entity\Character;
use App\Entity\Family;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\FamilyRepository;



#[AsCommand(
    name: 'FamilyMigration',
    description: 'Migration des familles',
)]
class FamilyMigrationCommand extends Command
{
    private $entityManager;
    private $familyRepository;

    public function __construct(FamilyRepository $familyRepository, EntityManagerInterface $entityManager)
    {
        $this->familyRepository = $familyRepository;
        $this->entityManager = $entityManager;
        parent::__construct();
    }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://thronesapi.com/api/V2/characters");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($response, true);

        foreach($response as $character) {
            $name = $this->familyRepository->findOneByName($character['family']);
            if (!$name && $character['family']) {
                $famille = new Family;
                $famille->setName($character["family"]);
                $this->entityManager->persist($famille);
                $this->entityManager->flush();
            }
        }

        return Command::SUCCESS;
    }
}
