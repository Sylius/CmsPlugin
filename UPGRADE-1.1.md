# UPGRADE FROM `1.1.4` TO `1.1.5`

### Constructors signature changes

1. The following constructor signatures have been changed:

   `Sylius\CmsPlugin\Renderer\ContentElement\ProductsCarouselContentElementRenderer`
    ```diff
        public function __construct(
    -       private ProductRepositoryInterface $productRepository,
    +       private ProductsProviderInterface|ProductRepositoryInterface $productsProvider,
        )
    ```

   `Sylius\CmsPlugin\Renderer\ContentElement\ProductsCarouselByTaxonContentElementRenderer`
    ```diff
        public function __construct(
    -       private ProductRepositoryInterface $productRepository,
    -       private TaxonRepositoryInterface $taxonRepository,
    +       private ProductsProviderInterface|ProductRepositoryInterface $productsProvider,
    +       private ?TaxonRepositoryInterface $taxonRepository = null,
        )
    ```

   `Sylius\CmsPlugin\Renderer\ContentElement\ProductsGridContentElementRenderer`
    ```diff
        public function __construct(
    -       private ProductRepositoryInterface $productRepository,
    +       private ProductsProviderInterface|ProductRepositoryInterface $productsProvider,
        )
    ```

   `Sylius\CmsPlugin\Renderer\ContentElement\ProductsGridByTaxonContentElementRenderer`
    ```diff
        public function __construct(
    -       private ProductRepositoryInterface $productRepository,
    -       private TaxonRepositoryInterface $taxonRepository,
    +       private ProductsProviderInterface|ProductRepositoryInterface $productsProvider,
    +       private ?TaxonRepositoryInterface $taxonRepository = null,
        )
    ```

   For backward compatibility, the old constructor signatures are still supported but deprecated.
   Passing repository interfaces will trigger a deprecation notice and will be removed in version 2.0.

# UPGRADE FROM `1.0.1` TO `1.1.0`

### Assets

#### Overview of changes
 - `PreviewController` has been registered in `controllers.json`
 - `PreviewController` uses a new standardized prefix `preview` → `@sylius-cms-plugin/admin/preview`.

In your end application, run the following command to add a new dependency to `package.json` file:

```bash
yarn add @sylius-cms-plugin/admin@file:vendor/sylius/cms-plugin/assets/admin
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
