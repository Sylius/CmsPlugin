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
