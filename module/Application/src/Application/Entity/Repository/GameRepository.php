<?php
namespace Application\Entity\Repository;

use Application\Entity\Game;
use Application\Util\Curl;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;

class GameRepository extends EntityRepository
{
    /**
     * @param DateTime $dateTime
     * @return ArrayCollection
     */
    public function findByDate($dateTime)
    {
        $entityManager = $this->getEntityManager();
        $qb = $entityManager->createQueryBuilder();

        $qb->select('g')
            ->from('Application\Entity\Game', 'g')
            ->where('g.date = :date')
            ->setParameter('date', $dateTime);

        return new ArrayCollection($qb->getQuery()->getResult());
    }

    public function apiRetrieveScores()
    {
        $dateNow = new DateTime();
        $curl = new Curl();

        $entityManager = $this->getEntityManager();
        $qb = $entityManager->createQueryBuilder();

        $qb->select('g')
            ->from('Application\Entity\Game', 'g')
            ->where('g.date <= :date')
            ->andWhere('isnull(g.scoreHome)')
            ->andWhere('isnull(g.scoreAway)')
            ->setParameter('date', $dateNow);

        $gamesToUpdate = new ArrayCollection($qb->getQuery()->getResult());
        /** @var Game $game */
        foreach ($gamesToUpdate as $game) {
            $date = $game->getDate()->format('Ymd');
            $result = $curl->get(URL_NBA_API, array('date' => $date));
            // TODO màj le game à partir de $result
            debug($result);
        }
    }

    /**
     * @param int $season
     */
    public function apiRetrieveGamesBySeason($season)
    {
        // TODO tester si game already exists

        $curl = new Curl();
        $entityManager = $this->getEntityManager();
        /** @var ClubRepository $clubRepository */
        $clubRepository = $entityManager->getRepository('Application\Entity\Club');

        // Loop through year days
        $date = ($season-1) . '-10-26';
        $date = ($season-1) . '-12-11';
        // End date
        $end_date = $season . '-06-31';

        while (strtotime($date) <= strtotime($end_date)) {
            $date = date("Ymd", strtotime("+1 day", strtotime($date)));
            $result = $curl->get(URL_NBA_API, array('date' => $date));

            if (isset($result['status'])
                && $result['status'] == Curl::STATUS_OK
                && sizeof($result['event'])) {
                foreach ($result['event'] as $gameData) {
                    $eventId = $gameData['event_id'];
                    $dateTimeStart = DateTime::createFromFormat('Y-m-d', substr($gameData['start_date_time'], 0, 10));
                    $homeTeam = $clubRepository->findOneByApiId($gameData['home_team']['team_id']);
                    $awayTeam = $clubRepository->findOneByApiId($gameData['away_team']['team_id']);
                    $homeScore = $gameData['home_points_scored'] == -1 ? 0 : $gameData['home_points_scored'];
                    $awayScore = $gameData['away_points_scored'] == -1 ? 0 : $gameData['away_points_scored'];

                    if (!$this->gameAlreadyExists($eventId)) {
                        $game = new Game();
                        $game->setSlug($eventId)
                            ->setDate($dateTimeStart)
                            ->setEquipeHome($homeTeam)
                            ->setEquipeAway($awayTeam)
                            ->setScoreHome($homeScore)
                            ->setScoreAway($awayScore);
                        $entityManager->persist($game);
                        $entityManager->flush();
                        debug('DID IT');
                    }
                }
            }
        }

        debug('Finished');
    }

    /**
     * @param string $id
     * @return bool
     */
    public function gameAlreadyExists($id)
    {
        $entityManager = $this->getEntityManager();
        $qb = $entityManager->createQueryBuilder();

        $qb->select('g')
            ->from('Application\Entity\Game', 'g')
            ->where('g.slug = :id')
            ->setParameter('id', $id)
            ->setMaxResults(1);

        $result = $qb->getQuery()->getResult();
        return sizeof($result) > 0;
    }
}
