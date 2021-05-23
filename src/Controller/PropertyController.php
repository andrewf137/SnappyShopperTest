<?php
declare(strict_types=1);

/**
 * Description:
 *      Property Controller
 *
 * @package App\Controller
 *
 * @copyright 2021 https://github.com/andrewf137
 *
 */

namespace App\Controller;

use App\Service\PropertyService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PropertyController extends AbstractController
{
    /**
     * Render index.html
     *
     * @param PropertyService $propertyService
     * @return Response
     * @throws \Exception
     */
    public function index(PropertyService $propertyService): Response
    {
        /** @var array $propertiesData */
        $propertiesData =  $propertyService->getPropertiesOverviewData();

        return $this->render('index.html.twig', [
            'totalNumberOfProperties' => $propertiesData['total'],
            'maxNumberOfPropertiesPerPage' => 100
        ]);
    }

    /**
     * Get overview data of the API
     * Save properties
     * Redirect to index.html
     *
     * @param Request $request
     * @param PropertyService $propertyService
     * @return Response
     */
    public function saveProperties(Request $request, PropertyService $propertyService): Response
    {
        /** @var array $parameters */
        $parameters = $request->query->all();

        /** @var array $propertiesData */
        $propertiesData = $propertyService->getPropertyDataByUrl(
            \sprintf(
                PropertyService::PROPERTY_API . '?page[size]=%s&page[number]=%s',
                $parameters['per-page'],
                $parameters['page-from']
            )
        );

        $propertyService->saveProperties(
            $propertiesData['data'],
            $propertiesData['next_page_url'],
            $propertiesData['last_page_url'],
            (int)$parameters['page-from'],
            (int)$parameters['page-to']
        );

        $this->addFlash('message', 'Properties successfully saved!');
        return $this->redirectToRoute('app');
    }

    public function getPropertyDataByUrl(string $url): array
    {
        return json_decode(file_get_contents($url), TRUE);
    }

    /**
     * @param PropertyService $propertyService
     * @return Response
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function getTopAgents(PropertyService $propertyService): Response
    {
        /** @var array $topAgents */
        $topAgents = $propertyService->getTopAgents();

        /** @var array $agentsAndProperties */
        $agentsAndProperties = $propertyService->getAgentsWithProperties();

        $this->addFlash('topAgents', $topAgents);
        $this->addFlash('agentsAndProperties', $agentsAndProperties);

        return $this->redirectToRoute('app');
    }
}