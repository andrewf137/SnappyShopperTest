<?php

namespace App\DataFixtures;

use App\Entity\Agent;
use App\Entity\Property;
use App\Entity\PropertyType;
use App\Repository\PropertyRepository;
use App\Service\PropertyService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AppFixtures extends Fixture implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ObjectManager $manager
     * @throws \Doctrine\DBAL\Exception
     * @throws \Doctrine\DBAL\Driver\Exception
     */
    public function load(ObjectManager $manager)
    {
        /**
         * Set id to 1 after db deletion
         */

        $sql = "ALTER TABLE `properties` AUTO_INCREMENT = 1";

        $stmt = $manager->getConnection()->prepare($sql);
        $stmt->execute();

        /**
         * Add property types
         */

        $propertyType = new PropertyType();
        $propertyType->setName('sale');
        $manager->persist($propertyType);

        $propertyType = new PropertyType();
        $propertyType->setName('rent');
        $manager->persist($propertyType);

        $manager->flush();

        /**
         * Save 7 properties into the database
         */

        /** @var PropertyService $propertyService */
        $propertyService = $this->container->get('app.property_service');

        /** @var array $propertiesData */
        $propertiesData = $propertyService->getPropertyDataByUrl(
            \sprintf(
                PropertyService::PROPERTY_API . '?page[size]=%s&page[number]=%s',
                7,
                1
            )
        );

        $propertyService->saveProperties(
            $propertiesData['data'],
            $propertiesData['next_page_url'],
            $propertiesData['last_page_url'],
            1,
            1
        );

        /**
         * Save 7 agents
         */

        /** @var array $agentNames */
        $agentNames = [
            'Jemimah Barnett',
            'Monica Davis',
            'Lonee Hamilton',
   	        'Roy Brooks',
   	        'Ian Green',
            'Knight Frank',
            'Daniel Cobb'
        ];

        /** @var PropertyRepository $propertyRepository */
        $propertyRepository = $manager->getRepository(Property::class);

        /** @var ObjectRepository $agentRepository */
        $agentRepository = $manager->getRepository(Agent::class);

        /** @var string $name */
        foreach ($agentNames as $name) {
            /** @var Agent|null $agent */
            $agent = $agentRepository->findOneBy(['name' => $name]);

            if (!$agent instanceof Agent) {
                $agent = new Agent();
                $agent->setName($name);

                /**
                 * Add from one to three random properties to each agent
                 */
                for ($i = 1; $i <=3; $i++) {
                    /** @var int|null $propertyId */
                    $propertyId = $propertyRepository->getRandomProperty();

                    if (null !== $propertyId) {
                        /** @var Property $property */
                        $property = $propertyRepository->find($propertyId);
                        if (!$agent->hasProperty($property)) {
                            $agent->addProperty($property);
                        }
                    }
                }

                $manager->persist($agent);
                $manager->flush();
            }
        }

        $manager->flush();
    }

    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
