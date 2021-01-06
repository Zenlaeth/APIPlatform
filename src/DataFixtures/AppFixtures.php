<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Adherent;
use App\Entity\Livre;
use App\Entity\Pret;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    private $manager;
    private $faker;
    private $repoLivre;
    public function __construct()
    {
        $this->faker=Factory::create("fr_FR");
    }
    public function load(ObjectManager $manager)
    {
        $this->manager=$manager;
        $this->repoLivre=$this->manager->getRepository(Livre::class);
        $this->loadAdherent();
        $this->loadPret();

        $manager->flush();
    }

    /**
     * Création des adhérents
     *
     * @return void
     */
    public function loadAdherent(){

        $genre=['male','female'];
        $commune=["78003","78005","78006", "78007", "78009"];
        for($i=0;$i<25;$i++){
            $adherent=new Adherent();
            $adherent   ->setNom($this->faker->lastName())
                        ->setPrenom($this->faker->firstName($genre[mt_rand(0,1)]))
                        ->setAdresse($this->faker->streetAddress())
                        ->setTel($this->faker->phoneNumber())
                        ->setCodeCommune($commune[mt_rand(0,sizeof($commune)-1)])
                        ->setMail(strtolower($adherent->getNom())."gmail.com")
                        ->setPassword($adherent->getNom());
            $this->addReference("adherent".$i,$adherent);
            $this->manager->persist($adherent);
        }

            $adherent=new Adherent();
            $adherent   ->setNom("Oumar")
                        ->setPrenom("Thierno")
                        ->setMail("admin@gmail.com")
                        ->setPassword("Oumar");
            $this->manager->persist($adherent);

        $this->manager->flush();
    }

    /**
     * Création des prêts
     *
     * @return void
     */
    public function loadPret(){

        for($i=0;$i<25;$i++){ // pour chaque adhérent
            $max=mt_rand(1,5);
            for($j=0;$j<$max;$j++){ // création des prêts
                $pret=new Pret();
                $livre=$this->repoLivre->find(mt_rand(1,49));
                $pret   ->setLivre($livre)
                        ->setAdherent($this->getReference("adherent".$i))
                        ->setDatePret($this->faker->dateTimeBetween('-6 months'));
                $dateRetourPrevue=date('Y-m-d H:m:n', strtotime('15 days', $pret->getDatePret()->getTimestamp()));
                $dateRetourPrevue=\DateTime::createFromFormat('Y-m-d H:m:n',$dateRetourPrevue);
                $pret   ->setDateRetourPrevue($dateRetourPrevue);

                if(mt_rand(1,3)==1){
                    $pret->setDateRetourReelle($this->faker->dateTimeInInterval($pret->getDatePret(),"+30 days"));
                }
                $this->manager->persist($pret);
            }
        }
        $this->manager->flush();
    }
}
