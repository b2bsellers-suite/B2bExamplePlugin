<?php

declare(strict_types=1);

namespace B2bExamplePlugin;

use B2bExamplePlugin\Setup\PlatformMenuInstaller;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\ActivateContext;
use Shopware\Core\Framework\Plugin\Context\DeactivateContext;
use Shopware\Core\Framework\Plugin\Context\InstallContext;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;
use Shopware\Core\Framework\Plugin\Context\UpdateContext;

class B2bExamplePlugin extends Plugin
{
    /**
     * @throws Exception
     */
    public function install(InstallContext $installContext): void
    {
        /** @var Connection $connection */
        $connection = $this->container->get(Connection::class);

        (new PlatformMenuInstaller())->install($connection);
    }

    /**
     * @throws Exception
     */
    public function update(UpdateContext $updateContext): void
    {

        /** @var Connection $connection */
        $connection = $this->container->get(Connection::class);

        (new PlatformMenuInstaller())->install($connection);
    }

    /**
     * @throws Exception
     */
    public function activate(ActivateContext $activateContext): void
    {
        /** @var Connection $connection */
        $connection = $this->container->get(Connection::class);
        (new PlatformMenuInstaller())->install($connection);

    }

    /**
     * @throws Exception
     */
    public function deactivate(DeactivateContext $deactivateContext): void
    {
        $connection = $this->container->get(Connection::class);

        (new PlatformMenuInstaller())->uninstall($connection);
    }

    /**
     * @throws Exception
     */
    public function uninstall(UninstallContext $uninstallContext): void
    {
        /** @var Connection $connection */
        $connection = $this->container->get(Connection::class);

        $connection->executeStatement('SET FOREIGN_KEY_CHECKS = 0');

        $connection->executeStatement('DROP TABLE IF EXISTS `b2b_example_devices`');

        $connection->executeStatement('DROP TABLE IF EXISTS `b2b_example_devices_translation`');

        $connection->executeStatement('SET FOREIGN_KEY_CHECKS = 1');

        (new PlatformMenuInstaller())->uninstall($connection);
    }
}
