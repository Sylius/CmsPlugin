<p align="center">
    <a href="https://sylius.com" target="_blank">
        <picture>
          <source media="(prefers-color-scheme: dark)" srcset="https://media.sylius.com/sylius-logo-800-dark.png">
          <source media="(prefers-color-scheme: light)" srcset="https://media.sylius.com/sylius-logo-800.png">
          <img alt="Sylius Logo." src="https://media.sylius.com/sylius-logo-800.png">
        </picture>
    </a>
</p>

<h1 align="center">CMS Plugin</h1>

<p align="center"><a href="https://sylius.com/plugins/" target="_blank"><img src="https://sylius.com/assets/badge-official-sylius-plugin.png" width="200"></a></p>

<p align="center">This plugin provides seamless CMS capabilities for Sylius stores</p>

<p align="center">Extend your Sylius store with powerful content management features. Create, edit, and organize pages with ease – all fully integrated into the Sylius ecosystem.</p>


# Installation

## Requirements:
We work on stable, supported and up-to-date versions of packages. We recommend you to do the same.

| Package       | Version |
|---------------|---------|
| PHP           | ^8.2    |
| sylius/sylius | ^2.0    |
| MySQL         | ^8.4    |
| NodeJS        | ^20.x   |

---
#### Beware!

This installation instruction assumes that you're using Symfony Flex. If you don't, take a look at the
[legacy installation instruction](legacy_installation.md). However, we strongly encourage you to use
Symfony Flex, it's much quicker!

1. Require plugin with composer:

    ```bash
    composer require sylius/cms-plugin
    ```

   > Remember to allow community recipes with `composer config extra.symfony.allow-contrib true` or during plugin installation process

1. Run `yarn add trix@^2.0.0 swiper@^11.2.6`

1. Run `yarn encore dev` or `yarn encore production`

1. Database update:

    ```bash
    bin/console doctrine:migrations:migrate
    ```
**Note:** If you are running it on production, add the `-e prod` flag to this command.

**Clear application cache by using command:**

  ```bash
    bin/console cache:clear
  ```

---

## Documentation

For more information about the plugin, please refer to the [Sylius documentation](https://docs.sylius.com/sylius-official-plugins-documentation/cms-plugin).

## Security issues

If you think that you have found a security issue, please do not use the issue tracker and do not post it publicly.
Instead, all security issues must be sent to `security@sylius.com`

## Community

For online communication, we invite you to chat with us & other users on [Sylius Slack](https://sylius-devs.slack.com/).

## License

This plugin's source code is completely free and released under the terms of the MIT license.
