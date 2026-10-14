# UPGRADE FROM `1.x` TO `2.0`

### Admin page form

1. Twig hooks of the admin page create/update form have been restructured (shown for `update`; `create` changed the same way):

   | Before                                                          | After                                                                                   |
   |-----------------------------------------------------------------|-----------------------------------------------------------------------------------------|
   | `sylius_cms.admin.page.update.content.form.sections.general`     | `sylius_cms.admin.page.update.content.form.sections.settings.general` (`code`, `name`) and `...sections.settings.publishing` (`enabled`, `publish_at`, `channels`) |
   | `...form.sections.general.preview`                               | `sylius_cms.admin.page.update.content.form.header.summary.preview`                       |
   | `...form.sections.collections`                                   | `...form.sections.settings.organization.collections`                                     |
   | `...form.sections.content.fields.template`                       | `...form.sections.settings.organization.template`                                        |
   | `...form.sections.content.translations.elements_template`        | `...form.sections.content.translations.structure.elements_template`                      |
   | `...form.sections.content.translations.elements`                 | `...form.sections.content.translations.structure.elements` (list) and `...form.sections.content.translations.editor.elements` (fields) |

   Page-specific templates have been moved accordingly under `@SyliusCmsPlugin/admin/page/form/`. The layout parts shared
   with the block form (header, tabs, locale switcher, content elements structure and editor, settings cards) live under
   `@SyliusCmsPlugin/admin/shared/crud/common/content/form/`.
   If you have added or overridden hookables under the old hooks, move them to the new ones.

   The form component template `@SyliusCmsPlugin/admin/page/form.html.twig` has been replaced by
   `@SyliusCmsPlugin/admin/shared/crud/common/content/form.html.twig`, which takes its form theme from the `form_theme`
   configuration of the `form` hookable. The `_sylius_cms_admin_page_channels_row` and
   `_sylius_cms_admin_page_contentElements_entry_template_row` blocks have been removed from
   `@SyliusCmsPlugin/admin/page/form_theme.html.twig`; the markup lives in the `settings.publishing.channels` and
   `structure.elements_template` hookable templates now. The unused `@SyliusCmsPlugin/admin/page/form/sections/general/buttons.html.twig`
   template has been removed.

2. New admin Stimulus controllers have been added: `@sylius-cms-plugin/admin/ui-state` and
   `@sylius-cms-plugin/admin/widget-state` (also used by the block form). If your application lists the controllers of the plugin
   in `assets/admin/controllers.json`, add both of them:

   ```json
   "@sylius-cms-plugin/admin": {
       "ui-state": { "enabled": true, "fetch": "lazy" },
       "widget-state": { "enabled": true, "fetch": "lazy" }
   }
   ```

   The admin entrypoint now also imports `scss/main.scss` (page and block form styles). Rebuild your admin assets after upgrading.

3. Content elements of pages and blocks have a new, not mapped `key` hidden field, which identifies an element across
   live re-renders (e.g. when elements are moved). If you override the content element form theme, render `form.key`.

4. Twig hooks of the admin block create/update form have been restructured the same way as the page form (shown for `update`; `create` changed the same way):

   | Before                                                               | After                                                                                   |
   |----------------------------------------------------------------------|-----------------------------------------------------------------------------------------|
   | `sylius_cms.admin.block.update.content.form.sections.general`         | `sylius_cms.admin.block.update.content.form.sections.settings.general` (`code`, `name`) and `...sections.settings.publishing` (`enabled`, `channels`) |
   | `...form.sections.general.preview`                                    | `sylius_cms.admin.block.update.content.form.header.summary.preview`                      |
   | `...form.sections.collections`                                        | `...form.sections.settings.organization.collections`                                     |
   | `...form.sections.content.template`                                   | `...form.sections.settings.organization.template`                                        |
   | `...form.sections.display_config` (`products`, `productsInTaxons`, `taxons`) | `...form.sections.display` (`products`, `products_in_taxons`, `taxons`)            |
   | `...form.sections.content.translations.elements_template`             | `...form.sections.content.translations.structure.elements_template`                      |
   | `...form.sections.content.translations.elements`                      | `...form.sections.content.translations.structure.elements` (list) and `...form.sections.content.translations.editor.elements` (fields) |

   Block-specific templates have been moved accordingly under `@SyliusCmsPlugin/admin/block/form/`; the shared parts of the layout
   use the templates from `@SyliusCmsPlugin/admin/shared/crud/common/content/form/`.
   If you have added or overridden hookables under the old hooks, move them to the new ones.

   `@SyliusCmsPlugin/admin/block/form.html.twig` has been replaced by `@SyliusCmsPlugin/admin/shared/crud/common/content/form.html.twig`
   and `@SyliusCmsPlugin/admin/block/form_theme.html.twig` has been removed; its blocks have been moved to the
   `settings.publishing.channels` and `structure.elements_template` hookable templates.

