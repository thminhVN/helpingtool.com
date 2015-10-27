<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StTagDetail
 *
 * @ORM\Table(name="st_tag_detail", indexes={@ORM\Index(name="tag_id", columns={"tag_id"}), @ORM\Index(name="language_id", columns={"language_id"})})
 * @ORM\Entity
 */
class StTagDetail
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
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;

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
     * @var \Entity\StTag
     *
     * @ORM\ManyToOne(targetEntity="Entity\StTag")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tag_id", referencedColumnName="id")
     * })
     */
    private $tag;



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
     * @return StTagDetail
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
     * Set language
     *
     * @param \Entity\StLanguage $language
     *
     * @return StTagDetail
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
     * Set tag
     *
     * @param \Entity\StTag $tag
     *
     * @return StTagDetail
     */
    public function setTag(\Entity\StTag $tag = null)
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * Get tag
     *
     * @return \Entity\StTag
     */
    public function getTag()
    {
        return $this->tag;
    }
}
