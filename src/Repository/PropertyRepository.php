<?php
declare(strict_types=1);
/**
 * Description:
 *      Repository class for Property entity.
 *
 * @package App\Repository
 *
 * @copyright 2021 https://github.com/andrewf137
 */

namespace App\Repository;

use App\Entity\Property;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Result;
use Doctrine\DBAL\Statement;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

class PropertyRepository extends ServiceEntityRepository
{
    /**
     * CurrencyRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Property::class);
    }

    /**
     * @param array $property
     */
    public function updateProperty(array $property): void
    {
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();

        $queryBuilder
            ->update(Property::class, 'p')
            ->set('p.country', ':country')
            ->set('p.town', ':town')
            ->set('p.description', ':description')
            ->set('p.latitude', ':latitude')
            ->set('p.longitude', ':longitude')
            ->set('p.numBedrooms', ':numBedrooms')
            ->set('p.numBathrooms', ':numBathrooms')
            ->set('p.price', ':price')
            ->set('p.propertyType', ':propertyType')
            ->set('p.type', ':type')
            ->where('p.propertyIdentifier = :propertyIdentifier')
            ->setParameter('country', $property['country'])
            ->setParameter('town', $property['town'])
            ->setParameter('description', $property['description'])
            ->setParameter('latitude', (float)$property['latitude'])
            ->setParameter('longitude', (float)$property['longitude'])
            ->setParameter('numBedrooms', (int)$property['num_bedrooms'])
            ->setParameter('numBathrooms', (int)$property['num_bathrooms'])
            ->setParameter('price', (float)$property['price'])
            ->setParameter('propertyType', \json_encode($property['property_type'], JSON_UNESCAPED_SLASHES))
            ->setParameter('type', $property['type'])
            ->setParameter('propertyIdentifier', $property['uuid']);

        $queryBuilder->getQuery()->execute();
    }

    /**
     * @return Property|null
     * @throws \Doctrine\DBAL\Exception
     * @throws \Doctrine\DBAL\Driver\Exception
     */
    public function getRandomProperty(): ?int
    {
        /** @var string $sql */
        $sql = "SELECT id FROM properties
                 ORDER BY RAND()
                 LIMIT 1";

        /** @var Statement $statement */
        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        /** @var Result $queryResult */
        $queryResult = $statement->executeQuery();

        /** @var array $result */
        $result = $queryResult->fetchAllAssociative();

        if (empty($result)) {
            return null;
        }

        return (int)$result[0]['id'];
    }

    /**
     * @return array
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function getTopAgents(): array
    {
       /** @var string $sql */
        $sql = "SELECT a.agent1 AS agent
                  FROM (
                      -- Agents with at least two properties in common with at least one agent
                        SELECT ap1.agent as agent1, ap2.agent
                          FROM agent_properties ap1
                          JOIN agent_properties ap2 ON ap2.property_id = ap1.property_id
                         WHERE ap1.agent != ap2.agent
                         GROUP BY ap1.agent, ap2.agent
                         HAVING COUNT(*) > 1
                ) a
                GROUP BY a.agent1
                HAVING COUNT(*) > 1";

        /** @var Statement $statement */
        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        /** @var Result $x */
        $queryResult = $statement->executeQuery();

        return $queryResult->fetchAllAssociative();
    }

    /**
     * @return array
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function getAgentsWithProperties()
    {
       /** @var string $sql */
        $sql = "SELECT ap.agent, group_concat(p.id SEPARATOR ', ') as properties
                  FROM agent_properties ap
                  LEFT JOIN properties p ON p.id = ap.property_id
                 GROUP BY agent";

        /** @var Statement $statement */
        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        /** @var Result $x */
        $queryResult = $statement->executeQuery();

        return $queryResult->fetchAllAssociative();
    }
}