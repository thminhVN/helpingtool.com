<?php

namespace Repository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * PageRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PageRepository extends CommonRepository
{
    public function getBy($params)
    {
        $qb = $this->_em->createQueryBuilder();
        $detailClass = '\\'.$this->getEntityName()."Detail";
        $qb->select("o, 
            d.title,
            d.description,
            d.seoDescription as seo_description,
            d.image as image
            ")->from($this->getEntityName(), 'o')
            ->join($detailClass, 'd', 'WITH', 'd.page = o.id');
        $qb = $this->filter($qb, $params);
        $paginator = new Paginator($qb->getQuery(), false);
        return $paginator;
    }
    
    public function getPage($id, $lang)
    {
        $qb = $this->_em->createQueryBuilder();
        $detailClass = '\\'.$this->getEntityName()."Detail";
        $qb->select("o, 
            d.title,
            d.description,
            d.seoDescription as seo_description,
            d.image as image
            ")->from($this->getEntityName(), 'o')
        ->join($detailClass, 'd', 'WITH', 'd.page = o.id')
        ->where($qb->expr()->eq('o.id', $id))
        ->andWhere($qb->expr()->eq('d.language', $lang))
        ->andWhere($qb->expr()->eq('o.status', \Application\Config\Config::STATUS_ACTIVE))
        ;

        return $qb->getQuery()->getOneOrNullResult();
    }
}
