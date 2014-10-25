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

    /**
     * @var array
     *
     * @ORM\Column(name="genra_rankings", type="json_array")
     */
    protected $genraRankings=array();

    /**
     * @var array
     *
     * @ORM\Column(name="user_matches", type="json_array")
     */
    protected $userMatches=array();

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

    /**
     * Set genraRankings
     *
     * @param array $genraRankings
     * @return User
     */
    public function setGenraRankings($genraRankings)
    {
        $this->genraRankings = $genraRankings;

        return $this;
    }

    /**
     * Get genraRankings
     *
     * @return array 
     */
    public function getGenraRankings()
    {
        return $this->genraRankings;
    }

    /**
     * Set genraRanking
     *
     * @param string $genraRanking
     * @param integer $value
     * @return User
     */
    public function setGenraRanking($index,$value){
        $this->genraRankings[$index] = $value;

        return $this;
    }

    /**
     * Get genraRankings
     *
     * @param string $genraRanking     
     * @return integer 
     */
    public function getGenraRanking($index){
        if(isset($this->genraRankings[$index])){
            return $this->genraRankings[$index];
        }else{
            return 0;
        }
    }



    /**
     * Set userMatches
     *
     * @param array $userMatches
     * @return User
     */
    public function setUserMatches($userMatches)
    {
        $this->userMatches = $userMatches;

        return $this;
    }

    /**
     * Get userMatches
     *
     * @return array 
     */
    public function getUserMatches()
    {
        return $this->userMatches;
    }
}
