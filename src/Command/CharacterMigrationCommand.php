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
use App\Repository\CharacterRepository;
use App\Repository\FamilyRepository;



#[AsCommand(
    name: 'CharacterMigration',
    description: 'Migration des personnages',
)]
class CharacterMigrationCommand extends Command
{
    private $entityManager;
    private $characterRepository;
    private $familyRepository;

    public function __construct(FamilyRepository $familyRepository, CharacterRepository $characterRepository, EntityManagerInterface $entityManager)
    {
        $this->familyRepository = $familyRepository;
        $this->characterRepository = $characterRepository;
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
            $personnage = new Character;
            $name = $this->familyRepository->findOneByName($character['family']);
            if ($name) {
                $familyId = $name;
                $personnage->setFamille($familyId);
            }
            $personnage->setfirstName($character["firstName"]);
            $personnage->setlastName($character["lastName"]);
            $personnage->setfullName($character["fullName"]);
            $personnage->setTitle($character["title"]);
            $personnage->setFamily($character["family"]);
            $personnage->setImageurl($character["imageUrl"]);
            $personnage->setImage($character['image']);

            $this->entityManager->persist($personnage);
            $this->entityManager->flush();
        }

        return Command::SUCCESS;
    }
}
