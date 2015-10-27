<?php 

namespace Application\Controller\Plugin;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Zend\Paginator\Adapter\AdapterInterface;

/*
 * Pagination with doctrine entities
 */
class DoctrinePaginatorAdapter implements AdapterInterface
{
    /**
     * Paginator
     *
     * @var Paginator
     */ 
    protected $paginator = null;

    /**
     * Item count
     *
     * @var integer
     */
    protected $count = null;
 
    /**
     * Constructor.
     *
     * @param Paginator $paginator
     */
    public function __construct(Paginator $paginator)
    {
        $this->paginator = $paginator;
        $this->count = count($paginator);
    }

    /**
     * Returns an array of items for a page.
     *
     * @param  integer $offset Page offset
     * @param  integer $itemCountPerPage Number of items per page
     * @return array
     */
    public function getItems($offset, $itemCountPerPage)
    {
        return $this->paginator->getIterator();
    }

    /**
     * Returns the total number of rows in the array.
     *
     * @return integer
     */
    public function count()
    {
        return $this->count;
    }
} 




?>