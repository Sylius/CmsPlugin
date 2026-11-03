# UPGRADE FROM `1.1` TO `1.2`

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

### Validation

1. Passing an array of options to configure a CMS Plugin validation constraint is **deprecated** since CMS Plugin 1.2
   and will be removed in CMS Plugin 2.0. Use named arguments instead.

   The `CollectionMatchesType` and `FileMatchesType` constraints now declare explicit constructors with named arguments
   (marked with `#[HasNamedArguments]`) and can be used as PHP attributes. The legacy array syntax keeps working,
   it only triggers a deprecation.

   Configuring constraints via **XML / YAML / PHP attributes is not affected** and requires no changes; the validator
   loaders pass the options as named arguments automatically. Only **direct instantiation in PHP** should be migrated:

   ```diff
   -new CollectionMatchesType(['type' => 'page'])
   +new CollectionMatchesType(type: 'page')

   -new FileMatchesType(['field' => 'cover'])
   +new FileMatchesType(field: 'cover')
   ```

### New WYSIWYG editor: Quill

A new Quill WYSIWYG editor is available alongside the existing Trix editor.
The default remains `trix`, so no action is required to keep the previous behavior.

To use Quill, register its Stimulus controller together with its stylesheet in your `assets/admin/controllers.json`
(Webpack Encore only picks up the `autoimport` entries listed there):

```json
{
    "controllers": {
        "@ehyiah/ux-quill": {
            "quill": {
                "main": "dist/controller.js",
                "webpackMode": "eager",
                "fetch": "eager",
                "enabled": true,
                "autoimport": {
                    "quill/dist/quill.snow.css": true
                }
            }
        }
    }
}
```

`quill.snow.css` is the theme the plugin uses by default. Any other Quill stylesheet, for example `quill/dist/quill.bubble.css`
or the module styles listed under `symfony.controllers.quill.autoimport` in the `package.json` of `@ehyiah/ux-quill`,
can be added to the same `autoimport` section.

See the documentation for setup and configuration details:
https://docs.sylius.com/cms-plugin/development/wysiwyg-editors
