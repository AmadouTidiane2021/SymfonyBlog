<?php

namespace App\DataFixtures;

use App\Entity\Bulletin;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class BulletinFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $lorem = "Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression. Le Lorem Ipsum est le faux
        texte standard de l'imprimerie depuis les années 1500, quand un imprimeur anonyme assembla ensemble des morceaux de texte pour réaliser un livre 
        spécimen de polices de texte. Il n'a pas fait que survivre cinq siècles, mais s'est aussi adapté à la bureautique informatique, sans que son contenu 
        n'en soit modifié. Il a été popularisé dans les années 1960 grâce à la vente de feuilles Letraset contenant des passages du Lorem Ipsum, et, plus récemment, 
        par son inclusion dans des applications de mise en page de texte, comme Aldus PageMake.
         
        Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression. Le Lorem Ipsum est le faux
        texte standard de l'imprimerie depuis les années 1500, quand un imprimeur anonyme assembla ensemble des morceaux de texte pour réaliser un livre 
        spécimen de polices de texte. Il n'a pas fait que survivre cinq siècles, mais s'est aussi adapté à la bureautique informatique, sans que son contenu 
        n'en soit modifié. Il a été popularisé dans les années 1960 grâce à la vente de feuilles Letraset contenant des passages du Lorem Ipsum, et, plus récemment, 
        par son inclusion dans des applications de mise en page de texte, comme Aldus PageMaker.";
        
        //Nous allons créer un tableau et le remplir via une boucle for
         for ($i = 0; $i < 25; $i++) {
            //A chaque tour de boucle, un nouvel objet est créé, renseigné, et ajouté au tableau $bulletins
            $bulletin = new Bulletin; //Nous créons un nouvel objet Bulletin avant de le renseigner via setters
            $bulletin->setTitle('Bulletin #' . rand(0,999)); //Le titre de notre bulletin correspond à la valeur de 
            // $i
            $bulletin->setCategory('general');
            $bulletin->setContent($lorem);
            // $bulletin->setCreationDate(new \DateTime('now')); // pris en charge par le constructeur
            $manager->persist($bulletin);// DEMANDE de persistance de l'objet $bulletin

        }

        $manager->flush(); // Applique toutes les requêtes envoyées au Manager
    }
}

