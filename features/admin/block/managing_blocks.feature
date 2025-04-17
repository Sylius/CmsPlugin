@managing_blocks
Feature: Managing cms blocks
    In order to present dynamic content in my store
    As an Administrator
    I want to be able to manage existing blocks

    Background:
        Given the store operates on a single channel in "United States"
        And I am logged in as an administrator

    @ui
    Scenario: Deleting block
        Given there is a block in the store
        When I go to the blocks page
        And I delete this block
        Then I should be notified that the block has been deleted
        And I should see empty list of blocks

    @ui
    Scenario: Seeing disabled code field while editing a block
        Given there is a block in the store
        When I want to edit this block
        Then the code field should be disabled

    @ui
    Scenario: Updating block
        Given there is a block "Store phone number"
        When I want to edit this block
        And I update it
        Then I should be notified that the block has been successfully updated

    @ui
    Scenario: Updating block textarea content element
        Given there is a block "Store phone number" with "Textarea" content element
        When I want to edit this block
        And I change textarea content element value to "New content"
        And I update it
        Then I should be notified that the block has been successfully updated
        And I should see a "Textarea" element with "New content" content

    @ui @javascript
    Scenario: Deleting content element in block
        Given there is a block "Store phone number" with "Textarea" content element
        When I want to edit this block
        And I delete the "Textarea" content element
        And I update it
        Then I should be notified that the block has been successfully updated
        And I should not see "Textarea" content element in the Content elements section

    @ui
    Scenario: Disabling block
        Given there is a block "Sylius_quote"
        When I want to edit this block
        And I disable it
        And I update it
        Then I should be notified that the block has been successfully updated
        And this block should be disabled
