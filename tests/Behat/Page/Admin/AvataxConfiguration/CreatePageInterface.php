<?php

declare(strict_types=1);

namespace Tests\Odiseo\SyliusAvataxPlugin\Behat\Page\Admin\AvataxConfiguration;

use Sylius\Behat\Page\Admin\Crud\CreatePageInterface as BaseCreatePageInterface;
use Tests\Odiseo\SyliusAvataxPlugin\Behat\Behaviour\ContainsErrorInterface;

interface CreatePageInterface extends BaseCreatePageInterface, ContainsErrorInterface
{
    /**
     * @param string $appName
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function fillAppName(string $appName): void;

    /**
     * @param string $appVersion
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function fillAppVersion(string $appVersion): void;

    /**
     * @param string $accountId
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function fillAccountId(string $accountId): void;

    /**
     * @param string $licenseKey
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function fillLicenseKey(string $licenseKey): void;

    /**
     * @param string $zone
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function chooseZone(string $zone): void;
}
