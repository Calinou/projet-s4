<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cartes
 *
 * @ORM\Table(name="cartes")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CartesRepository")
 */
class Cartes
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="categorie", type="integer")
     */
    private $categorie;

    /**
     * @var int
     *
     * @ORM\Column(name="valeur", type="integer")
     */
    private $valeur;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="annee", type="integer")
     */
    private $annee;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set categorie
     *
     * @param integer $categorie
     *
     * @return Cartes
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie
     *
     * @return int
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * Set valeur
     *
     * @param integer $valeur
     *
     * @return Cartes
     */
    public function setValeur($valeur)
    {
        $this->valeur = $valeur;

        return $this;
    }

    /**
     * Get valeur
     *
     * @return int
     */
    public function getValeur()
    {
        return $this->valeur;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Cartes
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set annee
     *
     * @param integer $annee
     *
     * @return Cartes
     */
    public function setAnnee($annee)
    {
        $this->annee = $annee;

        return $this;
    }

    /**
     * Get annee
     *
     * @return integer
     */
    public function getAnnee()
    {
        return $this->annee;
    }

    /**
     * Card image filenames differ from the IDs, so an array is filled with them after some processing
     *
     * @return string
     */
    public function getImageFilename()
    {
        $value = $this->getValeur();

        if ($value >= 11) {
            // "Extra" card, replace name, all "Extra" cards have the same image
            return "extra";
        } else if ($value <= 9) {
            // Leading zero is needed
            return "0".$value;
        } else {
            // Nothing special is needed (only valid for the "10" card)
            return  $value;
        }
    }
}
