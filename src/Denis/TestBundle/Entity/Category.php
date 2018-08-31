<?php

namespace Denis\TestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="Denis\TestBundle\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=190, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="mail_1", type="string")
     */
    private $email_1;

    /**
     * @var string
     *
     * @ORM\Column(name="mail_2", type="string")
     */
    private $email_2;

    ///**
    // * @ORM\OneToMany(targetEntity="Members", mappedBy="category")
    // */
    //private $members;


    /**
     * @ORM\OneToMany(targetEntity="Contact", mappedBy="category")
     */
    private $contacts;


    public function __construct()
    {
        $this->members = new ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set email_1.
     *
     * @param string $email_1
     *
     * @return Category
     */
    public function setEmail_1($email_1)
    {
        $this->email_1 = $email_1;

        return $this;
    }

    /**
     * Get email_1.
     *
     * @return string
     */
    public function getEmail_1()
    {
        return $this->email_1;
    }

    /**
     * Set email_2.
     *
     * @param string $email_2
     *
     * @return Category
     */
    public function setEmail_2($email_2)
    {
        $this->email_2 = $email_2;

        return $this;
    }

    /**
     * Get email_2.
     *
     * @return string
     */
    public function getEmail_2()
    {
        return $this->email_2;
    }

    /**
     * @return mixed
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * @return mixed
     */
    public function getContacts()
    {
        return $this->contacts;
    }

}
