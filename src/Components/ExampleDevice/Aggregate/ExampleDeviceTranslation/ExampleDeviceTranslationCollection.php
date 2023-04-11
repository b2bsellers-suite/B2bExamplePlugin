<?php

namespace B2bExamplePlugin\Components\ExampleDevice\Aggregate\ExampleDeviceTranslation;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

class ExampleDeviceTranslationCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return ExampleDeviceTranslationEntity::class;
    }
}