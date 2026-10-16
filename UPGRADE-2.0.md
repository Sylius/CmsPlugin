# UPGRADE FROM `1.x` TO `2.0`

The admin forms of pages, blocks, media, collections and content templates have been redesigned. They share a common
layout (header with tabs and a locale switcher, content editor, settings cards) whose templates live under
`@SyliusCmsPlugin/admin/shared/crud/common/content/form/`.

### Twig hooks

If you have added or overridden hookables under the old hooks, move them to the new ones.
The tables show the `update` forms; the `create` forms changed the same way.

1. Page:

   | Before                                                          | After                                                                                   |
   |-----------------------------------------------------------------|-----------------------------------------------------------------------------------------|
   | `sylius_cms.admin.page.update.content.form.sections.general`     | `sylius_cms.admin.page.update.content.form.sections.settings.general` (`code`, `name`) and `...sections.settings.publishing` (`enabled`, `publish_at`, `channels`) |
   | `...form.sections.general.preview`                               | `sylius_cms.admin.page.update.content.form.header.summary.preview`                       |
   | `...form.sections.collections`                                   | `...form.sections.settings.organization.collections`                                     |
   | `...form.sections.content.fields.template`                       | `...form.sections.settings.organization.template`                                        |
   | `...form.sections.content.translations.elements_template`        | `...form.sections.content.translations.structure.elements_template`                      |
   | `...form.sections.content.translations.elements`                 | `...form.sections.content.translations.structure.elements` (list) and `...form.sections.content.translations.editor.elements` (fields) |

2. Block:

   | Before                                                               | After                                                                                   |
   |----------------------------------------------------------------------|-----------------------------------------------------------------------------------------|
   | `sylius_cms.admin.block.update.content.form.sections.general`         | `sylius_cms.admin.block.update.content.form.sections.settings.general` (`code`, `name`) and `...sections.settings.publishing` (`enabled`, `channels`) |
   | `...form.sections.general.preview`                                    | `sylius_cms.admin.block.update.content.form.header.summary.preview`                      |
   | `...form.sections.collections`                                        | `...form.sections.settings.organization.collections`                                     |
   | `...form.sections.content.template`                                   | `...form.sections.settings.organization.template`                                        |
   | `...form.sections.display_config` (`products`, `productsInTaxons`, `taxons`) | `...form.sections.display` (`products`, `products_in_taxons`, `taxons`)            |
   | `...form.sections.content.translations.elements_template`             | `...form.sections.content.translations.structure.elements_template`                      |
   | `...form.sections.content.translations.elements`                      | `...form.sections.content.translations.structure.elements` (list) and `...form.sections.content.translations.editor.elements` (fields) |

3. Media (`create` has no `download`, `path` and `shop_preview` hookables):

   | Before                                                                  | After                                                                                   |
   |-------------------------------------------------------------------------|-----------------------------------------------------------------------------------------|
   | `sylius_admin.media.update.content.form.sections` (`general`, `collections`, `media`, `translations`) | `sylius_cms.admin.media.update.content.form.sections` (`content`, `settings`)             |
   | `sylius_cms.admin.media.update.content.form.sections.general` (`code`, `name`, `enabled`, `channels`) | `...form.sections.settings.general` (`code`, `name`) and `...form.sections.settings.publishing` (`enabled`, `channels`) |
   | `...form.sections.collections`                                          | `...form.sections.settings.collections`                                                  |
   | `...form.sections.media` (`type`, `file`, `path`, `save_with_original_filename`, `preview`) | `...form.sections.content.media` (`file`, `download`, `path`, `save_with_original_name`, `shop_preview`); the type is rendered in the header of the `media` card |
   | `...form.sections.translations` (`alt`, `link`, `content`)              | `...form.sections.content.translations` (`images_only`, `alt`, `link`, `content`) and `...form.sections.content.translations.header` (`locale_switcher`) |

4. Collection (`create` has no `usage` hookable):

   | Before                                                                  | After                                                                                   |
   |-------------------------------------------------------------------------|-----------------------------------------------------------------------------------------|
   | `sylius_cms.admin.collection.update.content.form.sections.general` (`code`, `name`) | `...form.sections.settings.general` (`code`, `name`)                         |
   | `...form.sections.general.type`                                         | `...form.sections.settings.type`                                                         |
   | `...form.sections.elements.collection_elements`                         | `...form.sections.elements` (`search`, `list`)                                           |
   |                                                                         | `...form.sections.settings.usage` (new: pages and blocks rendering the collection)       |

