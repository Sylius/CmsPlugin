# UPGRADE FROM `1.0.1` TO `1.1.0`

### Assets

#### Overview of changes
 - `PreviewController` has been registered in `controllers.json`
 - `PreviewController` uses a new standardized prefix `preview` → `@sylius-cms-plugin/admin/preview`.

In your end application, run the following command to add a new dependency to `package.json` file:

```bash
yarn add @sylius-cms-plugin/admin@file:vendor/sylius/cms-plugin/assets
```

And add the following to your `controllers.json` file:

```json
{
   "@sylius-cms-plugin/admin": {
      "preview": {
         "enabled": true,
         "fetch": "lazy"
      }
   }
}
```
