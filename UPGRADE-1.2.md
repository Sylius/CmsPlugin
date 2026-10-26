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

### Validation Constraints

1. Validator constraint constructors have been updated to use named arguments instead of array-based options:

   `Sylius\CmsPlugin\Validator\Constraint\CollectionMatchesType`
    ```diff
    - new CollectionMatchesType(['type' => SomeClass::class])
    + new CollectionMatchesType(type: SomeClass::class)
    ```

   For backward compatibility, the old array-based constructor is still supported but **deprecated**.
   The array-based approach will be removed in version 2.0.

   When using attributes/annotations:
    ```php
    // Old (deprecated)
    #[CollectionMatchesType(['type' => SomeClass::class])]

    // New
    #[CollectionMatchesType(type: SomeClass::class)]
    ```

### New WYSIWYG editor: Quill

A new Quill WYSIWYG editor is available alongside the existing Trix editor.
The default remains `trix`, so no action is required to keep the previous behavior.

See the documentation for setup and configuration details:
https://docs.sylius.com/cms-plugin/development/wysiwyg-editors
