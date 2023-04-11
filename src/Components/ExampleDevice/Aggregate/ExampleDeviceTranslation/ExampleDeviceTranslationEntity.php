<?php

namespace B2bExamplePlugin\Components\ExampleDevice\Aggregate\ExampleDeviceTranslation;

use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;
use Shopware\Core\Framework\DataAbstractionLayer\TranslationEntity;

class ExampleDeviceTranslationEntity extends TranslationEntity
{
    use EntityIdTrait;

    protected string $name;
    protected string $description;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }
}