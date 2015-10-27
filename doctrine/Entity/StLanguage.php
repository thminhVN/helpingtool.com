<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StLanguage
 *
 * @ORM\Table(name="st_language")
 * @ORM\Entity(repositoryClass="Repository\LanguageRepository")
 */
class StLanguage
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
     * @ORM\Column(name="code", type="string", length=3, nullable=true)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="locale", type="string", length=5, nullable=true)
     */
    private $locale;

    /**
     * @var string
     *
     * @ORM\Column(name="flag", type="string", length=100, nullable=true)
     */
    private $flag;

    /**
     * @var string
     *
     * @ORM\Column(name="date_format", type="string", length=20, nullable=true)
     */
    private $dateFormat;

    /**
     * @var string
     *
     * @ORM\Column(name="time_format", type="string", length=20, nullable=true)
     */
    private $timeFormat;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="smallint", nullable=true)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="currency", type="string", length=10, nullable=true)
     */
    private $currency;

    /**
     * @var integer
     *
     * @ORM\Column(name="sort", type="smallint", nullable=true)
     */
    private $sort;



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
     * Set code
     *
     * @param string $code
     *
     * @return StLanguage
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return StLanguage
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
     * Set locale
     *
     * @param string $locale
     *
     * @return StLanguage
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Get locale
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Set flag
     *
     * @param string $flag
     *
     * @return StLanguage
     */
    public function setFlag($flag)
    {
        $this->flag = $flag;

        return $this;
    }

    /**
     * Get flag
     *
     * @return string
     */
    public function getFlag()
    {
        return $this->flag;
    }

    /**
     * Set dateFormat
     *
     * @param string $dateFormat
     *
     * @return StLanguage
     */
    public function setDateFormat($dateFormat)
    {
        $this->dateFormat = $dateFormat;

        return $this;
    }

    /**
     * Get dateFormat
     *
     * @return string
     */
    public function getDateFormat()
    {
        return $this->dateFormat;
    }

    /**
     * Set timeFormat
     *
     * @param string $timeFormat
     *
     * @return StLanguage
     */
    public function setTimeFormat($timeFormat)
    {
        $this->timeFormat = $timeFormat;

        return $this;
    }

    /**
     * Get timeFormat
     *
     * @return string
     */
    public function getTimeFormat()
    {
        return $this->timeFormat;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return StLanguage
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set currency
     *
     * @param string $currency
     *
     * @return StLanguage
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get currency
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set sort
     *
     * @param integer $sort
     *
     * @return StLanguage
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
}
