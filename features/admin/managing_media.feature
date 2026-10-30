@managing_media
Feature: Managing digital assets
    In order to present custom digital assets in my store
    As an Administrator
    I want to be able to manage specific media types

    Background:
        Given the store operates on a single channel in "United States"
        And I am logged in as an administrator

    @ui @javascript
    Scenario: Creating image media
        When I go to the create media page
        And I fill the code with "aston_martin_db9"
        And I fill the name with "Aston Martin DB9"
        And I upload the "aston_martin_db_11.jpg" image
        And I add it
        Then I should be notified that new media has been created

    @ui @javascript
    Scenario: Updating image media
        Given there is an existing media with "aston_martin_db9" code
        When I want to edit this media
        And I fill the name with "Aston Martin DB11"
        And I update it
        Then I should be notified that the media has been successfully updated
