<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StUser
 *
 * @ORM\Table(name="st_user", uniqueConstraints={@ORM\UniqueConstraint(name="username", columns={"username"}), @ORM\UniqueConstraint(name="email", columns={"email"})})
 * @ORM\Entity(repositoryClass="Repository\UserRepository")
 */
class StUser
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
     * @ORM\Column(name="username", type="string", length=30, nullable=false)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="display_name", type="string", length=50, nullable=true)
     */
    private $displayName;

    /**
     * @var string
     *
     * @ORM\Column(name="avatar", type="string", length=255, nullable=true)
     */
    private $avatar;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=32, nullable=true)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="token_change_password", type="string", length=32, nullable=true)
     */
    private $tokenChangePassword;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expired_change_password_time", type="datetime", nullable=true)
     */
    private $expiredChangePasswordTime;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="new_email", type="string", length=100, nullable=true)
     */
    private $newEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="token_change_email", type="string", length=32, nullable=true)
     */
    private $tokenChangeEmail;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expired_change_email_time", type="datetime", nullable=true)
     */
    private $expiredChangeEmailTime;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=100, nullable=true)
     */
    private $role;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=true)
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datetime_created", type="datetime", nullable=true)
     */
    private $datetimeCreated;



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
     * Set username
     *
     * @param string $username
     *
     * @return StUser
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set displayName
     *
     * @param string $displayName
     *
     * @return StUser
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;

        return $this;
    }

    /**
     * Get displayName
     *
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * Set avatar
     *
     * @param string $avatar
     *
     * @return StUser
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return StUser
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set tokenChangePassword
     *
     * @param string $tokenChangePassword
     *
     * @return StUser
     */
    public function setTokenChangePassword($tokenChangePassword)
    {
        $this->tokenChangePassword = $tokenChangePassword;

        return $this;
    }

    /**
     * Get tokenChangePassword
     *
     * @return string
     */
    public function getTokenChangePassword()
    {
        return $this->tokenChangePassword;
    }

    /**
     * Set expiredChangePasswordTime
     *
     * @param \DateTime $expiredChangePasswordTime
     *
     * @return StUser
     */
    public function setExpiredChangePasswordTime($expiredChangePasswordTime)
    {
        $this->expiredChangePasswordTime = $expiredChangePasswordTime;

        return $this;
    }

    /**
     * Get expiredChangePasswordTime
     *
     * @return \DateTime
     */
    public function getExpiredChangePasswordTime()
    {
        return $this->expiredChangePasswordTime;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return StUser
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set newEmail
     *
     * @param string $newEmail
     *
     * @return StUser
     */
    public function setNewEmail($newEmail)
    {
        $this->newEmail = $newEmail;

        return $this;
    }

    /**
     * Get newEmail
     *
     * @return string
     */
    public function getNewEmail()
    {
        return $this->newEmail;
    }

    /**
     * Set tokenChangeEmail
     *
     * @param string $tokenChangeEmail
     *
     * @return StUser
     */
    public function setTokenChangeEmail($tokenChangeEmail)
    {
        $this->tokenChangeEmail = $tokenChangeEmail;

        return $this;
    }

    /**
     * Get tokenChangeEmail
     *
     * @return string
     */
    public function getTokenChangeEmail()
    {
        return $this->tokenChangeEmail;
    }

    /**
     * Set expiredChangeEmailTime
     *
     * @param \DateTime $expiredChangeEmailTime
     *
     * @return StUser
     */
    public function setExpiredChangeEmailTime($expiredChangeEmailTime)
    {
        $this->expiredChangeEmailTime = $expiredChangeEmailTime;

        return $this;
    }

    /**
     * Get expiredChangeEmailTime
     *
     * @return \DateTime
     */
    public function getExpiredChangeEmailTime()
    {
        return $this->expiredChangeEmailTime;
    }

    /**
     * Set role
     *
     * @param string $role
     *
     * @return StUser
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return StUser
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
     * Set datetimeCreated
     *
     * @param \DateTime $datetimeCreated
     *
     * @return StUser
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
}
