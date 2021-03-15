<?php

namespace App\Controller;

use App\Repository\AdherentRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StatsController extends AbstractController
{
    /**
     * Renvoie le nombre de prêts par adhérent
     * @Route(
     *      path="apiPlatform/adherents/nbPretsParAdherent",
     *      name="adherent_nbPrets",
     *      methods={"GET"}
     * )
     */
    public function nombrePretsParAdherent(AdherentRepository $repo)
    {
        $nbPretParAdherent = $repo->nombrePretsParAdherent();
        return $this->json($nbPretParAdherent);
    }
}
