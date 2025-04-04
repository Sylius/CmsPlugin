1. Add configuration under the `twig.form_themes` config key:

```yaml
# Symfony 2/3: app/config/config.yml
# Symfony 4: config/packages/twig.yaml

twig:
    form_themes:
        - '@SyliusCmsPlugin/Widget/_trixWidget.html.twig'
```

2. Add the file `config/packages/sylius_cms_plugin.yaml` (if it doesn't exist) and add the following configuration:

```yaml
sylius_cms_plugin:
    wysiwyg_editor: trix
```

3. Run `yarn add trix`
