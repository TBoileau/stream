<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Question;
use App\UseCase\Chat\QuestionProcessorInterface;
use App\UseCase\Chat\QuestionProviderInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @template ServiceEntityRepository<Question>
 */
final class QuestionRepository extends ServiceEntityRepository implements QuestionProcessorInterface, QuestionProviderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Question::class);
    }

    public function current(): ?Question
    {
        return $this->createQueryBuilder('q')
            ->where('DATE(q.askedAt) = :date')
            ->andWhere('q.answered = false')
            ->setParameter('date', date('Y-m-d'))
            ->orderBy('q.id', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function save(Question $question): void
    {
        $this->getEntityManager()->persist($question);
        $this->getEntityManager()->flush();
    }

    public function next(): void
    {
        $question = $this->current();

        if (!$question->current) {
            $question->current = true;
        } else {
            $question->answered = true;
        }

        $this->getEntityManager()->flush();
    }

    public function previous(): void
    {
        $queryBuilder = $this->createQueryBuilder('q')
            ->where('DATE(q.askedAt) = :date')
            ->setParameter('date', date('Y-m-d'))
            ->orderBy('q.id', 'DESC')
            ->setMaxResults(1);

        /** @var Question|null $question */
        $question = $queryBuilder
            ->andWhere(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->eq('q.answered', true),
                    $queryBuilder->expr()->eq('q.current', true)
                )
            )
            ->getQuery()
            ->getOneOrNullResult();

        if ($question === null) {
            return;
        }

        if ($question->answered) {
            $question->answered = false;
            $this->getEntityManager()->flush();
            return;
        }

        $question->current = false;
        $this->getEntityManager()->flush();
    }
}
