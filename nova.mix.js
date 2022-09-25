/* eslint-disable import/no-extraneous-dependencies,class-methods-use-this */

const mix = require('laravel-mix');
const webpack = require('webpack');
const path = require('path');
const fs = require('fs');

const novaLocation = [
    path.join(__dirname, '../../vendor/laravel/nova/resources/js/mixins/packages.js'),
    path.join(__dirname, 'vendor/laravel/nova/resources/js/mixins/packages.js'),
].filter(fs.existsSync)[0] ?? '';

if (!novaLocation) {
    throw new Error('Unable to locate Nova resources. Mount the extension in a Laravel installation, or run `composer install` in the extension directory.');
}

class NovaExtension {
    name() {
        return 'nova-extension';
    }

    register(name) {
        this.name = name;
    }

    webpackPlugins() {
        return new webpack.ProvidePlugin({
            _: 'lodash',
            Errors: 'form-backend-validation',
        });
    }

    webpackConfig(webpackConfig) {
        webpackConfig.externals = {
            vue: 'Vue',
        };

        webpackConfig.resolve.alias = {
            ...(webpackConfig.resolve.alias || {}),
            'laravel-nova': novaLocation,
        };

        webpackConfig.output = {
            uniqueName: this.name,
        };
    }
}

mix.extend('nova', new NovaExtension());
