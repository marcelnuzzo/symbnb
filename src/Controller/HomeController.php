<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController {

    /**
     * @Route("/bonjour/{prenom}/age/{age}", name="hello")
     * @Route("/salut", name="hello_base")
     * @Route("bonjour/{prenom}", name="hello_prenom")
     * Montre la page qui dit bonjour
     *
     * @return void
     */
    public function hello($prenom = "anonyme", $age = 0) {
        return $this->render(
            'hello.html.twig',
            [
                'prenom' => $prenom,
                'age' => $age
            ]
            );
    }

    /**
     * Undocumented function
     *
     * @Route("/", name="homepage")
     */
    public function home() {
        $prenom = ["Marcel" => 31, "joseph" => 12,"Anne" => 55];

        return $this->render(
            'home.html.twig',
            [ 'title' => "Bonjour",
              "age" => 31,
              "tableau" => $prenom
            ]
        );
    }
}

?>