5. Content template:

   | Before                                                                  | After                                                                                   |
   |-------------------------------------------------------------------------|-----------------------------------------------------------------------------------------|
   | `sylius_cms.admin.template.update.content.form.sections.general.name`   | `...form.sections.settings.general.name`                                                 |
   | `...form.sections.general.type`                                         | `...form.sections.settings.type`                                                         |
   | `...form.sections.elements.content_elements`                            | `...form.sections.elements.list`                                                         |

   The `list` hookable takes the element types offered for an empty template from its `quick_types` configuration.

6. The `general` hookable inherited from `sylius_admin.common` is disabled in the collection and content template forms.

7. The media, collection and content template forms render the summary of validation errors in a new
   `sylius_cms.admin.{media,collection,template}.{create,update}.content.form.errors` hookable, so the `form_error_alert`
   hookable of `sylius_cms.admin.{media,collection,template}.{create,update}.content` is disabled.

8. The `form` hook of `@SyliusCmsPlugin/admin/shared/crud/common/content/form.html.twig` receives the live component as
   `component` and the UI state of the form as `ui_state` (`null` for the collection and content template forms) in its context.

### Templates

1. The form component templates `@SyliusCmsPlugin/admin/{page,block,media,collection,template}/form.html.twig` have been
   replaced by `@SyliusCmsPlugin/admin/shared/crud/common/content/form.html.twig`, which takes its form theme from the
   `form_theme` configuration of the `form` hookable.

2. The templates of the previous form sections have been removed or moved under
   `@SyliusCmsPlugin/admin/{page,block,media,collection,template}/form/` according to the new hooks. Additionally:
   - `@SyliusCmsPlugin/admin/block/form_theme.html.twig` has been removed;
   - the `_sylius_cms_admin_page_channels_row` and `_sylius_cms_admin_page_contentElements_entry_template_row` blocks have
     been removed from `@SyliusCmsPlugin/admin/page/form_theme.html.twig`;
   - the markup of these form themes lives in the `settings.publishing.channels` and `structure.elements_template` hookable templates now;
   - the unused `@SyliusCmsPlugin/admin/page/form/sections/general/buttons.html.twig` template has been removed;
   - the `@SyliusCmsPlugin/admin/media/preview/{image,video,file}.html.twig` templates have been removed; the preview of
     the media is rendered by the `file` hookable.

3. The content elements form theme `@SyliusCmsPlugin/admin/shared/component_elements/form_theme.html.twig` has been
   rewritten for the new content editor (element cards with structure navigation). If you override it, update it
   accordingly and render the new `form.key` field of the content elements.

4. The `@SyliusCmsPlugin/admin/macros/insert_element_divider.html.twig` macro has been replaced by `insert_divider`
   from `@SyliusCmsPlugin/admin/macros/content_elements.html.twig`.

5. The input of the Trix editor (`@SyliusCmsPlugin/admin/shared/editor/trix.html.twig`) is `<input type="text" hidden>`
   instead of `<input type="hidden">` now, so that live re-renders do not overwrite the edited content.
   Update selectors relying on `input[type="hidden"]` (e.g. in Behat contexts) to `input[hidden]`.

### Live components

1. The following form components are no longer `Sylius\Bundle\UiBundle\Twig\Component\ResourceFormComponent`:

   | Component                         | Class                                                         |
   |-----------------------------------|---------------------------------------------------------------|
   | `sylius_cms:admin:media:form`      | `Sylius\CmsPlugin\Twig\Component\Media\FormComponent`         |
   | `sylius_cms:admin:collection:form` | `Sylius\CmsPlugin\Twig\Component\Collection\FormComponent`    |
   | `sylius_cms:admin:template:form`   | `Sylius\CmsPlugin\Twig\Component\Template\FormComponent`      |

2. The `sylius_cms:admin:media:preview` component (`Sylius\CmsPlugin\Twig\Component\MediaPreviewComponent`) has been removed.

