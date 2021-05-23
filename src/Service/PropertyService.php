<?php
declare(strict_types=1);

/**
 * Description:
 *      Properties Service
 *
 * @package App\Service
 *
 * @copyright 2021 https://github.com/andrewf137
 *
 */

namespace App\Service;

use App\Entity\Property;
use App\Entity\PropertyType;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class PropertyService
{
    public const PROPERTY_API = 'https://trial.craig.mtcserver15.com/api/properties';

    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Invoke basic API url
     *
     * @return array
     */
    public function getPropertiesOverviewData(): array
    {
        return json_decode(file_get_contents(self::PROPERTY_API), TRUE);
    }

    /**
     * Invoke an url
     *
     * @param string $url
     * @return array
     */
    public function getPropertyDataByUrl(string $url): array
    {
        return json_decode(file_get_contents($url), TRUE);
    }

    /**
     * Recursive function to save properties of a given page and invoke next page url
     *
     * @param array $properties
     * @param string $nextPageUrl
     * @param string $lastPageUrl
     * @param int $pageFrom
     * @param int $pageTo
     */
    public function saveProperties(
        array $properties,
        string $nextPageUrl,
        string $lastPageUrl,
        int $pageFrom,
        int $pageTo): void
    {

        /** @var int $i */
        $i = 1;
        /** @var array $property */
        foreach ($properties as $property) {
            $this->saveProperty($property);

            if ($i % 10 === 0) {
                $this->entityManager->flush();
            }
            $i++;
        }

        $this->entityManager->flush();

        if ($nextPageUrl !== $lastPageUrl && $pageFrom !== $pageTo) {
            $nextPage = $this->getPropertyDataByUrl($nextPageUrl);
            $this->saveProperties(
                $nextPage['data'],
                $nextPage['next_page_url'],
                $lastPageUrl,
                $nextPage['current_page'],
                $pageTo
            );
        }
    }

    /**
     * @param array $propertyData
     */
    private function saveProperty(array $propertyData): void
    {
        /** @var PropertyRepository $propertyRepository */
        $propertyRepository = $this->entityManager->getRepository(Property::class);

        /** @var Property|null $propertyEntity */
        $propertyEntity = $propertyRepository->findOneBy(['propertyIdentifier' => $propertyData['uuid']]);

        if ($propertyEntity instanceof Property){
            $propertyRepository->updateProperty($propertyData);
        } else {
            $property = new Property();
            $property->setPropertyIdentifier($propertyData['uuid']);
            $property->setCountry($propertyData['country']);
            $property->setTown($propertyData['town']);
            $property->setDescription($propertyData['description']);
            $property->setLatitude((float)$propertyData['latitude']);
            $property->setLongitude((float)$propertyData['longitude']);
            $property->setNumBedrooms((int)$propertyData['num_bedrooms']);
            $property->setNumBathrooms((int)$propertyData['num_bathrooms']);
            $property->setPrice((float)$propertyData['price']);
            $property->setPropertyType(\json_encode($propertyData['property_type'], JSON_UNESCAPED_SLASHES));

            /** @var ObjectRepository $propertyTypeRepository */
            $propertyTypeRepository = $this->entityManager->getRepository(PropertyType::class);

            /** @var PropertyType|null $propertyType */
            $propertyType = $propertyTypeRepository->findOneBy(['name' => $propertyData['type']]);
            if ($propertyType instanceof PropertyType) {
                $property->setType($propertyType);
            }

            $this->entityManager->persist($property);
        }
    }

    /**
     * Instead of file_get_contents($url) we can use this method to invoke the API
     * Method: POST, PUT, GET etc
     * Data: array("param" => "value") ==> index.php?param=value
     *
     * @param $method
     * @param $url
     * @param false $data
     * @return bool|string
     */
    function CallAPI(string $method, string $url, $data = false)
    {
        /** @var false|resource $curl */
        $curl = curl_init();

        switch ($method) {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);

                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_PUT, 1);
                break;
            default:
                if ($data)
                    /** @var string $url */
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }

        // Optional Authentication:
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, "username:password");

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        /** @var bool|string $result */
        $result = curl_exec($curl);

        curl_close($curl);

        return $result;
    }

    /**
     * @return array
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function getTopAgents(): array
    {
         /** @var PropertyRepository $propertyRepository */
        $propertyRepository = $this->entityManager->getRepository(Property::class);

        return $propertyRepository->getTopAgents();
    }

    /**
     * @return array
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function getAgentsWithProperties(): array
    {
         /** @var PropertyRepository $propertyRepository */
        $propertyRepository = $this->entityManager->getRepository(Property::class);

        return $propertyRepository->getAgentsWithProperties();
    }
}