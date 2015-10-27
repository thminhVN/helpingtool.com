<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StPage
 *
 * @ORM\Table(name="st_page", indexes={@ORM\Index(name="creator", columns={"creator"}), @ORM\Index(name="updator", columns={"updator"})})
 * @ORM\Entity(repositoryClass="Repository\PageRepository")
 */
class StPage
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
     * @var \DateTime
     *
     * @ORM\Column(name="datetime_created", type="datetime", nullable=true)
     */
    private $datetimeCreated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datetime_updated", type="datetime", nullable=true)
     */
    private $datetimeUpdated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datetime_published", type="datetime", nullable=true)
     */
    private $datetimePublished;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=true)
     */
    private $status;

    /**
     * @var boolean
     *
     * @ORM\Column(name="priority", type="boolean", nullable=true)
     */
    private $priority;

    /**
     * @var string
     *
     * @ORM\Column(name="layout", type="string", length=50, nullable=true)
     */
    private $layout;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_menu", type="boolean", nullable=true)
     */
    private $isMenu;

    /**
     * @var \Entity\StUser
     *
     * @ORM\ManyToOne(targetEntity="Entity\StUser")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="creator", referencedColumnName="id")
     * })
     */
    private $creator;

    /**
     * @var \Entity\StUser
     *
     * @ORM\ManyToOne(targetEntity="Entity\StUser")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="updator", referencedColumnName="id")
     * })
     */
    private $updator;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Entity\StCategory", inversedBy="page")
     * @ORM\JoinTable(name="st_page_category",
     *   joinColumns={
     *     @ORM\JoinColumn(name="page_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     *   }
     * )
     */
    private $category;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Entity\StTag", inversedBy="page")
     * @ORM\JoinTable(name="st_page_tag",
     *   joinColumns={
     *     @ORM\JoinColumn(name="page_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="tag_id", referencedColumnName="id")
     *   }
     * )
     */
    private $tag;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->category = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tag = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set datetimeCreated
     *
     * @param \DateTime $datetimeCreated
     *
     * @return StPage
     */
    public function setDatetimeCreated($datetimeCreated)
    {
        $this->datetimeCreated = $datetimeCreated;

        return $this;
    }

    /**
     * Get datetimeCreated
     *
     * @return \DateTime
     */
    public function getDatetimeCreated()
    {
        return $this->datetimeCreated;
    }

    /**
     * Set datetimeUpdated
     *
     * @param \DateTime $datetimeUpdated
     *
     * @return StPage
     */
    public function setDatetimeUpdated($datetimeUpdated)
    {
        $this->datetimeUpdated = $datetimeUpdated;

        return $this;
    }

    /**
     * Get datetimeUpdated
     *
     * @return \DateTime
     */
    public function getDatetimeUpdated()
    {
        return $this->datetimeUpdated;
    }

    /**
     * Set datetimePublished
     *
     * @param \DateTime $datetimePublished
     *
     * @return StPage
     */
    public function setDatetimePublished($datetimePublished)
    {
        $this->datetimePublished = $datetimePublished;

        return $this;
    }

    /**
     * Get datetimePublished
     *
     * @return \DateTime
     */
    public function getDatetimePublished()
    {
        return $this->datetimePublished;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return StPage
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
     * Set priority
     *
     * @param boolean $priority
     *
     * @return StPage
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
     * Set layout
     *
     * @param string $layout
     *
     * @return StPage
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;

        return $this;
    }

    /**
     * Get layout
     *
     * @return string
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * Set isMenu
     *
     * @param boolean $isMenu
     *
     * @return StPage
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
     * Set creator
     *
     * @param \Entity\StUser $creator
     *
     * @return StPage
     */
    public function setCreator(\Entity\StUser $creator = null)
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * Get creator
     *
     * @return \Entity\StUser
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * Set updator
     *
     * @param \Entity\StUser $updator
     *
     * @return StPage
     */
    public function setUpdator(\Entity\StUser $updator = null)
    {
        $this->updator = $updator;

        return $this;
    }

    /**
     * Get updator
     *
     * @return \Entity\StUser
     */
    public function getUpdator()
    {
        return $this->updator;
    }

    /**
     * Add category
     *
     * @param \Entity\StCategory $category
     *
     * @return StPage
     */
    public function addCategory(\Entity\StCategory $category)
    {
        $this->category[] = $category;

        return $this;
    }

    /**
     * Remove category
     *
     * @param \Entity\StCategory $category
     */
    public function removeCategory(\Entity\StCategory $category)
    {
        $this->category->removeElement($category);
    }

    /**
     * Get category
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Add tag
     *
     * @param \Entity\StTag $tag
     *
     * @return StPage
     */
    public function addTag(\Entity\StTag $tag)
    {
        $this->tag[] = $tag;

        return $this;
    }

    /**
     * Remove tag
     *
     * @param \Entity\StTag $tag
     */
    public function removeTag(\Entity\StTag $tag)
    {
        $this->tag->removeElement($tag);
    }

    /**
     * Get tag
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTag()
    {
        return $this->tag;
    }
}
