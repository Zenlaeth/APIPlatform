<?php

namespace App\Controller;

use App\Entity\Adherent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdherentController extends AbstractController
{
    /**
     * Renvoie le nombre de prêts pour un adhérent
     * @Route(
     *      path="apiPlatform/adherent/{id}/pret/count",
     *      name="adherent_prets_count",
     *      methods={"GET"},
     *      defaults={
     *          "_controller"="\app\controller\AdherentController::nombrePrets",
     *          "_api_resource_class"="App\Entity\Adherent",
     *          "_api_item_operation_name"="getNbPrets"
     *      }
     * )
     */
    public function nombrePrets(Adherent $data)
    {
        $count = $data->getPrets()->count();
        return $this->json([
            "id" => $data->getId(),
            "nombre_prets" => $count
        ]);
    }
}