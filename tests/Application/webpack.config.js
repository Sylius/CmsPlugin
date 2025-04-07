const path = require('path');
const Encore = require('@symfony/webpack-encore');

const SyliusAdmin = require('@sylius-ui/admin');
const SyliusShop = require('@sylius-ui/shop');

// WYSIWYG mode – switch to 'trix' if needed
const wysiwyg = 'ckeditor';

const adminEntry = wysiwyg === 'trix'
    ? './assets/admin/trix-entry.js'
    : './assets/admin/entry.js';

// Shop config (from @sylius-ui/shop)
const shopConfig = SyliusShop.getWebpackConfig(path.resolve(__dirname));

// Admin config (from @sylius-ui/admin)
const adminConfig = SyliusAdmin.getWebpackConfig(path.resolve(__dirname));

// App shop config
Encore
    .setOutputPath('public/build/app/shop')
    .setPublicPath('/build/app/shop')
    .addEntry('app-shop-entry', './assets/shop/entry.js')
    .addAliases({
        '@vendor': path.resolve(__dirname, '../../vendor'),
    })
    .disableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .enableSassLoader();

const appShopConfig = Encore.getWebpackConfig();

appShopConfig.externals = Object.assign({}, appShopConfig.externals, {
    window: 'window',
    document: 'document',
});
appShopConfig.name = 'app.shop';

Encore.reset();

// App admin config
Encore
    .setOutputPath('public/build/app/admin')
    .setPublicPath('/build/app/admin')
    .addEntry('app-admin-entry', adminEntry)
    .addAliases({
        '@vendor': path.resolve(__dirname, '../../vendor'),
    })
    .disableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .enableSassLoader();

const appAdminConfig = Encore.getWebpackConfig();

appAdminConfig.externals = Object.assign({}, appAdminConfig.externals, {
    window: 'window',
    document: 'document',
});
appAdminConfig.name = 'app.admin';

module.exports = [shopConfig, adminConfig, appShopConfig, appAdminConfig];
