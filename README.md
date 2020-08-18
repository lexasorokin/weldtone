# Civic Starter

This repo is a nice starter for Government Drupal sites. This documentation will walk though several aspects of the custom theme and custom modules to help other developers understand their structure, functionality, and how to easily update and expand what is here.

## Table of Contents

1. [Drupal Installation and Setup](#drupal-installation-and-setup)
1. [Using NPM and Webpack for SCSS and JS](#using-npm-and-webpack-for-scss-and-js)
1. [Understanding the Structure](#understanding-the-structure)
    1. [SCSS Structure](#scss-structure)
    1. [JS Structure](#js-structure)


---

## Drupal Installation and Setup

Clone this repo into the desired location. Navigate to the root of the cloned repo and run:

```
composer install
```

This will install of the modules and dependencies for the project declared in `composer.lock` or `composer.json` as a fallback. If you do not have the composer cli tool installed, visit getcomposer.org to install it on your system.

## Civic Theme

### Using NPM and Webpack for SCSS and JS

#### Installing Node Modules

If you've just cloned the project navigate to `/web/themes/civic` and run the following command:

```
npm install
```
This command looks in the same directory for the `package.json` file, sees what dependencies are needed, and installs them into a `node_modules` folder. This folder can become very large, which is why it is not tracked in the git repo.

### Compiling Your SCSS and JS

The compiled JS and CSS is currently tracked in the repo, so if you've just cloned the repo and haven't made any changes, you don't need to compile the SCSS and JS.

When you do want to compile your SCSS and JS, navigate to `/web/themes/civic` and run one of the following two commands:
```
npm build
```
This will do a single compilation.

```
npm start
``` 
This will run the compilation and then watch for additional changes. This is the ideal choice during development, as it will recompile your JS and SCSS every time a change is made.

### Debugging Compilation

If your compilation throws an error, it is usually one of three things.

1. There is a syntax error in your SCSS and JS code
1. You are trying to import a file that does not exist
1. There is a permissions issue.

If the error is one of the first two items, the error message in the console is typically specific about where the issue is happening. If the error is more cryptic, it is likely a permissions issue while trying to write to the theme's `dist` folder.

### Understanding the Structure

To avoid repetition, I will write `@theme` from here on out to refer to the civic theme folder instead of `/web/themes/civic`.

One of the most important things to understand before making updates to the theme, is how JavaScript and CSS are structured. The only custom assets being served by the theme are:
```
@theme/dist/bundle.js
@theme/dist/main.css
```
The files inside of the `dist` folder should never be manually updated. These files are a compiled result of all the files within `@theme/src/js` and `@theme/src/scss`.

This module uses a bundler called `webpack` to do this compilation, you can see the webpack configuration file at `@theme/webpack.config.js`.

### SCSS Structure

SCSS is a pre-processor for CSS with a lot of capabilities. It primarily helps with keeping style code organized and reducing repetition. Let's look at how the SCSS files are structured.

The entry point to our SCSS files is `@theme/src/scss/main.scss`. This is the one file that is being pulled into our webpack compilation and from this file, all other SCSS files are imported. It's important whenever you create a new SCSS file, to make sure that you have included it here.

From here the SCSS code is broken up into two folders, `modules` and `partials`. 

#### SCSS Modules

This is not to be confused with Drupal modules, which is something completely different. The `modules` folders contains helper code that doesn't actually output any styles. Here we have `_mixins.scss` and `_variables.scss`. Mixins are bits of reusable SCSS (Kint of like functions for styles) that can be included in your other SCSS files by writing `@include NAME-OF-MIXIN`. The `_variables.scss` file contains variables that can be referenced throughout the rest of your styles. The most notable variables here are the `$primary-color` and `$secondary-color` variables. Try changing these and recompiling your styles, and you'll see that it updates several different components on your site.

#### SCSS Partials

Inside the `partials` folder, there are several more folders. These folders attempt to group the styles by the components which they apply to. This structure is similar to the folder structure inside of the theme's `templates` folder.

## JS Structure

While SCSS is relatively easy to understand for someone who knows CSS, the JavaScript structure being used may be confusing for someone who has primarily used jQuery.

The use of Webpack allows for using ES6, which are newer features and some syntactic sugar on top of vanilla JavaScript. It allows JavaScript to be more modular and object-oriented. Webpack is able to take that code and compile it to JavaScript that is backwards compatible for older browsers.

### You Don't Have to Do It This Way

Before diving deeper into the JavaScript structure, it's important to say you don't have to follow this structure if you don't feel comfortable with it. The easiest alternative is to create another javascript file right next to `index.js`. For example, add `@theme/src/js/custom.js`. Then go to `civic.libraries.yml` and add the following line right under `dist/bundle.js`.

```
  src/js/custom.js: {}
```

Then you can add all the JavaScript you want in this file, and leave alone the existing JavaScript code in `@theme/src/js/index.js` and `@theme/src/js/classes`. Your new javascript file will not be included in Webpack's compilation process.

### Understanding index.js

The syntax in this file utilizes ES6, which is simply newer features of the JavaScript language. This syntax allows for the JavaScript code to be more modular and object-oriented. Instead of just writing out free flowing JavaScript, we create a `class` and then instantiate that class. In `index.js`, a `class` named `CivicThemeIndex` is declared. In this class, methods are declared. One of these methods is `intantiateClasses`. This function instantiates classes that we pass to it and then calls their init method. If a selector is provided, the class will be instantiated once for every element found in the DOM. If no elements are found, then no classes will be instatiated. This helps prevent unnecessary JavaScript from being added to the page.

### Adding Additional JavaScript Following Current Pattern

To add additional javascript following this pattern, follow these steps:

1. Add a new file inside of `@theme/src/js/classes`.
1. In that new file, declare your class name.
1. Add a constructor function for any variables you want to initalize on instantiation.
1. Add an `init` method to be called on instantiation.
1. Add a default export statement at the bottom of the file.
1. At the top of `@theme/src/js/index.js`, import your new file.
1. Add a call to `this.instantiateClasses` in either `initOnDocumentLoad` or `initOnWindowLoad`.
1. Pass the imported name of your class into the method.
1. Optionally pass in a query selector to the method.

#### Example

*@theme/src/js/classes/example.js*
```js
class Example {
  constructor(domEl) {
    this.domEl = domEl;
  }

  init() {
    // Do Something
  }
}

export default Exampe;
```

*@theme/src/js/index.js*
```js
import Example from './classes/example.js'

class CivicThemeIndex {
  initOnDocumentLoad() {
    this.instantiateClasses(Example, '.example-element');
  }

// Rest of code not included
```



### The civic.theme File

Open up `@theme/civic.theme`. If you're familiar with Drupal theming, this file includes preprocess functions that "hook" into Drupal at certain points of it's processing and allow us to add, remove, or alter variables that are currently available.

To keep this file clean, all of the business logic has been extracted into PHP classes within the `@theme/src` folder. If you open up one of these PHP classes, you'll see that each one is using public static functions with the same variables being passed through. Each function has a comment above stating what function in `civic.theme` is calling it.

### Additional Styles

One of the most customizable parts of this theme is the 'Additional Styles' field. Several of the paragraph types have this field, here is how it works.

- The field references taxonomy terms
- By default the taxonomy vocabulary that is referenced is 'Programmed Styles'
- Each of these referenced taxonomy terms has a 'Classes' field.
- Inside of the cooresponding paragraph's TWIG templates, they call the `additional_styles` macro
- This macro loops through each referenced taxonomy term, gets the associated class or classes, and then adds those classes to the paragraph wrapper.
- Then those classes can be targeted for CSS styles.
- Some default styles can be found at `@theme/src/scss/partials/_programmed-styles.scss`

At first glance, this sounds kind of cumbersome, but it's a great way for the developer to control and expand what styles are available to content creators. You can see that the default ones have a naming convention of category, colon, and then value (Example: `Background: Primary Color`), which should make it easier for content creators to find the type of styles they're looking for.

#### Expanding the Additional Styles Functionality

Perhaps you want to have additional programmed styles that are only available for the `Background Image Section` paragraph type. Consider creating a new Vocabulary called `Programmed Styles: BG Image Section`. Inside that vocabulary add the existing field `field_classes`, and then add a few terms.

Then navigate to `Admin > Structure > Paragraphs > BG Image Section > Manage Fields`. Click `Edit` for `Additional Styles`. Then under `Reference Type` check your new vocabulary `Programmed Styles: BG Image Section`. Now your new taxonomy terms can be added to this paragraph type, but not any of the other paragraph types.

## Components of Theme

Let's take a look at some of the more complicated functionality in our theme to understand what is happening.

### Translations

Translations involves two separate processes, that need to work together.

1. Google Translate
1. Drupal's Professional Translations

Google Translate is an external script from Google that uses JavaScript to translate text on the page. Drupal's profession translations allow content creators to create different versions of a Node or a Block, and professionally write what the translation should be.

When we try to get these two things to work in tandem, here are the issues:

1. If a node is professionally translated, we don't want Google to try to translate that translation, or it will be a mess.
1. Even though a node can be professionally translated, we still want Google Translate to translate the other things on the page (Header, Footer, etc.)

These problems are addressed in the following three files:
```
@theme/templates/layout/html.html.twig
@theme/src/Translations.php
@theme/src/js/classes/Translations.js
```
In the html template, we start by adding a `notranslate` class to the html and body tags. Google Translate stores preferred translations in a cookie, which can have unexpected results. Adding these wrapper classes ensures that the translation won't happen until we tell it to.

The PHP file looks to see what language is selected. If there's a professionally translated version of the current page that the user isn't viewing, it will redirect them there. It then sets variables to be read in our JavaScript about what the default language is, what the selected language is, and whether Google Translate should translate the page.

In the JavaScript file, we check the variables to see if we're viewing a professionally translated page. If we are, then we add the `notranslate` class to the `content region`. Then we remove the `notranslate` class from the body and html tags and perform the Google Translate.




