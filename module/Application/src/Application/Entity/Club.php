<?php

namespace Application\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Club
 * @package Application\Entity
 *
 * @ORM\Entity(repositoryClass="Application\Entity\Repository\ClubRepository");
 * @ORM\Table(name="club")
 */
class Club
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(name="slug", type="string", nullable=false)
     * @var string
     */
    protected $slug;

    /**
     * @ORM\OneToMany(targetEntity="Game", mappedBy="equipeHome", fetch="EXTRA_LAZY")
     * @var ArrayCollection
     **/
    protected $gamesHome;

    /**
     * @ORM\OneToMany(targetEntity="Game", mappedBy="equipeAway", fetch="EXTRA_LAZY")
     * @var ArrayCollection
     **/
    protected $gamesAway;

    /**
     * @ORM\Column(name="city", type="string", nullable=false)
     * @var string
     */
    protected $city;

    /**
     * @ORM\Column(name="name", type="string", nullable=false)
     * @var string
     */
    protected $name;

    /**
     * @ORM\Column(name="short_name", type="string", nullable=false)
     * @var string
     */
    protected $shortName;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getGamesHome()
    {
        return $this->gamesHome;
    }

    /**
     * @param mixed $gamesHome
     */
    public function setGamesHome($gamesHome)
    {
        $this->gamesHome = $gamesHome;
    }

    /**
     * @return ArrayCollection
     */
    public function getGamesAway()
    {
        return $this->gamesAway;
    }

    /**
     * @param ArrayCollection $gamesAway
     */
    public function setGamesAway($gamesAway)
    {
        $this->gamesAway = $gamesAway;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return string
     */
    public function getShortName()
    {
        return $this->shortName;
    }

    /**
     * @param string $shortName
     */
    public function setShortName($shortName)
    {
        $this->shortName = $shortName;
    }
}
