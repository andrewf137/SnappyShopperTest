<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Properties
 *
 * @ORM\Table(name="properties")
 * @ORM\Entity (repositoryClass="App\Repository\PropertyRepository")
 */
class Property
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="property_identifier", type="string", length=36, nullable=false, options={"fixed"=true})
     */
    private $propertyIdentifier;

    /**
     * @var string|null
     *
     * @ORM\Column(name="country", type="string", length=255, nullable=true)
     */
    private $country;

    /**
     * @var string|null
     *
     * @ORM\Column(name="town", type="string", length=255, nullable=true)
     */
    private $town;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="string", length=2000, nullable=true)
     */
    private $description;

    /**
     * @var float
     *
     * @ORM\Column(name="latitude", type="decimal", precision=10, scale=8, nullable=false)
     */
    private $latitude;

    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="decimal", precision=11, scale=8, nullable=false)
     */
    private $longitude;

    /**
     * @var int
     *
     * @ORM\Column(name="num_bedrooms", type="integer", length=3, nullable=false, options={"default"=0})
     */
    private $numBedrooms = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="num_bathrooms", type="integer", length=3, nullable=false, options={"default"=0})
     */
    private $numBathrooms = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="decimal", precision=15, scale=2, nullable=false, options={"default"=0})
     */
    private $price = 0;

    /**
     * @var string|null
     *
     * @ORM\Column(name="property_type", type="json", nullable=true)
     */
    private $propertyType;

    /**
     * @var PropertyType
     *
     * @ORM\ManyToOne(targetEntity="PropertyType", cascade={"persist", "merge"})
     * @ORM\JoinColumn(name="type", referencedColumnName="name")
     */
    private $type;

    /**
     * @ORM\ManyToMany(targetEntity="Agent", mappedBy="properties")
     */
    private $agents;

    /**
     * @return string
     */
    public function getPropertyIdentifier(): string
    {
        return $this->propertyIdentifier;
    }

    /**
     * @param string $propertyIdentifier
     * @return Property
     */
    public function setPropertyIdentifier(string $propertyIdentifier): Property
    {
        $this->propertyIdentifier = $propertyIdentifier;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @param string|null $country
     * @return Property
     */
    public function setCountry(?string $country): Property
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTown(): ?string
    {
        return $this->town;
    }

    /**
     * @param string|null $town
     * @return Property
     */
    public function setTown(?string $town): Property
    {
        $this->town = $town;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return Property
     */
    public function setDescription(?string $description): Property
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return float
     */
    public function getLatitude(): float
    {
        return $this->latitude;
    }

    /**
     * @param float $latitude
     * @return Property
     */
    public function setLatitude(float $latitude): Property
    {
        $this->latitude = $latitude;
        return $this;
    }

    /**
     * @return float
     */
    public function getLongitude(): float
    {
        return $this->longitude;
    }

    /**
     * @param float $longitude
     * @return Property
     */
    public function setLongitude(float $longitude): Property
    {
        $this->longitude = $longitude;
        return $this;
    }

    /**
     * @return int
     */
    public function getNumBedrooms(): int
    {
        return $this->numBedrooms;
    }

    /**
     * @param int $numBedrooms
     * @return Property
     */
    public function setNumBedrooms(int $numBedrooms): Property
    {
        $this->numBedrooms = $numBedrooms;
        return $this;
    }

    /**
     * @return int
     */
    public function getNumBathrooms(): int
    {
        return $this->numBathrooms;
    }

    /**
     * @param int $numBathrooms
     * @return Property
     */
    public function setNumBathrooms(int $numBathrooms): Property
    {
        $this->numBathrooms = $numBathrooms;
        return $this;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $price
     * @return Property
     */
    public function setPrice(float $price): Property
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPropertyType(): ?string
    {
        return $this->propertyType;
    }

    /**
     * @param string|null $propertyType
     * @return Property
     */
    public function setPropertyType(?string $propertyType): Property
    {
        $this->propertyType = $propertyType;
        return $this;
    }

    /**
     * @return PropertyType
     */
    public function getType(): PropertyType
    {
        return $this->type;
    }

    /**
     * @param PropertyType $propertyType
     * @return Property
     */
    public function setType(PropertyType $propertyType): Property
    {
        $this->type = $propertyType;
        return $this;
    }
}
