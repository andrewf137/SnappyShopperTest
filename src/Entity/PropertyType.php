<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Property types
 *
 * @ORM\Table(name="property_types")
 * @ORM\Entity
 */
class PropertyType
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=32, nullable=false)
     * @ORM\Id
     */
    private $name;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return PropertyType
     */
    public function setName(string $name): PropertyType
    {
        $this->name = $name;
        return $this;
    }
}
