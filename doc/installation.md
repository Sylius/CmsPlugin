# Installation

## Overview:
**General**
- [Requirements](#requirements)
- [Composer](#composer)
- [Basic configuration](#basic-configuration)
- [Entities](#entities)

## Requirements:
We work on stable, supported and up-to-date versions of packages. We recommend you to do the same.

| Package       | Version |
|---------------|---------|
| PHP           | ^8.2    |
| sylius/sylius | ^2.0    |
| MySQL         | ^8.4    |
| NodeJS        | ^20.x   |

## Composer:
```bash
composer require sylius/cms-plugin
```

## Basic configuration:
1. Add plugin dependencies to your `config/bundles.php` file (if not added automatically):

```php
# config/bundles.php

return [
    ...
    Sylius\CmsPlugin\SyliusCmsPlugin::class  => ['all' => true],
    Sylius\CmsPlugin\SyliusCmsPlugin::class  => ['all' => true],
];
```

2. Import required config in your `config/packages/_sylius.yaml` file:
```yaml
# config/packages/_sylius.yaml

imports:
      ...
      - { resource: "@SyliusCmsPlugin/config/config.yml" }
```

3. Import routing in your `config/routes.yaml` file:

```yaml

# config/routes.yaml
...
sylius_cms:
    resource: "@SyliusCmsPlugin/config/routing.yml"
```

4. Install assets:
```bash
bin/console assets:install --symlink
bin/console sylius:theme:assets:install --symlink
```

## Entities
### Update your database
```bash
bin/console doctrine:migrations:diff
bin/console doctrine:migrations:migrate
```
**Note:** If you are running it on production, add the `-e prod` flag to this command.

### Clear application cache by using command:
```bash
bin/console cache:clear

```

