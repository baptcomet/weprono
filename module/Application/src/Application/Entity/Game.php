<?php

namespace Application\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Game
 * @package Application\Entity
 *
 * @ORM\Entity(repositoryClass="Application\Entity\Repository\GameRepository");
 * @ORM\Table(name="game")
 */
class Game
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
     * @ORM\ManyToOne(targetEntity="Club", inversedBy="gamesHome", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="equipe_home_id", referencedColumnName="id")
     **/
    protected $equipeHome;

    /**
     * @ORM\ManyToOne(targetEntity="Club", inversedBy="gamesAway", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="equipe_away_id", referencedColumnName="id")
     **/
    protected $equipeAway;

    /**
     * @ORM\Column(name="date", type="date", nullable=false)
     * @var DateTime
     */
    protected $date;

    /**
     * @ORM\Column(name="score_home", type="integer", nullable=true)
     * @var int
     */
    protected $scoreHome;

    /**
     * @ORM\Column(name="score_away", type="integer", nullable=true)
     * @var int
     */
    protected $scoreAway;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Game
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEquipeHome()
    {
        return $this->equipeHome;
    }

    /**
     * @param mixed $equipeHome
     * @return Game
     */
    public function setEquipeHome($equipeHome)
    {
        $this->equipeHome = $equipeHome;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEquipeAway()
    {
        return $this->equipeAway;
    }

    /**
     * @param mixed $equipeAway
     * @return Game
     */
    public function setEquipeAway($equipeAway)
    {
        $this->equipeAway = $equipeAway;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param DateTime $date
     * @return Game
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return int
     */
    public function getScoreHome()
    {
        return $this->scoreHome;
    }

    /**
     * @param int $scoreHome
     * @return Game
     */
    public function setScoreHome($scoreHome)
    {
        $this->scoreHome = $scoreHome;
        return $this;
    }

    /**
     * @return int
     */
    public function getScoreAway()
    {
        return $this->scoreAway;
    }

    /**
     * @param int $scoreAway
     * @return Game
     */
    public function setScoreAway($scoreAway)
    {
        $this->scoreAway = $scoreAway;
        return $this;
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
     * @return Game
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }
}
