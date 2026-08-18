@managing_pages
Feature: Inserting content elements between existing elements on a page
    In order to manage the structure of content on a page
    As an Administrator
    I want to be able to insert content elements between existing ones

    Background:
        Given I am logged in as an administrator
        And the store operates on a single channel in "United States"

    @ui @javascript
    Scenario: Inserting a content element between two existing elements
        When I go to the create page
        And I fill the code with "insert-test-page"
        And I fill the name with "Insert Test Page"
        And I fill the slug with "insert-test-page"
        And I add a heading content element with type "h1" and "My Title" content
        And I add a textarea content element with "My body text" content
        When I insert a textarea content element after the 1st content element
        Then the 1st content element should be a "Heading" element
        And the 2nd content element should be a "Textarea" element
        And the 3rd content element should be a "Textarea" element

    @ui @javascript
    Scenario: Inserting a content element before the first element
        When I go to the create page
        And I fill the code with "insert-test-page"
        And I fill the name with "Insert Test Page"
        And I fill the slug with "insert-test-page"
        And I add a heading content element with type "h1" and "My Title" content
        And I add a textarea content element with "My body text" content
        When I insert a textarea content element before the 1st content element
        Then the 1st content element should be a "Textarea" element
        And the 2nd content element should be a "Heading" element
        And the 3rd content element should be a "Textarea" element
