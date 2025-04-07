const path = require('path');
const Encore = require('@symfony/webpack-encore');

const SyliusAdmin = require('@sylius-ui/admin');
const SyliusShop = require('@sylius-ui/shop');

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

// App admin config: CKEditor entry
Encore
    .setOutputPath('public/build/app/admin')
    .setPublicPath('/build/app/admin')
    .addEntry('app-admin-ckeditor-entry', './assets/admin/entry.js') // classic CKEditor
    .addAliases({
        '@vendor': path.resolve(__dirname, '../../vendor'),
    })
    .disableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .enableSassLoader();

const appAdminCkeditorConfig = Encore.getWebpackConfig();

appAdminCkeditorConfig.externals = Object.assign({}, appAdminCkeditorConfig.externals, {
    window: 'window',
    document: 'document',
});
appAdminCkeditorConfig.name = 'app.admin.ckeditor';

Encore.reset();

// App admin config: Trix entry
Encore
    .setOutputPath('public/build/app/admin')
    .setPublicPath('/build/app/admin')
    .addEntry('app-admin-trix-entry', './assets/admin/trix-entry.js') // trix version
    .addAliases({
        '@vendor': path.resolve(__dirname, '../../vendor'),
    })
    .disableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .enableSassLoader();

const appAdminTrixConfig = Encore.getWebpackConfig();

appAdminTrixConfig.externals = Object.assign({}, appAdminTrixConfig.externals, {
    window: 'window',
    document: 'document',
});
appAdminTrixConfig.name = 'app.admin.trix';

module.exports = [
    shopConfig,
    adminConfig,
    appShopConfig,
    appAdminCkeditorConfig,
    appAdminTrixConfig
];
