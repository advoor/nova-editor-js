/* eslint-disable import/no-extraneous-dependencies,class-methods-use-this */

const mix = require('laravel-mix');
const webpack = require('webpack');
const path = require('path');
const fs = require('fs');

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
            'laravel-nova': 'LaravelNova'
        };

        webpackConfig.resolve.alias = {
            ...(webpackConfig.resolve.alias || {}),
        };

        webpackConfig.output = {
            uniqueName: this.name,
        };
    }
}

mix.extend('nova', new NovaExtension());
