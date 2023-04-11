<?php

namespace B2bExamplePlugin\Components\ExampleDevice\SalesChannel;

use Shopware\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult;
use Shopware\Core\Framework\Struct\ArrayEntity;
use Shopware\Core\System\SalesChannel\StoreApiResponse;

class ExampleDeviceRouteResponse extends StoreApiResponse
{
    protected $object;

    public function __construct($object)
    {
        if (!$object instanceof EntitySearchResult && !$object instanceof ArrayEntity) {
            throw new \InvalidArgumentException('Invalid object type provided');
        }

        parent::__construct($object);
        $this->object = $object;
    }

    public function getDevice(): EntitySearchResult
    {
        return $this->object;
    }
}