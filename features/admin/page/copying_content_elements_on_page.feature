@managing_pages
Feature: Copying and pasting content elements on a page
    In order to duplicate content on a page without rebuilding it from scratch
    As an Administrator
    I want to be able to copy a content element and paste it elsewhere

    Background:
        Given I am logged in as an administrator
        And the store operates on a single channel in "United States"

    @ui @javascript
    Scenario: Pasting is not possible before copying a content element
        When I go to the create page
        And I fill the code with "copy-test-page"
        And I fill the name with "Copy Test Page"
        And I fill the slug with "copy-test-page"
        And I add a textarea content element with "My body text" content
        Then the paste element buttons should be disabled
        When I copy the 1st content element
        Then the paste element buttons should not be disabled

    @ui @javascript
    Scenario: Pasting a copied content element after another element
        When I go to the create page
        And I fill the code with "copy-test-page"
        And I fill the name with "Copy Test Page"
        And I fill the slug with "copy-test-page"
        And I add a heading content element with type "h1" and "My Title" content
        And I add a textarea content element with "My body text" content
        And I copy the 2nd content element
        And I paste the content element after the 2nd content element
        Then the 1st content element should be a "Heading" element
        And the 2nd content element should be a "Textarea" element
        And the 3rd content element should be a "Textarea" element
        And the 3rd content element should contain "My body text"

    @ui @javascript
    Scenario: Pasting a copied content element before the first element
        When I go to the create page
        And I fill the code with "copy-test-page"
        And I fill the name with "Copy Test Page"
        And I fill the slug with "copy-test-page"
        And I add a heading content element with type "h1" and "My Title" content
        And I add a textarea content element with "My body text" content
        And I copy the 1st content element
        And I paste the content element before the 1st content element
        Then the 1st content element should be a "Heading" element
        And the 2nd content element should be a "Heading" element
        And the 3rd content element should be a "Textarea" element
