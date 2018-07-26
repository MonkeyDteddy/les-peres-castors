<?php

namespace PlatformBundle\Repository;

/**
 * CategoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CategoryRepository extends \Doctrine\ORM\EntityRepository
{
    public function getStories()
    {
        $query = $this->createQueryBuilder('s')
            ->leftJoin('s.imageStory', 'i')
            ->addSelect('i')
            ->leftJoin('s.category', 'c')
            ->addSelect('c')
            ->orderBy('s.date', 'DESC')
            ->getQuery()
            ->getResult();

        return $query;
    }
}