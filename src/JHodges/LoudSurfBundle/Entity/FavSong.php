<?php

namespace JHodges\LoudSurfBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FavSong
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class FavSong
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="songID", type="string", length=255)
     */
    private $songID;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="fav_songs")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @var string
     *
     * @ORM\Column(name="data", type="json_array")
     */
    private $data;

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
     * @return FavSong
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
     * Set songID
     *
     * @param string $songID
     * @return FavSong
     */
    public function setSongID($songID)
    {
        $this->songID = $songID;

        return $this;
    }

    /**
     * Get songID
     *
     * @return string 
     */
    public function getSongID()
    {
        return $this->songID;
    }

    /**
     * Set user
     *
     * @param \JHodges\LoudSurfBundle\Entity\User $user
     * @return FavSong
     */
    public function setUser(\JHodges\LoudSurfBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \JHodges\LoudSurfBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set data
     *
     * @param array $data
     * @return FavSong
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data
     *
     * @return array 
     */
    public function getData()
    {
        return $this->data;
    }
}
