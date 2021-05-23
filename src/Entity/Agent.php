<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Agents
 *
 * @ORM\Table(name="agents")
 * @ORM\Entity
 */
class Agent
{
    /**
     * Customer constructor.
     */
    public function __construct()
    {
        $this->properties = new ArrayCollection();
    }

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=32, nullable=false)
     * @ORM\Id
     */
    private $name;

    /**
     *
     * @ORM\ManyToMany(targetEntity="Property", inversedBy="agents", cascade={"persist","remove"})
     * @ORM\JoinTable(
     *     name="agent_properties",
     *     joinColumns={@ORM\JoinColumn(name="agent", referencedColumnName="name", onDelete="CASCADE")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="property_id", referencedColumnName="id", onDelete="CASCADE" )}
     *)
     */
    protected $properties;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Agent
     */
    public function setName(string $name): Agent
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param Property $property
     * @return Agent
     */
    public function addProperty(Property $property): Agent
    {
        if (!$this->getProperties()->contains($property)) {
            $this->getProperties()->add($property);
        }
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getProperties(): ArrayCollection
    {
        return $this->properties;
    }

    /**
     * @param Property $property
     * @return bool
     */
    public function hasProperty(Property $property): bool
    {
        return $this->properties->contains($property);
    }

    /**
     * @param Property $property
     * @return Agent
     */
    public function removeProperty(Property $property): Agent
    {
        if ($this->getProperties()->contains($property)) {
            $this->getProperties()->removeElement($property);
        }

        return $this;
    }
}
