<?php

namespace App\Controller;

use App\Entity\Bulletin;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {

        // Notre fonctin index() publie des Bulletins à partir de notre base de données
        // Pour récupérer des éléments de notre base de données, nous allons avoir de l'entity Manager
        // de Doctrine, et du Repository que nous voulons récupérer (ici Bulletin)

        $entityManager = $this -> getDoctrine() -> getManager();
        $bulletinRepository = $entityManager -> getRepository(Bulletin::class);
        // Nous utilisons la méthode findAll() du Repository pour récupérer tous les éléments de la table Bulletin
        $bulletin = $bulletinRepository -> findAll();
        //Coeur de notre fonction (logique algorithmique)
        return $this->render('index/index.html.twig', [
            'bulletins' => $bulletin,
        ]);
    }

    /**
     * @Route("/cheatsheet", name="index_cheatsheet")
     */
    public function cheatsheet(): Response
    {
        return $this->render('index/cheatsheet.html.twig');
    }

    /**
     * @Route("/bulletin/display/{bulletinId}", name="bulletin_display")
     */
    public function displayBulletin($bulletinId = false): Response{
        // Cette fonction affiche un seul bulletin selon le numero de d'ID présenté dans la barre d'adresse
        // Pour dialoguer avec la BDD, nous récupérons l'Entity Manager et la Repository pertinet
        $entityManager = $this -> getDoctrine() -> getManager();
        $bulletinRepository = $entityManager -> getRepository(Bulletin::class);
        // Nous récupérons notre Bulletin selon l'ID indiqué
        $bulletin = $bulletinRepository -> find($bulletinId);
        // Si le résultat est nul, notre fonction n'a plus de raison d'être et nous revenons à l'index
        if(!$bulletin) {
            return $this -> redirect($this -> generateUrl('index'));
        }
        // Si le bulletin existe, nous le transmettons à notre page twig
        return $this -> render('index/index.html.twig', [
            'bulletins' => [$bulletin], // Nous transmettons un tableau à une seule entrée
        ]);
    }

    /**
     * @Route("/bulletin/generate", name="bulletin_generate")
     */
    public function generateBulletin(): Response
    {
        //Cette fonction a pour objectif de générer un tableau d'objets Bulletin avant de le publier sur la page twig d'index

        //Nous allons créer un tableau et le remplir via une boucle for
        $bulletins = []; //$bulletinS est logiquement un tableau composé d'objet de type "bulletin"
        for ($i = 0; $i < 10; $i++) {
            //A chaque tour de boucle, un nouvel objet est créé, renseigné, et ajouté au tableau $bulletins
            $bulletin = new Bulletin; //Nous créons un nouvel objet Bulletin avant de le renseigner via setters
            $bulletin->setTitle('Bulletin #0' . $i); //Le titre de notre bulletin correspond à la valeur de 
            // $i
            $bulletin->setCategory('general');
            $bulletin->setContent('Lorem Ipsum etc');
            // $bulletin->setCreationDate(new \DateTime('now'));
            array_push($bulletins, $bulletin); //Nous ajoutons au tableau $bulletins l'objet $bulletin
        }
        $bulletins = array_reverse($bulletins); //la nouvelle valeur de $bulletins est un tableau dont l'index est inversé
        //Maintenant que notre tableau est renseigné, nous pouvons le passer à notre page twig:
        return $this->render('index/index.html.twig', [
            'bulletins' => $bulletins, //La variable twig 'bulletins' aura pour valeur notre tableau
        ]);
    }

    /**
     * @Route("/square-display/{var}", name="square_display")
     */
    public function squareDisplay($var = false): Response
    {
        //Nous capturons la valeur que l'utilisateur écrit à la place de {var} et nous la plaçons au sein de la variable $var
        //Nous pouvons traiter $var via la logique algorithmique de notre fonction et rendre le résultat en conséquence
        $color = "couleur à déterminer"; //La couleur à transmettre à notre attribut de style "background-color", décidant de la couleur de notre <div>
        if ($var === false) {
            $color = "gray";
        } else {
            switch ($var) { //Switch va comparer la valeur de $color avec plusieurs possibilités (case)
                case "rouge":
                    $color = "red";
                    break; //permet de sortir d'une structure de contrôle, ici, la structure "switch"
                case "bleu":
                    $color = "blue";
                    break;
                case "vert":
                    $color = "green";
                    break;
                case "jaune":
                    $color = "yellow";
                    break;
                default: //default est une condition valable dans tous les cas de figure
                    $color = "black";
            }
        }


        //Nous rendons une nouvelle Response dont la valeur correspond à ce qui est passé en argument
        return new Response('<div style="width:500px; height:500px; background-color:' . $color . ';"></div>');
    }
}
