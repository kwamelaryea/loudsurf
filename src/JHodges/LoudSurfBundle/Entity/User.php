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
     * @ORM\Column(name="genre_rankings", type="json_array")
     */
    protected $genreRankings=array();

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
     * Set genreRankings
     *
     * @param array $genreRankings
     * @return User
     */
    public function setGenreRankings($genreRankings)
    {
        $this->genreRankings = $genreRankings;

        return $this;
    }

    /**
     * Get genreRankings
     *
     * @return array 
     */
    public function getGenreRankings()
    {
        return $this->genreRankings;
    }

    /**
     * Set genreRanking
     *
     * @param string $genreRanking
     * @param integer $value
     * @return User
     */
    public function setGenreRanking($index,$value){
        $this->genreRankings[$index] = $value;

        return $this;
    }

    /**
     * Get genreRankings
     *
     * @param string $genreRanking     
     * @return integer 
     */
    public function getGenreRanking($index){
        if(isset($this->genreRankings[$index])){
            return $this->genreRankings[$index];
        }else{
            return 0;
        }
    }

    /**
     * Get genre points
     *
     * @return integer 
     */
    public function getGenrePoints(){
        $points=0;
        foreach($this->genreRankings as $genre=>$score){
            $points+=$score;
        }
        return $points;
    }

}
