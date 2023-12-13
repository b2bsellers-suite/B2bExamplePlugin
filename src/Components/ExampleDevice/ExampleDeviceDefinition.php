<?php

namespace B2bExamplePlugin\Components\ExampleDevice;

use B2bExamplePlugin\Components\ExampleDevice\Aggregate\ExampleDeviceTranslation\ExampleDeviceTranslationDefinition;
use B2bSellersCore\Components\Employee\EmployeeDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\CreatedAtField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\CustomFields;
use Shopware\Core\Framework\DataAbstractionLayer\Field\DateField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\UpdatedAtField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class ExampleDeviceDefinition extends EntityDefinition
{

    public const ENTITY_NAME = 'b2bsellers_example_devices';


    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required(), new ApiAware()),
            (new TranslatedField('name'))->addFlags(new ApiAware()),
            (new TranslationsAssociationField(ExampleDeviceTranslationDefinition::class,
                'b2bsellers_example_devices_id'))->addFlags(new Required(), new ApiAware()),
            (new TranslatedField('description'))->addFlags(new ApiAware()),
            (new TranslationsAssociationField(ExampleDeviceTranslationDefinition::class,
                'b2bsellers_example_devices_id'))->addFlags(new Required(), new ApiAware()),
            (new StringField('serial_number', 'serial_number'))->addFlags(new ApiAware()),
            (new CustomFields('custom_fields', 'customFields'))->addFlags(new ApiAware()),
            (new DateField('start_at', 'start_at'))->addFlags(new ApiAware()),
            (new DateField('end_at', 'end_at'))->addFlags(new ApiAware()),
            (new FkField('employee_id', 'employeeId', EmployeeDefinition::class))->addFlags(new ApiAware()),
            new CreatedAtField(),
            new UpdatedAtField(),
            (new ManyToOneAssociationField('employee', 'employee_id', EmployeeDefinition::class))->addFlags(new ApiAware()),
        ]);
    }
}