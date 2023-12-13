<?php

namespace B2bExamplePlugin\Setup;

use B2bSellersCore\Components\B2bPlatform\PlatformMenu\PlatformMenuItemDefinition;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Uuid\Uuid;

class PlatformMenuInstaller
{
    private Connection $connection;

    /**
     * @throws Exception
     */
    public function install(Connection $connection) {
        $this->connection = $connection;

        if ($this->platformMenuItemAlreadyExists($connection)){
            return;
        }

        $englishLanguageId = $this->getLanguageIdByCode('en-GB');
        $germanLanguageId = $this->getLanguageIdByCode('de-DE');
        $danishLanguageId = $this->getLanguageIdByCode('da-DK');
        $polishLanguageId = $this->getLanguageIdByCode('pl-PL');
        $dutchLanguageId = $this->getLanguageIdByCode('nl-NL');

        $createdAt = (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT);
        $b2bExampleDevicesMenuId = Uuid::randomBytes();
        $entryPointCustomerMenuId = Uuid::fromHexToBytes(PlatformMenuItemDefinition::CUSTOMER_MENU_ID);

        $this->insertPlatformMenuItem([
            'id' => $b2bExampleDevicesMenuId,
            'parent_id' => $entryPointCustomerMenuId,
            'active' => 1,
            'type' => 'platform',
            'technical_name' => 'example_devices',
            'module_name' => 'example_devices',
            'deletable' => 0,
            'child_count' => 0,
            'level' => 1,
            'path' => '|' . PlatformMenuItemDefinition::CUSTOMER_MENU_ID . '|',
            'icon_type' => 'icon',
            'icon_name' => 'icons-default-device-desktop',
            'after_platform_menu_item_id' => null,
            'translations' => [
                [
                    'language_id' => $englishLanguageId,
                    'internal_link' => 'example-devices',
                    'name' => 'Device List',
                ],
                [
                    'language_id' => $germanLanguageId,
                    'internal_link' => 'example-devices',
                    'name' => 'Geräte Liste',
                ],
                [
                    'language_id' => $dutchLanguageId,
                    'internal_link' => 'example-devices',
                    'name' => 'Lijst van apparaten',
                ],
                [
                    'language_id' => $danishLanguageId,
                    'internal_link' => 'example-devices',
                    'name' => 'Liste over enheder',
                ],
                [
                    'language_id' => $polishLanguageId,
                    'internal_link' => 'example-devices',
                    'name' => 'Lista urządzeń',
                ]
            ],
            'created_at' => $createdAt
        ]);
    }

    /**
     * @throws Exception
     */
    public function uninstall(Connection $connection): void
    {
        $connection->delete('b2bsellers_platform_menu_item', ['module_name' => 'example_devices']);
    }


    /**
     * @throws Exception
     */
    private function platformMenuItemAlreadyExists(Connection $connection): bool
    {
        $platformMenu = $connection->fetchOne('SELECT id FROM `b2bsellers_platform_menu_item` WHERE `module_name` = :module_name', ['module_name' => 'example_devices']);

        if ($platformMenu) {
            $connection->update(
                'b2bsellers_platform_menu_item',
                ['technical_name' => 'example_devices'],
                ['module_name' => 'example_devices', 'deletable' => 0]
            );
        }

        return $platformMenu;
    }

    /**
     * @throws Exception
     */
    private function getLanguageIdByCode(string $code): ?string
    {
        /** @var string|null $langId */
        $langId = $this->connection->fetchOne('
        SELECT `language`.`id` FROM `language` INNER JOIN `locale` ON `language`.`locale_id` = `locale`.`id` WHERE `code` = :code LIMIT 1
        ', ['code' => $code]);
        if (!$langId) {
            return null;
        }
        return $langId;
    }

    private function insertPlatformMenuItem(array $data): void
    {
        $data['created_at'] = (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT);

        $translations = $data['translations'];
        unset($data['translations']);

        $this->connection->insert('b2bsellers_platform_menu_item', $data);

        foreach ($translations as &$translation) {
            if ($translation['language_id'] === null) {
                continue;
            }

            $translation['b2bsellers_platform_menu_item_id'] = $data['id'];
            $translation['created_at'] = (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT);

            $this->connection->insert('b2bsellers_platform_menu_item_translation', $translation);
        }
    }
}