3. `Sylius\CmsPlugin\Twig\Component\Trait\ContentElementsCollectionFormComponentTrait` uses the new
   `Sylius\CmsPlugin\Twig\Component\Trait\CollectionItemsFormComponentTrait`, which provides the live actions to remove,
   move, insert and duplicate the content elements and uses `Sylius\Bundle\UiBundle\Twig\Component\LiveCollectionTrait` itself.
   If you use `ContentElementsCollectionFormComponentTrait` in your own component:
   - remove `use LiveCollectionTrait;` from the component;
   - implement `onCollectionItemActivated(string $propertyPath, int|string|null $key): void`, called with the key
     of the element to activate after it has been removed, moved, inserted or duplicated (it can be empty), or use
     `Sylius\CmsPlugin\Twig\Component\Trait\FormUiStateComponentTrait`, which implements it.

4. The following constructor signatures have been changed:

   `Sylius\CmsPlugin\Twig\Component\Page\FormComponent`
    ```diff
        public function __construct(
            RepositoryInterface $pageRepository,
            FormFactoryInterface $formFactory,
            string $resourceClass,
            string $formClass,
            TemplateRepositoryInterface $templateRepository,
            Environment $twig,
            LocaleProviderInterface $localeProvider,
            string $previewTemplate,
            protected readonly SlugGeneratorInterface $slugGenerator,
    +       FormUiStateResolverInterface $formUiStateResolver,
    +       LocaleWarningsProviderInterface $localeWarningsProvider,
        )
    ```

   `Sylius\CmsPlugin\Twig\Component\Block\FormComponent`
    ```diff
        public function __construct(
            RepositoryInterface $blockRepository,
            FormFactoryInterface $formFactory,
            string $resourceClass,
            string $formClass,
            TemplateRepositoryInterface $templateRepository,
            Environment $twig,
            LocaleProviderInterface $localeProvider,
            string $previewTemplate,
            protected readonly ContentElementRendererStrategyInterface $contentElementRendererStrategy,
    +       FormUiStateResolverInterface $formUiStateResolver,
        )
    ```

### Form types

1. `Sylius\CmsPlugin\Form\Type\MediaType`: the `type` field is expanded (radio buttons) and defaults to `image` for a new media.

2. `Sylius\CmsPlugin\Form\Type\CollectionType`:
   - the `type` field is expanded (radio buttons);
   - the `pages`, `blocks` and `media` fields are always added (previously only the one matching the type); the names of
     the fields per type are available in the `CollectionType::ELEMENTS_FIELDS` constant;
   - on submit, the elements that do not match the type of the collection are removed from it.

3. `Sylius\CmsPlugin\Form\Type\TemplateType`:
   - its constructor has a new `iterable $contentElementTypes` argument (the `sylius_cms.content_elements.type` tagged services);
   - the `type` field is expanded (radio buttons) and set to `TemplateType::DEFAULT_TYPE` (`page`) for a template without a type;
   - the `contentElements` collection uses `Sylius\Bundle\AdminBundle\Form\Type\AddButtonType` with the available element types.

4. `Sylius\CmsPlugin\Form\Type\ContentElements\ContentElementConfigurationType` has a new, not mapped `key` hidden field,
   which identifies an element across live re-renders (e.g. when elements are moved).

### Repositories

1. `Sylius\CmsPlugin\Repository\ContentConfigurationRepository` implements the new
   `Sylius\CmsPlugin\Repository\ContentConfigurationRepositoryInterface`, required by the collection form component.
   If you have configured a custom content configuration repository, make it implement the interface as well.

### Entities

1. `Sylius\CmsPlugin\Entity\Template::setContentElements()` reindexes the elements, so they are always stored as a JSON list.

### Assets

1. The admin entrypoint imports `scss/main.scss` now.

2. New admin Stimulus controllers have been added. If your application lists the controllers of the plugin in
   `assets/admin/controllers.json`, add them:

   ```json
   "@sylius-cms-plugin/admin": {
       "widget-state": { "enabled": true, "fetch": "lazy" },
       "ui-state": { "enabled": true, "fetch": "lazy" },
       "media-upload": { "enabled": true, "fetch": "lazy" },
       "collection-type": { "enabled": true, "fetch": "lazy" },
       "tooltip": { "enabled": true, "fetch": "lazy" }
   }
   ```

   Rebuild your admin assets after upgrading.
