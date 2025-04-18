@managing_blocks
Feature: Adding blocks
    In order to present and manage dynamic content on my store pages
    As an Administrator
    I want to be able to add new blocks

    Background:
        Given I am logged in as an administrator
        And the store operates on a single channel in "United States"

    @ui
    Scenario: Trying to add block with existing code
        Given there is a block "Homepage Image" with code "homepage_image"
        When I go to the create block page
        And I fill the code with "homepage_image"
        And I try to add it
        Then I should be notified that there is already an existing block with provided code

    @ui
    Scenario: Trying to add block with blank data
        When I go to the create block page
        And I try to add it
        Then I should be notified that "Code" fields cannot be blank

    @ui
    Scenario: Trying to add block with too long data
        When I go to the create block page
        And I fill "Code, Name" fields with 251 characters
        And I try to add it
        Then I should be notified that "Code, Name" fields are too long

    @ui @javascript
    Scenario: Adding block with collections
        Given there are existing collections named "Blog" and "Homepage"
        When I go to the create block page
        And I fill the code with "intro"
        And I fill the name with "Intro"
        And I add "Blog" and "Homepage" collections to it
        And I add it
        Then I should be notified that the block has been created
        And this block should have collections "Blog" and "Homepage"

    @ui @javascript
    Scenario: Adding block with textarea content element
        When I go to the create block page
        And I fill the code with "intro"
        And I fill the name with "Intro"
        And I add a textarea content element with "Welcome to our store" content
        And I add it
        Then I should be notified that the block has been created
        And I should see a "Textarea" element with "Welcome to our store" content

    @ui @javascript
    Scenario: Adding block with single media content element
        Given there is an existing media with "image_1" code and name "Image 1"
        When I go to the create block page
        And I fill the code with "intro"
        And I fill the name with "Intro"
        And I add a single media content element with name "Image 1"
        And I add it
        Then I should be notified that the block has been created
        And I should see a "Single media" element with "Image 1" content

    @ui @javascript
    Scenario: Adding block with multiple media content element
        Given there is an existing media with names "Image 1" and "Image 2"
        When I go to the create block page
        And I fill the code with "intro"
        And I fill the name with "Intro"
        And I add a multiple media content element with names "Image 1" and "Image 2"
        And I add it
        Then I should be notified that the block has been created
        And I should see a "Multiple media" element with "Image 1" and "Image 2" content

    @ui @javascript
    Scenario: Adding block with heading content element
        When I go to the create block page
        And I fill the code with "intro"
        And I fill the name with "Intro"
        And I add a heading content element with type "H3" and "Welcome to our store" content
        And I add it
        Then I should be notified that the block has been created
        And I should see a "Heading" element with "H3" and "Welcome to our store" content

    @ui @javascript
    Scenario: Adding block with products carousel content element
        Given the store has "iPhone 8" and "iPhone X" products
        When I go to the create block page
        And I fill the code with "intro"
        And I fill the name with "Intro"
        And I add a products carousel content element with "iPhone 8" and "iPhone X" products
        And I add it
        Then I should be notified that the block has been created
        And I should see a "Products carousel" element with "iPhone 8" and "iPhone X" content

    @ui @javascript
    Scenario: Adding block with products carousel by taxon content element
        Given the store has "Smartphones" taxonomy
        When I go to the create block page
        And I fill the code with "intro"
        And I fill the name with "Intro"
        And I add a products carousel by taxon content element with "Smartphones" taxonomy
        And I add it
        Then I should be notified that the block has been created
        And I should see a "Products carousel by Taxon" element with "Smartphones" content

    @ui @javascript
    Scenario: Adding block with products grid content element
        Given the store has "iPhone 8" and "iPhone X" products
        When I go to the create block page
        And I fill the code with "intro"
        And I fill the name with "Intro"
        And I add a products grid content element with "iPhone 8" and "iPhone X" products
        And I add it
        Then I should be notified that the block has been created
        And I should see a "Products grid" element with "iPhone 8" and "iPhone X" content

    @ui @javascript
    Scenario: Adding block with products grid by taxon content element
        Given the store has "Smartphones" taxonomy
        When I go to the create block page
        And I fill the code with "intro"
        And I fill the name with "Intro"
        And I add a products grid by taxon content element with "Smartphones" taxonomy
        And I add it
        Then I should be notified that the block has been created
        And I should see a "Products grid by Taxon" element with "Smartphones" content

    @ui @javascript
    Scenario: Adding block with taxons list content element
        Given the store classifies its products as "Smartphones" and "Laptops"
        When I go to the create block page
        And I fill the code with "intro"
        And I fill the name with "Intro"
        And I add a taxons list content element with "Smartphones" and "Laptops" taxonomy
        And I add it
        Then I should be notified that the block has been created
        And I should see a "Taxons list" element with "Smartphones" and "Laptops" content

    @ui @javascript
    Scenario: Adding block with two content elements
        Given there is an existing media with names "Image 1" and "Image 2"
        And the store classifies its products as "Smartphones" and "Laptops"
        When I go to the create block page
        And I fill the code with "intro"
        And I fill the name with "Intro"
        And I add a taxons list content element with "Smartphones" and "Laptops" taxonomy
        And I add a multiple media content element with names "Image 1" and "Image 2"
        And I add it
        Then I should be notified that the block has been created
        And I should see a "Taxons list" element with "Smartphones" and "Laptops" content
        And I should see a "Multiple media" element with "Image 1" and "Image 2" content

    @ui @javascript
    Scenario: Adding block with content template
        Given there is an existing content template named "Homepage" with "block" type that contains "Textarea, Single media" content elements
        When I go to the create block page
        And I fill the code with "intro"
        And I fill the name with "Intro"
        And I select "Homepage" content template
        And I confirm that I want to use this template
        And I add it
        Then I should be notified that the block has been created
        And I should see newly created "Textarea" content element in Content elements section
        And I should see newly created "Single media" content element in Content elements section
