<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StPageDetail
 *
 * @ORM\Table(name="st_page_detail", indexes={@ORM\Index(name="page_id", columns={"page_id"}), @ORM\Index(name="language_id", columns={"language_id"})})
 * @ORM\Entity
 */
class StPageDetail
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
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="seo_description", type="string", length=160, nullable=true)
     */
    private $seoDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    private $image;

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
     * @var \Entity\StPage
     *
     * @ORM\ManyToOne(targetEntity="Entity\StPage")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="page_id", referencedColumnName="id")
     * })
     */
    private $page;



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
     * Set title
     *
     * @param string $title
     *
     * @return StPageDetail
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return StPageDetail
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
     * Set seoDescription
     *
     * @param string $seoDescription
     *
     * @return StPageDetail
     */
    public function setSeoDescription($seoDescription)
    {
        $this->seoDescription = $seoDescription;

        return $this;
    }

    /**
     * Get seoDescription
     *
     * @return string
     */
    public function getSeoDescription()
    {
        return $this->seoDescription;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return StPageDetail
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set language
     *
     * @param \Entity\StLanguage $language
     *
     * @return StPageDetail
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

    /**
     * Set page
     *
     * @param \Entity\StPage $page
     *
     * @return StPageDetail
     */
    public function setPage(\Entity\StPage $page = null)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get page
     *
     * @return \Entity\StPage
     */
    public function getPage()
    {
        return $this->page;
    }
}
