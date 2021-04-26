<?php declare(strict_types=1);

namespace App\Source;

use App\Corona\CoronaInterface;
use App\Entity\Data;
use Location\Coordinate;
use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class ResultSource
{
    const KEY_PREFIX = 'corona_result';
    const NAMESPACE = 'criticalmass_corona';
    const TTL = 60 * 60;

    protected AbstractAdapter $adapter;
    protected CoronaInterface $corona;

    public function __construct(CoronaInterface $corona)
    {
        $this->corona = $corona;
        $this->adapter = new FilesystemAdapter(self::NAMESPACE, self::TTL);
    }

    public function getResultForCoordinate(Coordinate $target): ?Data
    {
        $key = sprintf('%s_%f_%f', self::KEY_PREFIX, $target->getLat(), $target->getLng());

        return $this->adapter->get($key, function() use ($target): ?Data {
            return $this->corona->getResultForCoordinate($target);
        });
    }
}
