@managing_pages
Feature: Duplicating content elements on a page
    In order to reuse content on a page without rebuilding it from scratch
    As an Administrator
    I want to be able to duplicate a content element

    Background:
        Given I am logged in as an administrator
        And the store operates on a single channel in "United States"

    @ui @javascript
    Scenario: Duplicating a content element places the copy directly below it
        When I go to the create page
        And I fill the code with "duplicate-test-page"
        And I fill the name with "Duplicate Test Page"
        And I fill the slug with "duplicate-test-page"
        And I add a textarea content element with "My body text" content
        And I add a heading content element with type "h1" and "My Title" content
        And I duplicate the 1st content element
        Then the 1st content element should be a "Textarea" element
        And the 2nd content element should be a "Textarea" element
        And the 2nd content element should contain "My body text"
        And the 3rd content element should be a "Heading" element

    @ui @javascript
    Scenario: Duplicating the last content element
        When I go to the create page
        And I fill the code with "duplicate-test-page"
        And I fill the name with "Duplicate Test Page"
        And I fill the slug with "duplicate-test-page"
        And I add a heading content element with type "h1" and "My Title" content
        And I add a textarea content element with "My body text" content
        And I duplicate the 2nd content element
        Then the 1st content element should be a "Heading" element
        And the 2nd content element should be a "Textarea" element
        And the 3rd content element should be a "Textarea" element
        And the 3rd content element should contain "My body text"
