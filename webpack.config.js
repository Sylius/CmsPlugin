const path = require('path');
const Encore = require('@symfony/webpack-encore');
const pluginName = 'cms';

const createConfigs = (pluginName, options = {}) => {
    const defaultOptions = {
        wysiwyg: 'ckeditor',
    };
    const mergedOptions = {...defaultOptions, ...options};

    const getConfig = (type) => {
        Encore.reset();

    Encore.setOutputPath(`public/build/${pluginName}/${type}/`)
        .setPublicPath(`/build/${pluginName}/${type}/`)
        .addEntry(`sylius-${pluginName}-${type}`, path.resolve(__dirname, `./src/Resources/assets/${type}/entry.js`))
        .disableSingleRuntimeChunk()
        .cleanupOutputBeforeBuild()
        .enableSourceMaps(!Encore.isProduction())
        .enableSassLoader();
        let entryFile = 'entry.js';
        if (type !== 'shop') {
            entryFile = mergedOptions.wysiwyg === 'trix'
                ? 'trix-entry.js'
                : 'entry.js';
        }

        Encore
            .setOutputPath(`public/build/sylius/${pluginName}/${type}/`)
            .setPublicPath(`/build/sylius/${pluginName}/${type}/`)
            .addEntry(
                `sylius-${pluginName}-${type}`,
                path.resolve(__dirname, `./src/Resources/assets/${type}/${entryFile}`)
            )
            .disableSingleRuntimeChunk()
            .cleanupOutputBeforeBuild()
            .enableSourceMaps(!Encore.isProduction())
            .enableSassLoader();

    const config = Encore.getWebpackConfig();
    config.name = `sylius-${pluginName}-${type}`;

    return config;
};

    return [
        getConfig('shop'),
        getConfig('admin')
    ];
};

Encore.setOutputPath(`src/Resources/public/build/`)
    .setPublicPath(`/public/build/`)
    .addEntry(`sylius-${pluginName}-shop`, path.resolve(__dirname, `./src/Resources/assets/shop/entry.js`))
    // Ckeditor
    .addEntry(`sylius-${pluginName}-admin`, path.resolve(__dirname, `./src/Resources/assets/admin/entry.js`))
    // Trix
    // .addEntry(`bitbag-${pluginName}-admin`, path.resolve(__dirname, `./src/Resources/assets/admin/trix-entry.js`))
    .cleanupOutputBeforeBuild()
    .disableSingleRuntimeChunk()
    .enableSassLoader();

const distConfig = Encore.getWebpackConfig();
distConfig.name = `cms-plugin-dist`;

Encore.reset();

module.exports = (options) => createConfigs('cms', options);
