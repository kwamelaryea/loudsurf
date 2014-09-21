<?php
namespace JHodges\LoudSurfBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;

use FOS\UserBundle\Entity\User as BaseUser;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="FavSong", mappedBy="user")
     */
    protected $favSongs;

    public function __construct()
    {
        parent::__construct();
        $this->favSongs = new ArrayCollection();
        // your own logic
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
     * Add favSongs
     *
     * @param \JHodges\LoudSurfBundle\Entity\FavSong $favSongs
     * @return User
     */
    public function addFavSong(\JHodges\LoudSurfBundle\Entity\FavSong $favSongs)
    {
        $this->favSongs[] = $favSongs;

        return $this;
    }

    /**
     * Remove favSongs
     *
     * @param \JHodges\LoudSurfBundle\Entity\FavSong $favSongs
     */
    public function removeFavSong(\JHodges\LoudSurfBundle\Entity\FavSong $favSongs)
    {
        $this->favSongs->removeElement($favSongs);
    }

    /**
     * Get favSongs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFavSongs()
    {
        return $this->favSongs;
    }
}
