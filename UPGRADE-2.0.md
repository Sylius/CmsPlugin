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

   Templates of the moved hookables have been moved accordingly under `@SyliusCmsPlugin/admin/page/form/`.
   If you have added or overridden hookables under the old hooks, move them to the new ones.

2. New admin Stimulus controllers have been added: `@sylius-cms-plugin/admin/ui-state` and
   `@sylius-cms-plugin/admin/widget-state` (also used by the block form). If your application lists the controllers of the plugin
   in `assets/admin/controllers.json`, add both of them:

   ```json
   "@sylius-cms-plugin/admin": {
       "ui-state": { "enabled": true, "fetch": "lazy" },
       "widget-state": { "enabled": true, "fetch": "lazy" }
   }
   ```

   The admin entrypoint now also imports `scss/main.scss` (page form styles). Rebuild your admin assets after upgrading.

3. Content elements of pages and blocks have a new, not mapped `key` hidden field, which identifies an element across
   live re-renders (e.g. when elements are moved). If you override the content element form theme, render `form.key`.

4. The input of the Trix editor (`@SyliusCmsPlugin/admin/shared/editor/trix.html.twig`) is now `<input type="text" hidden>`
   instead of `<input type="hidden">`, so that live re-renders do not overwrite the edited content.
   Update selectors relying on `input[type="hidden"]` (e.g. in Behat contexts) to `input[hidden]`.
