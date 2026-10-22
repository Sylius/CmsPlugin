@managing_pages
Feature: Managing cms pages
    In order to present custom pages in my online store
    As an Administrator
    I want to be able to edit and remove existing pages

    Background:
        Given the store operates on a single channel in "United States"
        And I am logged in as an administrator

    @ui
    Scenario: Deleting page
        Given there is a page in the store
        When I go to the cms pages page
        And I delete this page
        Then I should be notified that the page has been deleted
        And I should see empty list of pages

    @ui
    Scenario: Seeing disabled code field while editing a page
        Given there is a page in the store
        When I want to edit this page
        Then the code field should be disabled

    @ui
    Scenario: Updating page
        Given there is a page in the store
        When I want to edit this page
        And I fill the name with "Updated Page Name"
        And I update it
        Then I should be notified that the page was updated
        And the page name should be "Updated Page Name"

    @ui
    Scenario: Updating page with textarea content element
        Given there is a page in the store with "Textarea" content element
        When I want to edit this page
        And I fill "Code, Name" fields
        And I change textarea content element value to "New content"
        And I update it
        Then I should be notified that the page was updated
        And I should see a "Textarea" element with "New content" content

    @ui @javascript
    Scenario: Deleting content element in page
        Given there is a page in the store with "Textarea" content element
        When I want to edit this page
        And I delete the "Textarea" content element
        And I update it
        Then I should be notified that the page was updated
        And I should not see "Textarea" content element in the Content elements section

    @ui @javascript
    Scenario: Updating page with header and content template
        Given there is a page in the store with "Textarea, Heading" content elements
        When I want to edit this page
        And I fill "Code, Name" fields
        And I change textarea content element value to "Updated Content"
        And I select "H3" option from "heading_type"
        And I update it
        Then I should be notified that the page was updated
        And I should see a "Textarea" element with "Updated Content" content
        And the heading type should be "H3"
        And I should see trix toolbar with children in "Textarea" element

    @ui @javascript
    Scenario: Updating page with all available fields
        Given there are existing collections named "Blog" and "News" with "page" type
        And there is a page in the store
        When I want to edit this page
        And I fill the name with "Updated Page Name"
        And I fill the slug with "updated-page-slug"
        And I fill the meta keywords with "updated, keywords, for, seo"
        And I fill the meta description with "This is an updated meta description for the page"
        And I add "Blog" and "News" collections to it
        And I update it
        Then I should be notified that the page was updated
        And this page should have collections "Blog" and "News"

    @ui @javascript
    Scenario: Associating collections to existing page
        Given there are existing collections named "Blog", "Homepage" and "News" with "page" type
        And there is a page in the store
        And this page has these collections associated with it:
            | Homepage |
        When I want to edit this page
        And I add "Blog" and "News" collections to it
        And I update it
        Then I should be notified that the page was updated
        And this page should have collections "Homepage", "Blog" and "News"
