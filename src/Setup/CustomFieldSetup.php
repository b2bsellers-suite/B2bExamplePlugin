<?php

namespace B2bExamplePlugin\Setup;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\CustomField\Aggregate\CustomFieldSet\CustomFieldSetEntity;
use Shopware\Core\System\CustomField\CustomFieldTypes;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CustomFieldSetup
{
    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var Context
     */
    private $context;
    /**
     * @var EntityRepositoryInterface
     */
    private $customFieldSetRepository;
    /**
     * @var EntityRepositoryInterface
     */
    private $customFieldRepository;
    /**
     * @var EntityRepositoryInterface
     */
    private $customFieldSetRelationRepository;

    public function __construct(ContainerInterface $container, Context $context)
    {
        $this->container = $container;
        $this->context = $context;
        $this->customFieldSetRepository = $container->get('custom_field_set.repository');
        $this->customFieldRepository = $container->get('custom_field.repository');
        $this->customFieldSetRelationRepository = $container->get('custom_field_set_relation.repository');
    }

    public function install(){

        $exampleDevicesSetId = $this->getCustomFieldSetId('b2b_example_devices_set');

        $this->customFieldSetRepository->upsert([
            [
                'id' => $exampleDevicesSetId,
                'name' => 'b2b_example_devices_set',
                'config' => [
                    'label'=> [
                        'de-DE' => 'B2B - Geräteverwaltung',
                        'en-GB' => 'B2B - Device Management'
                    ]
                ],
                'relations' => [[
                    'id' => $this->getCustomFieldSetRelationId($exampleDevicesSetId, 'b2bsellers_example_devices'),
                    'entityName' => 'b2bsellers_example_devices'
                ]],

                'customFields' => [[
                    'id' => $this->getCustomFieldId('b2b_example_devices_mac'),
                    'name' => 'b2b_example_devices_mac',
                    'type' => CustomFieldTypes::ENTITY,
                    'config' => [
                        'componentName' => 'sw-entity-single-select',
                        'customFieldType' => CustomFieldTypes::TEXT,
                        'entity' => 'b2bsellers_example_devices',
                        'labelProperty' => 'name',
                        'label' => [
                            'de-DE' => 'MAC - Addresse',
                            'en-GB' => 'MAC - Address'
                        ],
                        'placeholder' => [
                            'en-GB' => 'MAC address of the device...',
                            'de-DE' => 'Mac-Addresse des Gerätes...',
                        ],
                    ],
                ]]
            ]
        ], $this->context);
    }

    public function uninstall(){
        $this->uninstallCustomFieldSet('b2b_example_devices_set');
    }

    public function uninstallCustomFieldSet(string $customFieldSetName) {
        $fieldSet = $this->getCustomFieldSet($customFieldSetName);

        if (!empty($fieldSet)) {
            $this->customFieldSetRepository->delete([['id' => $fieldSet->getId()]], $this->context);
        }
    }

    private function getCustomFieldSetId(string $name): string
    {
        $customFieldSet = $this->getCustomFieldSet($name);

        if (empty($customFieldSet)) {
            return Uuid::randomHex();
        }

        return $customFieldSet->getId();
    }

    private function getCustomFieldId(string $name)
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('name', $name));

        $customField = $this->customFieldRepository->search($criteria, $this->context)->first();

        if (empty($customField)) {
            return Uuid::randomHex();
        }

        return $customField->getId();
    }

    private function getCustomFieldSetRelationId(string $fieldSetId, string $entityName)
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('entityName', $entityName));
        $criteria->addFilter(new EqualsFilter('customFieldSetId', $fieldSetId));

        $relation = $this->customFieldSetRelationRepository->search($criteria, $this->context)->first();

        if (empty($relation)) {
            return Uuid::randomHex();
        }

        return $relation->getId();
    }

    private function getCustomFieldSet(string $name): ?CustomFieldSetEntity
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('name', $name));

        return $this->customFieldSetRepository->search($criteria, $this->context)->first();
    }
}