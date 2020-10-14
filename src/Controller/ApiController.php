<?php declare(strict_types=1);

namespace App\Controller;

use App\Source\ResultSource;
use JMS\Serializer\SerializerInterface;
use Location\Coordinate;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @Route("/", name="query")
     */
    public function index(Request $request, ResultSource $resultSource, SerializerInterface $serializer): JsonResponse
    {
        // one or both query parameters are missing
        if (!$request->get('latitude') || !$request->get('longitude')) {
            return new JsonResponse(null, Response::HTTP_BAD_REQUEST, [], false);
        }

        try {
            $coordinate = new Coordinate((float) $request->get('latitude'), (float) $request->get('longitude'));
        } catch (\Exception $exception) {
            // latitude and longitude are set, but have invalid values
            return new JsonResponse(null, Response::HTTP_BAD_REQUEST, [], false);
        }

        $result = $resultSource->getResultForCoordinate($coordinate);

        // latitude and longitude were set and valid, but did not deliever any results
        if (!$result) {
            return new JsonResponse(null, Response::HTTP_BAD_REQUEST, [], false);
        }
        
        return new JsonResponse($serializer->serialize($result, 'json'), Response::HTTP_OK, [], true);
    }
}
