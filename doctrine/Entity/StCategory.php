<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StCategory
 *
 * @ORM\Table(name="st_category", indexes={@ORM\Index(name="parent", columns={"parent_id"})})
 * @ORM\Entity(repositoryClass="Repository\CategoryRepository")
 */
class StCategory
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=true)
     */
    private $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="sort", type="smallint", nullable=true)
     */
    private $sort;

    /**
     * @var boolean
     *
     * @ORM\Column(name="priority", type="boolean", nullable=true)
     */
    private $priority;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_menu", type="boolean", nullable=true)
     */
    private $isMenu;

    /**
     * @var \Entity\StCategory
     *
     * @ORM\ManyToOne(targetEntity="Entity\StCategory")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     * })
     */
    private $parent;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Entity\StPage", mappedBy="category")
     */
    private $page;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->page = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return StCategory
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set sort
     *
     * @param integer $sort
     *
     * @return StCategory
     */
    public function setSort($sort)
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * Get sort
     *
     * @return integer
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * Set priority
     *
     * @param boolean $priority
     *
     * @return StCategory
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return boolean
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set isMenu
     *
     * @param boolean $isMenu
     *
     * @return StCategory
     */
    public function setIsMenu($isMenu)
    {
        $this->isMenu = $isMenu;

        return $this;
    }

    /**
     * Get isMenu
     *
     * @return boolean
     */
    public function getIsMenu()
    {
        return $this->isMenu;
    }

    /**
     * Set parent
     *
     * @param \Entity\StCategory $parent
     *
     * @return StCategory
     */
    public function setParent(\Entity\StCategory $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Entity\StCategory
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add page
     *
     * @param \Entity\StPage $page
     *
     * @return StCategory
     */
    public function addPage(\Entity\StPage $page)
    {
        $this->page[] = $page;

        return $this;
    }

    /**
     * Remove page
     *
     * @param \Entity\StPage $page
     */
    public function removePage(\Entity\StPage $page)
    {
        $this->page->removeElement($page);
    }

    /**
     * Get page
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPage()
    {
        return $this->page;
    }
}
