<?php declare(strict_types=1);

namespace App\Controller;

use App\Corona\CoronaInterface;
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
    public function index(Request $request, CoronaInterface $corona, SerializerInterface $serializer): JsonResponse
    {
        $coordinate = new Coordinate((float) $request->get('latitude'), (float) $request->get('longitude'));

        $result = $corona->getResultForCoordinate($coordinate);

        return new JsonResponse($serializer->serialize($result, 'json'), Response::HTTP_OK, [], true);
    }
}
