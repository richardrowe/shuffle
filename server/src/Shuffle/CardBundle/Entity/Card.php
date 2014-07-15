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
     * @ORM\Column(name="front", type="string", length=255)
     */
    private $front;

    /**
     * @var string
     *
     * @ORM\Column(name="back", type="string", length=255)
     */
    private $back;

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

    /**
     * Set front
     *
     * @param string $front
     * @return Card
     */
    public function setFront($front)
    {
        $this->front = $front;

        return $this;
    }

    /**
     * Get front
     *
     * @return string 
     */
    public function getFront()
    {
        return $this->front;
    }

    /**
     * Set back
     *
     * @param string $back
     * @return Card
     */
    public function setBack($back)
    {
        $this->back = $back;

        return $this;
    }

    /**
     * Get back
     *
     * @return string 
     */
    public function getBack()
    {
        return $this->back;
    }
}
