@managing_avatax_configurations
Feature: Adding a new avatax configuration
    In order to show different configurations
    As an Administrator
    I want to add a new avatax configuration to the admin

    Background:
        Given I am logged in as an administrator
        And the store operates on a single channel in "United States"
        And the store has zones "North America", "South America" and "Europe"

    @ui
    Scenario: Adding a new avatax configuration
        Given I want to add a new avatax configuration
        When I fill the app name with "Test"
        And I fill the app version with "1.0"
        And I fill the account id with "123456"
        And I fill the license key with "123456"
        And I select the "Europe" as zone
        And I add it
        Then I should be notified that it has been successfully created
        And the avatax configuration "Test" should appear in the admin

    @ui
    Scenario: Trying to add a new avatax configuration with empty fields
        Given I want to add a new avatax configuration
        When I fill the app name with "Test"
        And I add it
        Then I should be notified that the form contains invalid fields

    @ui
    Scenario: Trying to add an avatax configuration with existing app name
        Given there is an existing avatax configuration with "Test" app name
        And I want to add a new avatax configuration
        When I fill the app name with "Test"
        And I add it
        Then I should be notified that there is already an existing avatax configuration with provided app name
