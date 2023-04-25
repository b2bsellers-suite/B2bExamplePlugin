<?php declare(strict_types=1);

namespace B2bExamplePlugin\Migration;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1680602598b2bExampleDevice extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1680602598;
    }

    /**
     * @throws Exception
     */
    public function update(Connection $connection): void
    {
        $connection->executeStatement('
            CREATE TABLE IF NOT EXISTS `b2b_example_devices` (
                `id` BINARY(16) NOT NULL,
                `custom_fields` JSON NULL,
                `start_at` DATE NULL,
                `end_at` DATE NULL,
                `serial_number` VARCHAR(255) NULL,
                `employee_id` BINARY(16) NULL,
                `created_at` DATETIME(3) NOT NULL,
                `updated_at` DATETIME(3) NULL,
                PRIMARY KEY (`id`),
                CONSTRAINT `json.b2b_example_devices.custom_fields` CHECK (JSON_VALID(`custom_fields`)),
                KEY `fk.b2b_example_devices.employee_id` (`employee_id`),
                CONSTRAINT `fk.b2b_example_devices.employee_id` FOREIGN KEY (`employee_id`) REFERENCES `b2b_employee` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');

        $connection->executeStatement('
            CREATE TABLE IF NOT EXISTS `b2b_example_devices_translation` (
                `name` VARCHAR(255) NOT NULL,
                `description` VARCHAR(255) NOT NULL,
                `created_at` DATETIME(3) NOT NULL,
                `updated_at` DATETIME(3) NULL,
                `b2b_example_devices_id` BINARY(16) NOT NULL,
                `language_id` BINARY(16) NOT NULL,
                PRIMARY KEY (`b2b_example_devices_id`,`language_id`),

                CONSTRAINT `fk.b2b_example_devices_translation.b2b_example_devices_id` FOREIGN KEY (`b2b_example_devices_id`) REFERENCES `b2b_example_devices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `fk.b2b_example_devices_translation.language_id` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE CASCADE on UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
