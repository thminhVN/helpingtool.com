<?php

namespace Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
/**
 * CommonRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CommonRepository extends EntityRepository
{
    public function filter(\Doctrine\ORM\QueryBuilder $qb, $params)
    {
        $entityName = $this->getEntityName();
        $detailClass = $this->getEntityName()."Detail";
        $targetClass = $detailClass;
        if ($params != null) {

            if (!empty($params['paging']['length'])) {
                $qb->setFirstResult($params['paging']['start'])->setMaxResults($params['paging']['length']);
            }
            if (!empty($params['order']['orderby'])) {
                $qb->orderBy($params['order']['orderby'], $params['order']['orderdir']);
            }
            
            foreach ($params as $property => $value) {
                if($this->isEmptyValue($value))
                    continue;
                
                if(property_exists($entityName, $property) || property_exists($detailClass, $property)) {
                    $prefix = 'd';
                    $targetClass = $detailClass;
                    if(property_exists($entityName, $property)) {
                        $prefix = 'o';
                        $targetClass = $entityName;
                    }
                    $ref = new \ReflectionProperty($targetClass, $property);
                    $propertyType = trim(\ST\Text::exx($ref->getDocComment(), '@var ', ' '));
                    if(is_array($value)) {
                        if($propertyType == "\DateTime") {
                            foreach ($value as $key => $item) {
                                if(!empty($item)){
                                    $tmpDate = \Datetime::createFromFormat(_ST_DATE_TIME_FORMAT, $item);
                                    $value[$key] = $qb->expr()->literal($tmpDate->format('Y-m-d H:i:s'));
                                }
                            }
                        }
                        if(!empty($value['from']) && !empty($value['to'])) {
                            $qb->andWhere($qb->expr()->between("$prefix.$property", $value['from'], $value['to']));
                        } elseif (!empty($value['from'])) {
                            $qb->andWhere($qb->expr()->gte("$prefix.$property", $value['from']));
                        } elseif (!empty($value['to'])) {
                            $qb->andWhere($qb->expr()->gte("$prefix.$property", $value['to']));
                        } else {
                            $qb->andWhere($qb->expr()->in("$prefix.$property", $value));
                        }
                    } else {
                        if($propertyType == 'string')
                            $qb->andWhere($qb->expr()->like("$prefix.$property", $qb->expr()->literal("%$value%")));
                        else
                            $qb->andWhere($qb->expr()->eq("$prefix.$property", $value));
                    }
                }
                
            }
        }
        return $qb;
    }
    
    public function isEmptyValue($value)
    {
        if(is_array($value))
            foreach ($value as $k => $v) {
                if($v == '')
                    unset($value[$k]);
            }
        return empty($value);
    }
    
}


