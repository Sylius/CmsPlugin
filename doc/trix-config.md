1. Add configuration under the `twig.form_themes` config key:

```yaml
# Symfony 2/3: app/config/config.yml
# Symfony 4: config/packages/twig.yaml

twig:
    form_themes:
        - '@SyliusCmsPlugin/widget/trix.html.twig'
```

3. Run `yarn add trix`
