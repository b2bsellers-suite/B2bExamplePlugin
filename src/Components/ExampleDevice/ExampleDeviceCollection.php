<?php

namespace B2bExamplePlugin\Components\ExampleDevice;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

class ExampleDeviceCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return ExampleDeviceEntity::class;
    }

}