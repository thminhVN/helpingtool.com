<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StCategoryDetail
 *
 * @ORM\Table(name="st_category_detail", indexes={@ORM\Index(name="category_id", columns={"category_id"}), @ORM\Index(name="language_id", columns={"language_id"})})
 * @ORM\Entity
 */
class StCategoryDetail
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=255, nullable=true)
     */
    private $description;

    /**
     * @var \Entity\StCategory
     *
     * @ORM\ManyToOne(targetEntity="Entity\StCategory")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     * })
     */
    private $category;

    /**
     * @var \Entity\StLanguage
     *
     * @ORM\ManyToOne(targetEntity="Entity\StLanguage")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="language_id", referencedColumnName="id")
     * })
     */
    private $language;



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
     * Set name
     *
     * @param string $name
     *
     * @return StCategoryDetail
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return StCategoryDetail
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set category
     *
     * @param \Entity\StCategory $category
     *
     * @return StCategoryDetail
     */
    public function setCategory(\Entity\StCategory $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \Entity\StCategory
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set language
     *
     * @param \Entity\StLanguage $language
     *
     * @return StCategoryDetail
     */
    public function setLanguage(\Entity\StLanguage $language = null)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return \Entity\StLanguage
     */
    public function getLanguage()
    {
        return $this->language;
    }
}