5. The content elements form theme `@SyliusCmsPlugin/admin/shared/component_elements/form_theme.html.twig` has been rewritten
   for the new content editor (element cards with structure navigation), and the `@SyliusCmsPlugin/admin/macros/insert_element_divider.html.twig`
   macro has been replaced by `insert_divider` from `@SyliusCmsPlugin/admin/macros/content_elements.html.twig`.
   If you override the form theme, update it accordingly.

6. The input of the Trix editor (`@SyliusCmsPlugin/admin/shared/editor/trix.html.twig`) is now `<input type="text" hidden>`
   instead of `<input type="hidden">`, so that live re-renders do not overwrite the edited content.
   Update selectors relying on `input[type="hidden"]` (e.g. in Behat contexts) to `input[hidden]`.

7. The admin media create/update form has been redesigned (shown for `update`; `create` changed the same way, without the `download`,
   `path` and `shop_preview` hookables):

   | Before                                                                  | After                                                                                   |
   |-------------------------------------------------------------------------|-----------------------------------------------------------------------------------------|
   | `sylius_admin.media.update.content.form.sections` (`general`, `collections`, `media`, `translations`) | `sylius_cms.admin.media.update.content.form.sections` (`content`, `settings`)             |
   | `sylius_cms.admin.media.update.content.form.sections.general` (`code`, `name`, `enabled`, `channels`) | `...form.sections.settings.general` (`code`, `name`) and `...form.sections.settings.publishing` (`enabled`, `channels`) |
   | `...form.sections.collections`                                          | `...form.sections.settings.collections`                                                  |
   | `...form.sections.media` (`type`, `file`, `path`, `save_with_original_filename`, `preview`) | `...form.sections.content.media` (`file`, `download`, `path`, `save_with_original_name`, `shop_preview`); the type is rendered in the header of the `media` card |
   | `...form.sections.translations` (`alt`, `link`, `content`)              | `...form.sections.content.translations` (`images_only`, `alt`, `link`, `content`) and `...form.sections.content.translations.header` (`locale_switcher`) |

   A new `sylius_cms.admin.media.update.content.form.errors` hookable renders the summary of validation errors, so the
   `form_error_alert` hookable of `sylius_cms.admin.media.update.content` has been disabled.
   If you have added or overridden hookables under the old hooks, move them to the new ones.

   The media form component (`sylius_cms:admin:media:form`) is now `Sylius\CmsPlugin\Twig\Component\Media\FormComponent` and renders
   `@SyliusCmsPlugin/admin/shared/crud/common/content/form.html.twig`. Its templates live under `@SyliusCmsPlugin/admin/media/form/`;
   `@SyliusCmsPlugin/admin/media/form.html.twig` and the previous templates of the media form sections have been removed.
   The errors summary is rendered by `@SyliusCmsPlugin/admin/shared/crud/common/content/form/errors.html.twig`.
   The `sylius_cms:admin:media:preview` component (`Sylius\CmsPlugin\Twig\Component\MediaPreviewComponent`) and its
   `@SyliusCmsPlugin/admin/media/preview/*.html.twig` templates have been removed; the preview is rendered by the `file` hookable.

   The `type` field of `Sylius\CmsPlugin\Form\Type\MediaType` is now expanded (radio buttons) and defaults to `image` for a new media.

   A new `@sylius-cms-plugin/admin/media-upload` Stimulus controller handles drag & drop and the preview of the selected file.
   If your application lists the controllers of the plugin in `assets/admin/controllers.json`, add it:

   ```json
   "@sylius-cms-plugin/admin": {
       "media-upload": { "enabled": true, "fetch": "lazy" }
   }
   ```

8. `Sylius\CmsPlugin\Twig\Component\Trait\FormUiStateComponentTrait::getUiStateElementsPath()` returns `?string` now;
   return `null` when the form has no content elements.
   `@SyliusCmsPlugin/admin/shared/crud/common/content/form.html.twig` renders the UI state only for components providing
   `getUiState()` (e.g. through this trait); for other components `ui_state` in the context of the `form` hook is `null`.

