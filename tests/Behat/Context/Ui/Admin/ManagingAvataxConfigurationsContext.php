<?php

declare(strict_types=1);

namespace Tests\Odiseo\SyliusAvataxPlugin\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use FriendsOfBehat\PageObjectExtension\Page\SymfonyPageInterface;
use Odiseo\SyliusAvataxPlugin\Entity\AvataxConfigurationInterface;
use Sylius\Behat\Service\NotificationCheckerInterface;
use Sylius\Behat\Service\Resolver\CurrentPageResolverInterface;
use Tests\Odiseo\SyliusAvataxPlugin\Behat\Page\Admin\AvataxConfiguration\CreatePageInterface;
use Tests\Odiseo\SyliusAvataxPlugin\Behat\Page\Admin\AvataxConfiguration\IndexPageInterface;
use Webmozart\Assert\Assert;

final class ManagingAvataxConfigurationsContext implements Context
{
    /** @var CurrentPageResolverInterface */
    private $currentPageResolver;

    /** @var NotificationCheckerInterface */
    private $notificationChecker;

    /** @var IndexPageInterface */
    private $indexPage;

    /** @var CreatePageInterface */
    private $createPage;

    public function __construct(
        CurrentPageResolverInterface $currentPageResolver,
        NotificationCheckerInterface $notificationChecker,
        IndexPageInterface $indexPage,
        CreatePageInterface $createPage
    ) {
        $this->currentPageResolver = $currentPageResolver;
        $this->notificationChecker = $notificationChecker;
        $this->indexPage = $indexPage;
        $this->createPage = $createPage;
    }

    /**
     * @Given I want to add a new avatax configuration
     * @throws \FriendsOfBehat\PageObjectExtension\Page\UnexpectedPageException
     */
    public function iWantToAddNewConfiguration(): void
    {
        $this->createPage->open(); // This method will send request.
    }

    /**
     * @When I fill the app name with :appName
     * @When I rename the app name with :appName
     * @param string $appName
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function iFillTheAppNameWith(string $appName): void
    {
        $this->createPage->fillAppName($appName);
    }

    /**
     * @When I fill the app version with :appVersion
     * @When I rename the app version with :appVersion
     * @param string $appVersion
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function iFillTheAppVersionWith(string $appVersion): void
    {
        $this->createPage->fillAppVersion($appVersion);
    }

    /**
     * @When I fill the account id with :accountId
     * @When I rename the account id with :accountId
     * @param string $accountId
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function iFillTheAccountIdWith(string $accountId): void
    {
        $this->createPage->fillAccountId($accountId);
    }

    /**
     * @When I fill the license key with :licenseKey
     * @When I rename the license key with :licenseKey
     * @param string $licenseKey
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function iFillTheLicenseKeyWith(string $licenseKey): void
    {
        $this->createPage->fillLicenseKey($licenseKey);
    }

    /**
     * @When I select the :zone as zone
     * @param string $zone
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function iSelectTheZone(string $zone)
    {
        $this->createPage->chooseZone($zone);
    }

    /**
     * @When I add it
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function iAddIt(): void
    {
        $this->createPage->create();
    }

    /**
     * @Then /^the (avatax configuration "([^"]+)") should appear in the admin/
     * @param AvataxConfigurationInterface $configuration
     * @throws \FriendsOfBehat\PageObjectExtension\Page\UnexpectedPageException
     */
    public function configurationShouldAppearInTheAdmin(AvataxConfigurationInterface $configuration): void
    {
        $this->indexPage->open();

        //Webmozart assert library.
        Assert::true(
            $this->indexPage->isSingleResourceOnPage(['appName' => $configuration->getAppName()]),
            sprintf('Avatax configuration %s should exist but it does not', $configuration->getAppName())
        );
    }

    /**
     * @Then I should be notified that the form contains invalid fields
     */
    public function iShouldBeNotifiedThatTheFormContainsInvalidFields(): void
    {
        Assert::true($this->resolveCurrentPage()->containsError(),
            sprintf('The form should be notified you that the form contains invalid field but it does not')
        );
    }

    /**
     * @Then I should be notified that there is already an existing avatax configuration with provided app name
     */
    public function iShouldBeNotifiedThatThereIsAlreadyAnExistingConfigurationWithSlug(): void
    {
        Assert::true($this->resolveCurrentPage()->containsErrorWithMessage(
            'There is an existing configuration with this APP name.',
            false
        ));
    }

    /**
     * @return CreatePageInterface|SymfonyPageInterface
     */
    private function resolveCurrentPage(): SymfonyPageInterface
    {
        return $this->currentPageResolver->getCurrentPageWithForm([
            $this->indexPage,
            $this->createPage
        ]);
    }
}
