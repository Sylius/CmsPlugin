# Legacy Installation

## Overview:
**General**
- [Requirements](#requirements)
- [Composer](#composer)
- [Basic configuration](#basic-configuration)

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
    ];
    ```

1. Import required config in your `config/packages/_sylius.yaml` file:
    ```yaml
    # config/packages/_sylius.yaml
    
    imports:
          ...
          - { resource: "@SyliusCmsPlugin/config/config.yaml" }
    ```

1. Import routing in your `config/routes.yaml` file:

    ```yaml
    
    # config/routes.yaml
    ...
    sylius_cms:
        resource: "@SyliusCmsPlugin/config/routes.yaml"
    ```
1. Disable doctrine validate_xml_mapping
   ```yaml
      # config/packages/doctrine.yaml
      ...
          orm:
            validate_xml_mapping: false
          
   ```

1. Install assets:
    ```bash
    bin/console assets:install --symlink
    ```

1. Add entrypoint import:
    ```yaml
    // assets/admin/entrypoint.js
    import '../../vendor/sylius/cms-plugin/assets/admin/entrypoint'
    ```
    ```yaml
    // assets/shop/entrypoint.js
    import '../../vendor/sylius/cms-plugin/assets/shop/entrypoint'
    ```
   2. Add StimulusJS Support for admin customization:
      1. Create `controllers.json` if not exist
         ```json
         // assets/admin/controllers.json 
         {
           "controllers": [],
           "entrypoints": []
         }
         ```
      2. Add controllers directory if not exist: 
      ```bash 
         mkdir assets/admin/controllers 
      ```
      3. Enable encore Stimulus Bridge in `webpack.config.js`
      ```js 
         // webpack.config.js
         ... 
         // App admin config
         Encore
            ...
            .enableStimulusBridge(path.resolve(__dirname, './assets/admin/controllers.json'))
            ...
      ```
1. Run `yarn add trix@^2.0.0 swiper@^11.2.6`

1. Build assets:
    ```bash
      yarn install
    ```
    ```bash
      yarn encore dev
    ```

1. Database update:
```bash
  bin/console doctrine:migrations:migrate
```
**Note:** If you are running it on production, add the `-e prod` flag to this command.

**Clear application cache by using command:**
```bash
  bin/console cache:clear
```

