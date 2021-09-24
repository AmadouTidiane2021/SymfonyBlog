<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Form\TagType;
use App\Entity\Bulletin;
use App\Form\BulletinType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(Request $request): Response
    {
        //Notre fonction index() publie des Bulletin à partir de notre base de données
        //Pour récupérer des éléments de notre base de données, nous allons avoir de l'Entity Manager de Doctrine, et du Repository de l'Entity que nous voulons récupérer (ici, Bulletin)
        $entityManager = $this->getDoctrine()->getManager();
        $bulletinRepository = $entityManager->getRepository(Bulletin::class);
        //Nous créons un nouvel objet Bulletin que nous lions à notre formulaire
        $bulletin = new Bulletin;
        $bulletinForm = $this->createForm(BulletinType::class, $bulletin);
        //Nous transmettons la requête client à notre formulaire
        $bulletinForm->handleRequest($request);
        if ($request->isMethod('post') && $bulletinForm->isValid()) {
            //Si notre requête client a été validée via un formulaire POST et que ce formulaire est valide
            $entityManager->persist($bulletin); //Bulletin est déjà rempli via handleRequest()
            $entityManager->flush();
            return $this->redirect($this->generateUrl('index'));
        }
        //Nous utilisons la méthode findAll() du Repository pour récupérer tous les éléments de la table Bulletin
        $bulletins = $bulletinRepository->findAll();
        $bulletins = array_reverse($bulletins); //la nouvelle valeur de $bulletins est un tableau dont l'index est inversé
        return $this->render('index/index.html.twig', [
            'bulletinForm' => $bulletinForm->createView(),
            'bulletins' => $bulletins,
        ]);
    }

    /**
     * @Route("/category/{categoryName}", name="index_category")
     */
    public function indexCategory(string $categoryName = ""): Response
    {
        //Cette fonction renvoie la liste des bulletins appartenant à une catégorie donnée
        //Nous devons récupérer l'Entity Manager et le Repository pertinent pour dialoguer avec notre BDD
        $entityManager = $this->getDoctrine()->getManager();
        $bulletinRepository = $entityManager->getRepository(Bulletin::class);
        //Nous récupérons les Bulletin dont la catégorie correspond à la valeur passée en paramètre de route
        $bulletins = $bulletinRepository->findByCategory($categoryName); //findBy rend un TABLEAU
        //Version alternative -> findBy(['category' => ($categoryName)])
        //Si le tableau est vide, nous retournons à l'index
        if (!$bulletins || !$categoryName) { //Si $bulletins OU $categoryName est vide
            return $this->redirect($this->generateUrl('index'));
        }
        //Si nous obtenons un résultat à partir de notre recherche, nous le renvoyons à notre twig
        $bulletins = array_reverse($bulletins); //On inverse l'ordre des bulletins
        return $this->render('index/index.html.twig', [
            'bulletins' => $bulletins,
        ]);
    }

    /**
     * @Route("/cheatsheet", name="index_cheatsheet")
     */
    public function cheatsheet(): Response
    {
        return $this->render('index/cheatsheet.html.twig', ['cheatsheet' => true]);
    }

    /**
     * @Route("/bulletin/display/{bulletinId}", name="bulletin_display")
     */
    public function displayBulletin($bulletinId = false): Response
    {
        //Cette fonction affiche un seul Bulletin selon le numéro d'ID présenté dans la barre d'adresse
        //Pour dialoguer avec la BDD, nous récupérons l'Entity Manager et le Repository pertinent
        $entityManager = $this->getDoctrine()->getManager();
        $bulletinRepository = $entityManager->getRepository(Bulletin::class);
        //Nous récupérons notre Bulletin selon l'ID indiqué
        $bulletin = $bulletinRepository->find($bulletinId);
        //Si le résultat est nul, notre fonction n'a plus de raison d'être et nous revenons à l'index
        if (!$bulletin) {
            return $this->redirect($this->generateUrl('index'));
        }
        //Si le bulletin existe, nous le transmettons à notre page Twig
        return $this->render('index/index.html.twig', [
            'bulletins' => [$bulletin], //Nous transmettons un tableau à une seule entrée
        ]);
    }

    /**
     * @Route("/tag/create", name="tag_create")
     */
    public function createTag(Request $request): Response
    {
        //Cette fonction crée un Tag selon les informations entrées par l'Utilisateur via formulaire
        //Nous faisons appel à l'Entity Manager afin de communiquer avec notre BDD
        $entityManager = $this->getDoctrine()->getManager();
        //Nous créons un nouvel objet Tag que nous lions à notre formulaire
        $tag = new Tag;
        $tagForm = $this->createForm(TagType::class, $tag);
        //Nous transmettons la requête client à notre formulaire
        $tagForm->handleRequest($request);
        if ($request->isMethod('post') && $tagForm->isValid()) {
            //Si notre requête client a été validée via un formulaire POST et que ce formulaire est valide
            $entityManager->persist($tag); //Tag est déjà rempli via handleRequest()
            $entityManager->flush();
            return $this->redirect($this->generateUrl('index'));
        }
        //Nous affichons le formulaire
        return $this->render('index/dataform.html.twig', [
            'formName' => 'Création de Tag',
            'dataForm' => $tagForm->createView(),
        ]);
    }

    /**
     * @Route("/bulletin/create", name="bulletin_create")
     */
    public function createBulletin(Request $request): Response
    {
        //Cette fonction crée un Bulletin selon les informations entrées par l'Utilisateur via formulaire
        //Nous faisons appel à l'Entity Manager afin de communiquer avec notre BDD
        $entityManager = $this->getDoctrine()->getManager();
        //Nous créons un nouvel objet Bulletin que nous lions à notre formulaire
        $bulletin = new Bulletin;
        $bulletinForm = $this->createForm(BulletinType::class, $bulletin);
        //Nous transmettons la requête client à notre formulaire
        $bulletinForm->handleRequest($request);
        if ($request->isMethod('post') && $bulletinForm->isValid()) {
            //Si notre requête client a été validée via un formulaire POST et que ce formulaire est valide
            $entityManager->persist($bulletin); //Bulletin est déjà rempli via handleRequest()
            $entityManager->flush();
            return $this->redirect($this->generateUrl('index'));
        }
        //Nous affichons le formulaire
        return $this->render('index/dataform.html.twig', [
            'formName' => 'Création de Bulletin',
            'dataForm' => $bulletinForm->createView(),
        ]);
    }

    /**
     * @Route("/bulletin/update/{bulletinId}", name="bulletin_update")
     */
    public function updateBulletin(Request $request, int $bulletinId = 0): Response
    {
        //Cette fonction est chargée de modifier un bulletin transmis par notre barre d'adresse, via un formulaire fourni à l'Utilisateur
        //Nous récupérons l'Entity Manager et le Repository pertinent
        $entityManager = $this->getDoctrine()->getManager();
        $bulletinRepository = $entityManager->getRepository(Bulletin::class);
        //Nous récupérons le Bulletin selon l'ID indiqué par notre paramètre de route
        $bulletin = $bulletinRepository->find($bulletinId);
        //Si le bulletin n'existe pas, nous retournons à l'index
        if (!$bulletin) {
            return $this->redirect($this->generateUrl('index'));
        }
        //Si le Bulletin existe, nous pouvons créer notre formulaire
        $bulletinForm = $this->createForm(BulletinType::class, $bulletin);
        //Nous transmettons la requête client à notre formulaire
        $bulletinForm->handleRequest($request);
        //Si notre bulletin est valide, nous le persistons
        if ($request->isMethod('post') && $bulletinForm->isValid()) {
            $entityManager->persist($bulletin);
            $entityManager->flush();
            return $this->redirect($this->generateUrl('index'));
        }
        //Si le bulletin n'est pas validé, nous affichons le formulaire pour l'Utilisateur
        return $this->render('index/dataform.html.twig', [
            'formName' => 'Mise à Jour de Bulletin',
            'dataForm' => $bulletinForm->createView(),
        ]);
    }

    /**
     * @Route("/bulletin/delete/{bulletinId}", name="bulletin_delete")
     */
    public function deleteBulletin($bulletinId = false): Response
    {
        //Cette fonction a pour objectif de supprimer un élément Bulletin de notre base de données selon l'id qui aura été communiqué via le paramètre de route
        //Pour cela, nous récupérons l'Entity Manager et le Repository pertinent (Bulletin)
        $entityManager = $this->getDoctrine()->getManager();
        $bulletinRepository = $entityManager->getRepository(Bulletin::class);
        //Nous récupérons le bulletin dont l'ID est indiqué
        $bulletin = $bulletinRepository->find($bulletinId);
        //Si le bulletin n'existe pas, nous revenons à l'index
        if (!$bulletin) {
            return $this->redirect($this->generateUrl('index')); //redirection vers index
        }
        //Si le bulletin existe, nous le supprimons
        $entityManager->remove($bulletin); //requête de suppression de $bulletin
        $entityManager->flush(); //application de toutes les requêtes antérieures
        //Notre bulletin supprimé, nous retournons vers l'index
        return $this->redirect($this->generateUrl('index'));
    }

    /**
     * @Route("/bulletin/autopersist", name="bulletin_autopersist")
     */
    public function autopersistBulletin(): Response
    {
        //Cette fonction doit générer un Bulletin automatiquement et le faire persister dans la base de données
        // 1. Récupérer les outils nécessaires (Entity Manager)
        $entityManager = $this->getDoctrine()->getManager();
        // 2. Créer notre objet Bulletin et le Renseigner
        $lorem = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec posuere diam vitae pharetra faucibus. Fusce eget arcu orci. Etiam venenatis lorem eget feugiat commodo. Fusce felis dolor, aliquam in felis at, congue pulvinar justo. Curabitur fringilla dolor nec consequat porttitor. Proin consequat leo vitae nisl laoreet, pretium varius justo consectetur.";
        $categories = ['general', 'divers', 'urgent']; //catégories possibles
        //Création du Bulletin après avoir prédéterminé les valeurs possibles de content et category
        $bulletin = new Bulletin; //Nous créons un nouvel objet Bulletin avant de le renseigner via setters
        $bulletin->setTitle('Bulletin Généré #' . rand(0, 999));
        $bulletin->setCategory($categories[rand(0, (count($categories) - 1))]); //Choix aléatoire entre un des éléments du tableau $categories
        $bulletin->setContent($lorem);
        // 3. Faire persister notre Bulletin
        $entityManager->persist($bulletin); //DEMANDE de persistance de l'objet $bulletin
        $entityManager->flush(); //Applique toutes les requêtes envoyées au Manager
        // 4. Rediriger vers index()
        return $this->redirect($this->generateUrl('index'));
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
            $bulletin->setTitle('Bulletin #0' . $i); //Le titre de notre bulletin correspond à la valeur de $i
            $bulletin->setCategory('general');
            $bulletin->setContent('Lorem Ipsum etc');
            //$bulletin->setCreationDate(new \DateTime('now')); //Pris en charge par le constructeur
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