9. The admin collection create/update form has been redesigned (shown for `update`; `create` changed the same way, without the `usage` hookable):

   | Before                                                                  | After                                                                                   |
   |-------------------------------------------------------------------------|-----------------------------------------------------------------------------------------|
   | `sylius_cms.admin.collection.update.content.form.sections.general` (`code`, `name`) | `...form.sections.settings.general` (`code`, `name`)                         |
   | `...form.sections.general.type`                                         | `...form.sections.settings.type`                                                         |
   | `...form.sections.elements.collection_elements`                         | `...form.sections.elements` (`search`, `list`)                                           |
   |                                                                         | `...form.sections.settings.usage` (new: pages and blocks rendering the collection)       |

   The `general` hookable of `sylius_cms.admin.collection.update.content.form.sections`, inherited from `sylius_admin.common`, is disabled.
   A new `sylius_cms.admin.collection.update.content.form.errors` hookable renders the summary of validation errors, so the
   `form_error_alert` hookable of `sylius_cms.admin.collection.update.content` has been disabled.
   If you have added or overridden hookables under the old hooks, move them to the new ones.

   `@SyliusCmsPlugin/admin/collection/form.html.twig`, `@SyliusCmsPlugin/admin/collection/form/sections/general.html.twig`,
   `@SyliusCmsPlugin/admin/collection/form/sections/general/{code,name,type}.html.twig` and
   `@SyliusCmsPlugin/admin/collection/form/sections/elements/collection_elements.html.twig` have been removed.
   The errors summary template `@SyliusCmsPlugin/admin/media/form/errors.html.twig` has been moved to
   `@SyliusCmsPlugin/admin/shared/crud/common/content/form/errors.html.twig` and is used by the media and collection forms.
   The `form` hook of `@SyliusCmsPlugin/admin/shared/crud/common/content/form.html.twig` receives the live component as `component` in its context.

   The collection form component (`sylius_cms:admin:collection:form`) is now `Sylius\CmsPlugin\Twig\Component\Collection\FormComponent`
   and renders `@SyliusCmsPlugin/admin/shared/crud/common/content/form.html.twig`. Its constructor has a new
   `Sylius\CmsPlugin\Repository\ContentConfigurationRepositoryInterface $contentConfigurationRepository` argument.
   `Sylius\CmsPlugin\Repository\ContentConfigurationRepository` implements this new interface; if you have configured
   a custom content configuration repository, make it implement the interface as well.

   `Sylius\CmsPlugin\Form\Type\CollectionType` has changed:
   - the `type` field is expanded (radio buttons);
   - the `pages`, `blocks` and `media` fields are always added (previously only the one matching the type), and the names of
     the fields per type are available in the `CollectionType::ELEMENTS_FIELDS` constant;
   - on submit, the elements that do not match the type of the collection are removed from it.

   A new `@sylius-cms-plugin/admin/collection-type` Stimulus controller asks for a confirmation before changing the type
   of a collection that contains elements. If your application lists the controllers of the plugin in `assets/admin/controllers.json`, add it:

   ```json
   "@sylius-cms-plugin/admin": {
       "collection-type": { "enabled": true, "fetch": "lazy" }
   }
   ```

10. The admin content template create/update form has been redesigned (shown for `update`; `create` changed the same way):

   | Before                                                                  | After                                                                                   |
   |-------------------------------------------------------------------------|-----------------------------------------------------------------------------------------|
   | `sylius_cms.admin.template.update.content.form.sections.general.name`   | `...form.sections.settings.general.name`                                                 |
   | `...form.sections.general.type`                                         | `...form.sections.settings.type`                                                         |
   | `...form.sections.elements.content_elements`                            | `...form.sections.elements.list`                                                         |

   The `general` hookable of `sylius_cms.admin.template.update.content.form.sections`, inherited from `sylius_admin.common`, is disabled.
   A new `sylius_cms.admin.template.update.content.form.errors` hookable renders the summary of validation errors, so the
   `form_error_alert` hookable of `sylius_cms.admin.template.update.content` has been disabled.
   The `list` hookable takes the element types offered when the template is empty in its `quick_types` configuration.
   If you have added or overridden hookables under the old hooks, move them to the new ones.

   `@SyliusCmsPlugin/admin/template/form.html.twig`, `@SyliusCmsPlugin/admin/template/form/sections/general.html.twig`,
   `@SyliusCmsPlugin/admin/template/form/sections/general/{name,type}.html.twig` and
   `@SyliusCmsPlugin/admin/template/form/sections/elements/content_elements.html.twig` have been removed.

   The content template form component (`sylius_cms:admin:template:form`) is now `Sylius\CmsPlugin\Twig\Component\Template\FormComponent`
   and renders `@SyliusCmsPlugin/admin/shared/crud/common/content/form.html.twig`.
   The `moveCollectionItem`, `insertCollectionItem` and `duplicateCollectionItem` live actions have been moved from
   `Sylius\CmsPlugin\Twig\Component\Trait\ContentElementsCollectionFormComponentTrait` to the new
   `Sylius\CmsPlugin\Twig\Component\Trait\CollectionItemsFormComponentTrait`, which the former uses. If you resolve
   conflicts of `onCollectionItemActivated` with `insteadof` in a component using only the new trait, name `CollectionItemsFormComponentTrait`.

   `Sylius\CmsPlugin\Form\Type\TemplateType` has changed:
   - its constructor has a new `iterable $contentElementTypes` argument (the `sylius_cms.content_elements.type` tagged services);
   - the `type` field is expanded (radio buttons) and set to `TemplateType::DEFAULT_TYPE` (`page`) for a template without a type;
   - the `contentElements` collection uses `Sylius\Bundle\AdminBundle\Form\Type\AddButtonType` with the available element types.

   `Sylius\CmsPlugin\Entity\Template::setContentElements()` reindexes the elements, so they are always stored as a JSON list.
