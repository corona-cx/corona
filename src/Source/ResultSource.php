<?php declare(strict_types=1);

namespace App\Source;

use App\Corona\CoronaInterface;
use App\Model\Result;
use GeoJson\Feature\FeatureCollection;
use Location\Coordinate;
use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class ResultSource
{
    const KEY = 'corona_result';
    const NAMESPACE = 'criticalmass_corona';
    const TTL = 60 * 60;

    protected AbstractAdapter $adapter;
    protected CoronaInterface $corona;

    public function __construct(CoronaInterface $corona)
    {
        $this->corona = $corona;
        $this->adapter = new FilesystemAdapter(self::NAMESPACE, self::TTL);
    }

    public function getResultForCoordinate(Coordinate $target): Result
    {
        return $this->adapter->get(self::KEY, function() use ($target) {
            return $this->corona->getResultForCoordinate($target);
        });
    }
}
