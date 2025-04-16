# Pages

Pages represent a customizable web page, you can adjust to your needs in admin panel.

## Page sections

Page contain 4 main editable sections:
- **General settings** - where you can set page name, code, channels, collections and publish at. It also contains a Preview button, which allows you to preview the page.
- **Content elements** - where you can add content elements to the page, read more about content elements [here](content_elements.md).
- **Teaser** - where you can set image, title and content. Teaser is a small preview of the page. It is used during rendering a collection of pages.
- **SEO** - where you can set slug, meta title, meta keywords and meta description.

## General usage

### Rendering the page

Once you created a page in the admin panel, you can render page in several ways:

1. Render the entire page content:

```twig
{{ render(path('sylius_cms_shop_page_show', {'slug' : 'about'})) }}
```
This will render the entire page with all its content elements.

2. Generate a page link template:

```twig
{{ sylius_cms_render_page_link('about', {}, 'custom/template.html.twig') }}
```
Use this to generate a page link template.

3. Generate a direct URL:

```twig
{{ sylius_cms_get_page_url('about') }}
```
Use this to generate just the URL of the page.

### Visiting the page

Page URL is generated based on the page slug. Full link looks like this: `domain.com/{locale}/page/{slug}`.

## Customization

### Override page template

If you don't know how to override templates yet,
read [Sylius template customization guide](http://docs.sylius.org/en/latest/customization/template.html).

You can create a template under `app/templates/bundles/SyliusCmsPlugin/Shop/Page` location.
Available templates you can override can be found under [this location](../templates/Shop/Page).

### Custom Page Templates

For more information about custom page templates, check the [documentation](templates.md).
