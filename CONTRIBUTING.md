### PHP Development

If you just want to change PHP code or HTML files, you just need to have
[Composer](https://getcomposer.org/doc/00-intro.md) installed. The tool manages
the PHP dependencies with the help of the `composer.json` file.

  1. Run `composer install` to install all dependencies.
  2. Follow the basic installation guide in the README.md or the LoanPlane Wiki.
  3. Make sure your editor or IDE has the correct code formatting settings set.
  4. That's it. You are good to go.


### Styles and Scripts Development

To change the actual styling of the application or scripts you may have to install
some other tools.
Please notice that LoanPlane has a unique theme system that splits up styles
into core styles and theme styles. Please visit the [LoanPlane Themes](https://github.com/LoanPlane/LoanPlane-Themes)
repository for more information.
This main repository ONLY manages core styles and scripts. If you want to develop a new
theme please use the LoanPlane Themes repository.

  requires Node and NPM: [Install both](https://nodejs.org/en/download/)
    Make sure to use the latest LTS version which is version 8 at the moment.
  * Install Grunt globally by running `npm install -g grunt-cli`.

If you have prepared your machine run `npm install` to install all dependencies.
After that there are two commands available for development:

  * `grunt dev`   Compiles all assets and starts watching your development files. If you
                  change a file and save it, the compilation will start automatically so
                  you do not have to do it manually.
                  All styles and scripts are not minified and sourcemaps are generated for
                  easier development.

  * `grunt build` This command is used to compile all assets for the production use. If
                  also minifies all assets to make sure pages load faster. Normally this
                  command is not used for styles development.
