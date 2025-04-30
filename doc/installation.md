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
[legacy installation instruction](docs/legacy_installation.md). However, we strongly encourage you to use
Symfony Flex, it's much quicker!

## Composer:
```bash
  composer require sylius/cms-plugin
```

1. Run `yarn add trix`
---
1. Build assets:
```bash
  yarn install
```
```bash
  yarn encore dev
```
---
1. Database update:
```bash
  bin/console doctrine:migrations:migrate
```
**Note:** If you are running it on production, add the `-e prod` flag to this command.

**Clear application cache by using command:**
```bash
  bin/console cache:clear
```

