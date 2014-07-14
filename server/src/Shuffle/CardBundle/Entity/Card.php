<?php

namespace Shuffle\CardBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Shuffle\CardBundle\Model\CardInterface;

/**
 * Card
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Card implements CardInterface
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity="Deck", inversedBy="cards")
     * @ORM\JoinColumn(name="deck_id", referencedColumnName="id")
     */
    private $deck;

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
     * @return Card
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
     * Set deck
     *
     * @param \Shuffle\CardBundle\Entity\Deck $deck
     * @return Card
     */
    public function setDeck(\Shuffle\CardBundle\Entity\Deck $deck = null)
    {
        $this->deck = $deck;

        return $this;
    }

    /**
     * Get deck
     *
     * @return \Shuffle\CardBundle\Entity\Deck 
     */
    public function getDeck()
    {
        return $this->deck;
    }
}
