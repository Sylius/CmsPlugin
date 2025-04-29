# WYSIWYG editor

## General usage

You can use a custom [WysiwygType](../src/Form/Type/WysiwygType.php) any place you want the Trix to appear in.
Take [the BlockTranslationType](../src/Form/Type/Translation/BlockTranslationType.php) as an example.

1. Add configuration under the `twig.form_themes` config key:

```yaml
# config/packages/twig.yaml
twig:
    form_themes:
        - '@SyliusCmsPlugin/widget/trix.html.twig'
```

3. Run `yarn add trix`


