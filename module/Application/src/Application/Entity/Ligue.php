<?php

namespace Application\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Ligue
 * @package Application\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="ligue")
 */
class Ligue
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(name="nom", type="string", length=50)
     * @var String
     */
    protected $nom;

    /**
     * @ORM\Column(name="image", type="string", length=128, nullable=true)
     * @var null|string
     */
    protected $image;

    /**
     * @ORM\Column(name="date_creation", type="date", nullable=false)
     * @var DateTime
     */
    protected $dateCreation;

    /**
     * @ORM\Column(name="date_debut", type="date", nullable=true)
     * @var DateTime
     */
    protected $dateDebut;

    /**
     * @ORM\Column(name="date_fin", type="date", nullable=true)
     * @var DateTime
     */
    protected $dateFin;

    /**
     * @ORM\ManyToOne(targetEntity="Utilisateur", inversedBy="liguesCrees")
     * @ORM\JoinColumn(name="createur_id", referencedColumnName="id")
     **/
    protected $createur;

    /**
     * @ORM\ManyToMany(targetEntity="Utilisateur", inversedBy="ligues", fetch="EXTRA_LAZY", cascade={"persist"})
     * @ORM\JoinTable(
     *     name="ligue_joueur",
     *     joinColumns={@ORM\JoinColumn(name="ligue_id", referencedColumnName="id", onDelete="CASCADE")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="joueur_id", referencedColumnName="id", onDelete="CASCADE")}
     * )
     * @var ArrayCollection
     **/
    protected $joueurs;

    /**
     * @ORM\Column(name="disabled", type="boolean", options={"default" = 0})
     * @var bool
     */
    protected $disabled;

    public function __construct()
    {
        $this->dateCreation = new DateTime();
        $this->dateDebut = new DateTime();
        $this->dateFin = DateTime::createFromFormat('Y-m-d', Game::SEASON_END_DATE);
        $this->joueurs = new ArrayCollection();
        $this->disabled = false;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Ligue $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return String
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param String $nom
     * @return Ligue $this
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDisabled()
    {
        return $this->disabled;
    }

    /**
     * @return string
     */
    public function getDateCreation()
    {
        return is_null($this->dateCreation) ? '' : $this->dateCreation->format('d/m/Y');
    }

    /**
     * @return DateTime
     */
    public function getDateTimeCreation()
    {
        return $this->dateCreation;
    }

    /**
     * @param DateTime $dateCreation
     * @return Ligue $this
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation ? $dateCreation : null;
        return $this;
    }

    /**
     * @return string
     */
    public function getDateDebut()
    {
        return is_null($this->dateDebut) ? '' : $this->dateDebut->format('d/m/Y');
    }

    /**
     * @return DateTime
     */
    public function getDateTimeDebut()
    {
        return $this->dateDebut;
    }

    /**
     * @param DateTime $dateDebut
     * @return Ligue $this
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut ? $dateDebut : null;
        return $this;
    }

    /**
     * @return string
     */
    public function getDateFin()
    {
        return is_null($this->dateFin) ? '' : $this->dateFin->format('d/m/Y');
    }

    /**
     * @return DateTime
     */
    public function getDateTimeFin()
    {
        return $this->dateFin;
    }

    /**
     * @param DateTime $dateFin
     * @return Ligue $this
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin ? $dateFin : null;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param null|string $image
     * @return Ligue $this
     */
    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @return Utilisateur
     */
    public function getCreateur()
    {
        return $this->createur;
    }

    /**
     * @param Utilisateur $createur
     * @return Ligue $this
     */
    public function setCreateur($createur)
    {
        $this->createur = $createur;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getJoueurs()
    {
        return $this->joueurs;
    }

    /**
     * @param ArrayCollection $joueurs
     * @return Ligue $this
     */
    public function setJoueurs($joueurs)
    {
        $this->joueurs = $joueurs;
        return $this;
    }

    /**
     * @param Utilisateur $joueur
     * @return Ligue $this
     */
    public function addJoueur($joueur)
    {
        if (!$this->joueurs->contains($joueur)) {
            $this->joueurs->add($joueur);
        }
        return $this;
    }

    /**
     * @param ArrayCollection $joueurs
     * @return Ligue $this
     */
    public function addJoueurs($joueurs)
    {
        foreach ($joueurs as $joueur) {
            $this->joueurs->add($joueur);
        }
        return $this;
    }

    /**
     * @param Utilisateur $joueur
     * @return Ligue $this
     */
    public function removeJoueur($joueur)
    {
        $this->joueurs->remove($joueur);
        return $this;
    }

    /**
     * @param ArrayCollection $joueurs
     * @return Ligue $this
     */
    public function removeJoueurs($joueurs)
    {
        foreach ($joueurs as $joueur) {
            $this->joueurs->remove($joueur);
        }
        return $this;
    }

    /**
     * Get disabled
     *
     * @return boolean
     */
    public function getDisabled()
    {
        return $this->disabled;
    }

    /**
     * @param bool $disabled
     * @return Ligue $this
     */
    public function setDisabled($disabled)
    {
        $this->disabled = $disabled;
        return $this;
    }
}
