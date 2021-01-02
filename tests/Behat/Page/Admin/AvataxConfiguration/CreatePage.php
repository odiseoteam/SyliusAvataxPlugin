<?php

declare(strict_types=1);

namespace Tests\Odiseo\SyliusAvataxPlugin\Behat\Page\Admin\AvataxConfiguration;

use Sylius\Behat\Page\Admin\Crud\CreatePage as BaseCreatePage;
use Tests\Odiseo\SyliusAvataxPlugin\Behat\Behaviour\ContainsErrorTrait;

final class CreatePage extends BaseCreatePage implements CreatePageInterface
{
    use ContainsErrorTrait;

    /**
     * @inheritdoc
     */
    public function fillAppName(string $appName): void
    {
        $this->getDocument()->fillField('APP name', $appName);
    }

    /**
     * @inheritdoc
     */
    public function fillAppVersion(string $appVersion): void
    {
        $this->getDocument()->fillField('APP version', $appVersion);
    }

    /**
     * @inheritdoc
     */
    public function fillAccountId(string $accountId): void
    {
        $this->getDocument()->fillField('Account ID', $accountId);
    }

    /**
     * @inheritdoc
     */
    public function fillLicenseKey(string $licenseKey): void
    {
        $this->getDocument()->fillField('License key', $licenseKey);
    }

    /**
     * @inheritdoc
     */
    public function chooseZone(string $zone): void
    {
        $this->getDocument()->selectFieldOption('Zone', $zone);
    }
}
