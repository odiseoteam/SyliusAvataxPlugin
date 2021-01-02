<?php

declare(strict_types=1);

namespace Tests\Odiseo\SyliusAvataxPlugin\Behat\Page\Admin\AvataxConfiguration;

use Sylius\Behat\Page\Admin\Crud\IndexPage as BaseIndexPage;

final class IndexPage extends BaseIndexPage implements IndexPageInterface
{
    /**
     * @inheritdoc
     */
    public function deleteConfiguration(string $appName): void
    {
        $this->deleteResourceOnPage(['appName' => $appName]);
    }
}
