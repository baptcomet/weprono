<?php

namespace Application\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
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
     * @ORM\ManyToOne(targetEntity="Club", inversedBy="games_home", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="equipe_home_id", referencedColumnName="id")
     **/
    protected $equipeHome;

    /**
     * @ORM\ManyToOne(targetEntity="Club", inversedBy="games_away", fetch="EXTRA_LAZY")
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
     * @ORM\Column(name="score_home", type="integer", nullable=true)
     * @var int
     */
    protected $scoreAway;
}
