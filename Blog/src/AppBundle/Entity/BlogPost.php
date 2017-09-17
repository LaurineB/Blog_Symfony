<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * BlogPost
 *
 * @ORM\Table(name="blogpost")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BlogPostRepository")
 */
class BlogPost
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /*********************************************/
    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min=2)
     * @Assert\Length(max=100)
     */
    private $title;

    /*********************************************/
    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     * @Assert\NotBlank()
     * @Assert\Length(min=10)
     * @Assert\Length(max=20000)
     */
    private $content;

    /*********************************************/
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="published_at", type="datetime", nullable=true)
     *
     */
    public $published_at;

    /*********************************************/
    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="blogpost")
     */
    private $comments;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    /*********************************************/
    // Url flickr Rouen : https://flic.kr/ps/Tv2Ym

    /**
     * @var string
     *
     * @ORM\Column(name="url_flickr", type="string", length=500 , nullable=true )
     * @Assert\Length(min=10)
     * @Assert\Length(max=500)
     */
    public $url_flickr;

    /*********************************************/
    /**
     * @var string
     *
     * @ORM\Column(name="cover", type="string", length=255 )
     * @Assert\NotBlank()
     * @Assert\Image(maxSize="2M")
     */
    private $cover;

    /*********************************************/
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return \DateTime
     */
    public function getPublishedAt()
    {
        return $this->published_at;
    }

    /**
     * @param \DateTime $published_at
     */
    public function setPublishedAt($published_at)
    {
        $this->published_at = $published_at;
    }

    /**
     * @return mixed
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param mixed $comments
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
    }

    /**
     * @return mixed
     */
    public function getUrlFlickr()
    {
        return $this->url_flickr;
    }

    /**
     * @param mixed $url_flickr
     */
    public function setUrlFlickr($url_flickr)
    {
        $this->url_flickr = $url_flickr;
    }

    /**
     * @return mixed
     */
    public function getCover()
    {
        return $this->cover;
    }

    /**
     * @param mixed $cover
     */
    public function setCover($cover)
    {
        $this->cover = $cover;
    }



}

?>