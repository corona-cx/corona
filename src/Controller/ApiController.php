<?php declare(strict_types=1);

namespace App\Controller;

use App\Corona\CoronaInterface;
use Location\Coordinate;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @Route("/", name="query")
     */
    public function index(Request $request, CoronaInterface $corona): JsonResponse
    {
        $coordinate = new Coordinate((float) $request->get('latitude'), (float) $request->get('longitude'));

        $result = $corona->getResultForCoordinate($coordinate);

        return $this->json($result);
    }
}
