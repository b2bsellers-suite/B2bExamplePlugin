<?php

namespace B2bExamplePlugin\Components\ExampleDevice\Aggregate\ExampleDeviceTranslation;

use B2bExamplePlugin\Components\ExampleDevice\ExampleDeviceDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class ExampleDeviceTranslationDefinition extends EntityTranslationDefinition
{
    public const ENTITY_NAME = 'b2b_example_devices_translation';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getEntityClass(): string
    {
        return ExampleDeviceTranslationEntity::class;
    }

    public function getCollectionClass(): string
    {
        return ExampleDeviceTranslationCollection::class;
    }

    public function getParentDefinitionClass(): string
    {
        return ExampleDeviceDefinition::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new StringField('name', 'name'))->addFlags(new Required()),
            (new StringField('description', 'description'))->addFlags(new Required())
        ]);
    }
}