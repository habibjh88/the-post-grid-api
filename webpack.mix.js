const mix = require('laravel-mix');
const fsExtra = require('fs-extra');
const path = require("path");
const cliColor = require("cli-color");
const emojic = require("emojic");
const wpPot = require('wp-pot');

let WebpackRTLPlugin = require( 'webpack-rtl-plugin' );

const isProduction = Mix.inProduction() ? true : false

const package_path = path.resolve(__dirname);
const package_slug = path.basename(path.resolve(package_path));
const temDirectory = package_path + '/dist';

if ((!process.env.npm_config_block && !process.env.npm_config_package) && (process.env.NODE_ENV === 'development' || process.env.NODE_ENV === 'production')) {


    if (mix.inProduction()) {
        let languages = path.resolve('languages');
        fsExtra.ensureDir(languages, function (err) {
            if (err) return console.error(err); // if file or folder does not exist
            wpPot({
                package: 'The Post Grid API',
                bugReport: '',
                src: '**/*.php',
                domain: 'the-post-grid-api',
                destFile: `languages/the-post-grid-api.pot`
            });
        });
    } else {
        // --> Create source map
        mix.webpackConfig({output: {devtoolModuleFilenameTemplate: '[resource-path]'}})
            .sourceMaps(false, 'inline-source-map');
    }

    mix.js( 'src/scripts/app.js', 'assets/js/' )
    mix.js( 'src/scripts/admin.js', 'assets/js/' )
    mix.sass( 'src/scss/style.scss', 'assets/css/' )
    mix.sass( 'src/scss/admin.scss', 'assets/css/' )
        .options( {
            terser: {
                extractComments: false
            },
            processCssUrls: false
        } )
        .webpackConfig( {
            plugins: [
                new WebpackRTLPlugin( {
                    filename: [ /(\.min.css)/i, '.rtl$1' ],
                    minify: isProduction,
                } )
            ],
        } )
}


if (process.env.npm_config_package) {
    mix.then(function () {
        const copyTo = path.resolve(`${temDirectory}/${package_slug}`);
        // Select All file then paste on list
        let includes = [
            'app',
            'assets',
            'languages',
            'templates',
            'vendor',
            'index.html',
            'README.txt',
            `${package_slug}.php`
        ];
        fsExtra.ensureDir(copyTo, function (err) {
            if (err) return console.error(err);
            includes.map((include) => {
                fsExtra.copy(
                    `${package_path}/${include}`,
                    `${copyTo}/${include}`,
                    function (err) {
                        if (err) return console.error(err);
                        console.log(
                            cliColor.white(`=> ${emojic.smiley}  ${include} copied...`)
                        );
                    }
                );
            });
            console.log(
                cliColor.white(`=> ${emojic.whiteCheckMark}  Build directory created`)
            );
        });
    });

    return;
}