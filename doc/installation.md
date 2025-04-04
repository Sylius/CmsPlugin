# Installation

## Overview:
GENERAL
- [Requirements](#requirements)
- [Composer](#composer)
- [Basic configuration](#basic-configuration)
--- 
BACKEND
- [Entities](#entities)
---
FRONTEND
- [Webpack](#webpack)
---
ADDITIONAL
- [Tests](#tests)
---

## Requirements:
We work on stable, supported and up-to-date versions of packages. We recommend you to do the same.

| Package       | Version         |
|---------------|-----------------|
| PHP           | \>=8.1          |
| sylius/sylius | 1.12.x - 1.13.x |
| MySQL         | \>= 5.7         |
| NodeJS        | \>= 18.x        |

## Composer:
```bash
composer require bitbag/cms-plugin --no-scripts
```

## Basic configuration:
1. Add plugin dependencies to your `config/bundles.php` file (if not added automatically):

```php
# config/bundles.php

return [
    ...
    BitBag\SyliusCmsPlugin\SyliusCmsPlugin::class  => ['all' => true],
    FOS\CKEditorBundle\FOSCKEditorBundle::class => ['all' => true], // WYSIWYG editor
    Sylius\CmsPlugin\SyliusCmsPlugin::class  => ['all' => true],
];
```

##### 3. Configure a WYSIWYG editor.
```bash
bin/console ckeditor:install
```

The plugin supports two WYSIWYG editors: [FOS CKEditor](https://symfony.com/doc/master/bundles/FOSCKEditorBundle/usage/ckeditor.html) by default and [Trix](https://trix-editor.org/) as an alternative. 

You can choose which one you want to use by following one of the guides below:
```bash
bin/console ckeditor:install --tag=4.22.1
```

- [Trix WYSIWYG config](./trix-config.md)*
- [CKeditor WYSIWYG config](./ckeditor-config.md)*

3.  If you are not using Symfony Flex, you need to add the following configuration:

```yaml
# Symfony 2/3: app/config/config.yml
# Symfony 4: config/packages/twig.yaml

twig:
    form_themes:
        - '@FOSCKEditor/Form/ckeditor_widget.html.twig'
        - '@SyliusCmsPlugin/Form/ckeditor_widget.html.twig'
```

##### 4. Import required config in your `config/packages/_sylius.yaml` file:
```yaml
# config/packages/_sylius.yaml

imports:
      ...
      - { resource: "@SyliusCmsPlugin/Resources/config/config.yml" }

##### 5. Import routing in your `config/routes.yaml` file:

```yaml

# config/routes.yaml
...
sylius_cms:
    resource: "@SyliusCmsPlugin/Resources/config/routing.yml"
```

4. Install assets:
```bash
bin/console assets:install --symlink
bin/console sylius:theme:assets:install --symlink
```

## Entities
### Update your database
First, please run legacy-versioned migrations by using command:
```bash
bin/console doctrine:migrations:migrate
```

After migration, please create a new diff migration and update database:
```bash
bin/console doctrine:migrations:diff
bin/console doctrine:migrations:migrate
```
**Note:** If you are running it on production, add the `-e prod` flag to this command.

### Clear application cache by using command:
##### 6. Finish the installation by updating the database schema and installing assets:

```bash
bin/console cache:clear
$ bin/console cache:clear

# If you used migrations in your project...
$ bin/console doctrine:migrations:migrate
# ... or if you use doctrine schema tool.
$ bin/console doctrine:schema:update --dump-sql # and --force switch when you're ready :)

$ bin/console assets:install --symlink
$ bin/console sylius:theme:assets:install --symlink
```
**Note:** If you are running it on production, add the `-e prod` flag to this command.

## Webpack
**Note.** In some cases, the `--symlink` option [may throw some errors](https://github.com/Sylius/SyliusThemeBundle/issues/91). If you consider running the commands without the `--symlink` option, please keep in mind to run them on every potential plugin update.

##### 7. Add plugin assets to your project

We recommend you to use Webpack (Encore), for which we have prepared four different instructions on how to add this plugin's assets to your project:

- [Import webpack config](./01.1-webpack-config.md)*
- [Add entry to existing config](./01.2-webpack-entry.md)
- [Import entries in your entry.js files](./01.3-import-entry.md)
- [Your own custom config](./01.4-custom-solution.md)

<small>* Default option for plugin development</small>

However, if you are not using Webpack, here are instructions on how to add optimized and compressed assets directly to your project templates:

- [Non webpack solution](./01.5-non-webpack.md)

##### 8. Passing required "backend" values to "frontend"

In order to make plugin finally work you need to declare "route", in admin _scripts.html.twig you can pass:

```
<script>
    const route = "{{ path('sylius_cms_plugin_admin_ajax_media_by_name_phrase')|escape('js') }}";
</script>
```

Any other approach, that will allow cms pages to read this value in js, under "route" key, will work. 

## Testing & running the plugin
### Run commands
```bash
yarn install
yarn encore dev # or prod, depends on your environment
```

## Tests
To run the tests, execute the commands:
```bash
composer install
cd tests/Application
yarn install
yarn encore dev
APP_ENV=test bin/console assets:install
APP_ENV=test bin/console doctrine:schema:create
APP_ENV=test symfony server:start --port=8080 -d
cd ../..
open http://localhost:8080
vendor/bin/behat
vendor/bin/phpspec run
```
