<?php

declare(strict_types=1);

namespace Tests\Odiseo\SyliusAvataxPlugin\Behat\Page\Admin\AvataxConfiguration;

use Sylius\Behat\Page\Admin\Crud\IndexPageInterface as BaseIndexPageInterface;

interface IndexPageInterface extends BaseIndexPageInterface
{
    /**
     * @param string $appName
     */
    public function deleteConfiguration(string $appName): void;
}
