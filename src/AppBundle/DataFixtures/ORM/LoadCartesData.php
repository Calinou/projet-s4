<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Cartes as Carte;

class LoadCartesData implements FixtureInterface
{
    /**
     * Initializes database with card data (60 cards).
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $cards = [
            // categorie, valeur, nom, annee
            [1, 2,  "Blanche-Neige", 1937],
            [1, 3,  "Pinocchio", 1940],
            [1, 4,  "Dumbo", 1941],
            [1, 5,  "Bambi", 1942],
            [1, 6,  "Cendrillon", 1950],
            [1, 7,  "Alice au pays des merveilles", 1951],
            [1, 8,  "Peter-Pan", 1953],
            [1, 9,  "La belle et le clochard", 1958],
            [1, 10, "La belle au bois dormant", 1959],
            [1, 11, "Carte Extra 1", -1],
            [1, 12, "Carte Extra 2", -1],
            [1, 13, "Carte Extra 3", -1],
            [2, 2,  "101 Dalmatiens", 1961],
            [2, 3,  "Merlin l'enchanteur", 1963],
            [2, 4,  "Mary Poppins", 1964],
            [2, 5,  "Le Livre de la jungle", 1967],
            [2, 6,  "Les Aristochats", 1970],
            [2, 7,  "Robin des Bois", 1973],
            [2, 8,  "Winnie l'ourson", 1977],
            [2, 9,  "Bernard et Bianca", 1977],
            [2, 10, "Peter et Elliott", 1977],
            [2, 11, "Carte Extra 1", -1],
            [2, 12, "Carte Extra 2", -1],
            [2, 13, "Carte Extra 3", -1],
            [3, 2,  "Rox et Rouky", 1981],
            [3, 3,  "Basil, détective privé", 1986],
            [3, 4,  "Qui veut la peau de Roger Rabbit ?", 1988],
            [3, 5,  "Olivier et compagnie", 1988],
            [3, 6,  "La petite sirène", 1989],
            [3, 7,  "Picsou", 1990],
            [3, 8,  "La belle et la bête", 1991],
            [3, 9,  "Aladdin", 1992],
            [3, 10, "Le roi lion", 1994],
            [3, 11, "Carte Extra 1", -1],
            [3, 12, "Carte Extra 2", -1],
            [3, 13, "Carte Extra 3", -1],
            [4, 2,  "Pocahontas", 1995],
            [4, 3,  "Toy Story", 1995],
            [4, 4,  "Le bossu de Notre-Dame", 1996],
            [4, 5,  "Mulan", 1998],
            [4, 6,  "Tarzan", 1999],
            [4, 7,  "Kuzco", 2000],
            [4, 8,  "Monstres et Compagnie", 2001],
            [4, 9,  "Lilo et Stitch", 2002],
            [4, 10, "Le monde de Némo", 2003],
            [4, 11, "Carte Extra 1", -1],
            [4, 12, "Carte Extra 2", -1],
            [4, 13, "Carte Extra 3", -1],
            [5, 2,  "Cars", 2006],
            [5, 3,  "Ratatouille", 2007],
            [5, 4,  "Wall-E", 2007],
            [5, 5,  "Là-haut", 2009],
            [5, 6,  "Raiponce", 2010],
            [5, 7,  "Le monde de Ralph", 2012],
            [5, 8,  "La reine des neiges", 2013],
            [5, 9,  "Les nouveaux héros", 2014],
            [5, 10, "Vice Versa", 2015],
            [5, 11, "Carte Extra 1", -1],
            [5, 12, "Carte Extra 2", -1],
            [5, 13, "Carte Extra 3", -1],
        ];

        foreach ($cards as $card) {
            $cardRow = new Carte();
            $cardRow->setCategorie($card[0]);
            $cardRow->setValeur($card[1]);
            $cardRow->setNom($card[2]);
            $cardRow->setAnnee($card[3]);

            $manager->persist($cardRow);
        }

        $manager->flush();
    }
}
