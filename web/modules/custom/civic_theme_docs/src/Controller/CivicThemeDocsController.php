<?php

namespace Drupal\civic_theme_docs\Controller;

use Drupal\Core\Url;
use Drupal\Core\Controller\ControllerBase;

/**
 * Provides route responses for the Civic Theme Docs Module
 */
class CivicThemeDocsController extends ControllerBase {
  protected function inlineLink($route, $text = NULL) {
    $router = \Drupal::service('router.no_access_checks');
    $routeUrl = Url::fromRoute('civic_theme_docs.' . $route)->getInternalPath();
    $routeTitle = $router->match($routeUrl)['_title'];

    if (isset($text)) {
      return t('<a href="/:link">' . $text . '</a>', [':link' => $routeUrl]);
    }
    
    return t('<a href="/:link">' . $routeTitle . '</a>', [':link' => $routeUrl]);
  }

  protected function liLink($route, $nestOl = FALSE) {
    $li = '<li>' . $this->inlineLink($route);
    $li .= $nestOl ? '<ol>' : '</li>';
    return $li;
  }

  protected function moduleLink($module_name) {
    $module_handler = \Drupal::service('module_handler');

    if ($module_handler->moduleExists($module_name)) {
      $display_name = $module_handler->getName($module_name);
      $help_link = 'admin/help/' . $module_name;
      return t('<a href="/:link">' . $display_name . '</a>', [':link' => $help_link]);
    }

    return '';
  }

  protected function routeArray($subarray = NULL) {
    $routeArray = array(
      array(
        'name' => 'assets',
        'subroutes' => array(
          array('name' => 'webpack_scss_and_js'),
          array('name' => 'scss_structure'),
          array('name' => 'js_structure'),
        ),
      ),
      array(
        'name' => 'components',
        'subroutes' => array(
          array('name' => 'theme_file'),
          array('name' => 'translations'),
          array('name' => 'recurring_events'),
          array('name' => 'modular_pages'),
          array('name' => 'additional_styles'),
        ),
      ),
    );

    return $routeArray;
  }

  protected function renderPrevAndNextPages() {
    $currentRoute = \Drupal::routeMatch()->getRouteName();
    $routeArray = $this->routeArray();
    
    $previousRoute = NULL;
    $nextRoute = NULL;
    $currentRouteFound = FALSE;
    
    $this->prevAndNextPagesWalkArray($routeArray, $currentRoute, $previousRoute, $nextRoute, $currentRouteFound);
    
    $markup = '<div class="docs-nav-wrapper">';
    if ($previousRoute) {
      $markup .= '<div><h3>' . t('Previous') .'</h3><p>' . $this->inlineLink($previousRoute) . '</p></div>';
    }
    
    if ($nextRoute) {
      $markup .= '<div><h3>' . t('Next') .'</h3><p>' . $this->inlineLink($nextRoute) . '</p></div>';
    }
    $markup .= '</div>';
    
    return $markup;
  }

  protected function prevAndNextPagesWalkArray($array, $currentRoute, &$previousRoute, &$nextRoute, &$currentRouteFound) {
    foreach ($array as $route) {
        
      if (empty($nextRoute) && $currentRouteFound) {
        $nextRoute = $route['name'];
      }

      if ('civic_theme_docs.' . $route['name'] == $currentRoute) {
        $currentRouteFound = TRUE;
      } else {
        if (!$currentRouteFound) {
          $previousRoute = $route['name'];
        }

        if (isset($route['subroutes'])) {
          $this->prevAndNextPagesWalkArray($route['subroutes'], $currentRoute, $previousRoute, $nextRoute, $currentRouteFound);
        }
      }
    }
  }

  protected function renderRouteArray($subarray = NULL) {
    $markup = '<ol>';
    $routes = $this->routeArray();

    if (isset($subarray)) {
      foreach ($routes as $route) {
        if ($route['name'] === $subarray) {
          foreach ($route['subroutes'] as $subroute) {
            $markup .= $this->liLink($subroute['name']);
          }
          $markup .= '</ol>';
          return $markup;
        }
      }
    }

    foreach($routes as $route) {
      
      if (isset($route['subroutes'])) {
        $markup .= $this->liLink($route['name'], TRUE);
        foreach ($route['subroutes'] as $subroute) {  
          $markup .= $this->liLink($subroute['name']);
        }
        $markup .= '</ol></li>';
      } else {
        $markup .= $this->liLink($route['name']);
      }
    }

    $markup .= '</ol></li></ol>';
    return $markup;
  }

  public function overview() {
    $markup = '<h2>' . t('Civic Theme') . '</h2>';
    $markup .= $this->renderRouteArray();    
    $markup .= '<br><h2>' . t('Custom Modules') . '</h2>';
    $markup .= '<p>' . t('A list of all available module overviews can be found on the default <a href="/:helplink">help page</a>. Here is a list of all the Custom Modules shipped with Civic Starter:', [':helplink' => 'admin/help']) . '</p>';
    $markup .= '<ol>';
    $markup .= '<li>' . $this->moduleLink('autocomplete_inputs') . '</li>';
    $markup .= '<li>' . $this->moduleLink('civic_mega_menu') . '</li>';
    $markup .= '<li>' . $this->moduleLink('ckeditor_bootstrap_grid_extended') . '</li>';
    $markup .= '<li>' . $this->moduleLink('geolocation_filter') . '</li>';
    $markup .= '<li>' . $this->moduleLink('menu_translate') . '</li>';
    $markup .= '<li>' . $this->moduleLink('mod_schedule_pub') . '</li>';
    $markup .= '<li>' . $this->moduleLink('private_pages') . '</li>';
    $markup .= '<li>' . $this->moduleLink('text_alert') . '</li>';
    $markup .= '</ol>';

    return ['#markup' => $markup];
  }

  public function assetsOverview() {
    $markup = '';
    $markup .= '<div data-iframe-append-src="https://www.youtube.com/embed/eRYfsxs0VrQ"></div>';
    $markup .= $this->renderRouteArray('assets');

    return ['#markup' => $markup];
  }

  public function webpackScssAndJs() {
    $markup = '';
    $markup .= '<h2>' . t('Installing Node Modules') . '</h2>';
    $markup .= '<p>' . t('If you\'ve just cloned the project navigate to ') . '<code>/web/themes/civic</code>' . t(' and run the following command:') . '</p>';
    $markup .= '<code class="code-block">npm install</code>';
    $markup .= '<p>' . t('This command looks in the same directory for the ') . '<code>package.json</code>' . t(' file, sees what dependencies are needed, and installs them into a ') . '<code>node_modules</code>' . t(' folder. This folder can become very large, which is why it is not tracked in the git repo.') . '</p>';
    $markup .= '<br><h2>' . t('Compiling Your SCSS and JS') . '</h2>';
    $markup .= '<p>' . t('Navigate to ') . '<code>/web/themes/civic</code>' . t(' and run the following command:') . '</p>';
    $markup .= '<code class="code-block">npm start</code>';
    $markup .= '<p>' . t('To avoid repetion, we will use ') . '<code>@theme</code>' . t(' to refer to the civic theme folder instead of ') . '<code>/web/themes/civic</code>.</p>';
    $markup .= '<br><h2>' . t('Understanding the Structure') . '</h2>';
    $markup .= '<p>' . t('One of the most important things to understand before making updates to the theme, is how JavaScript and CSS are structured. The only custom assets being served by the theme are:') . '</p>';
    $markup .= '<code class="code-block">
      @theme/dist/bundle.js<br>
      @theme/dist/main.css
    </code>';
    $markup .= '<p>' . t('The files inside of the ') . '<code>dist</code>' . t('folder should never be manually updated. These files are a compiled result of all the files within ') . '<code>@theme/src/js</code>' . t(' and ') . '<code>@theme/src/scss</code>.';
    
    $markup .= '<p>' . t('This module uses a bundler called ') . '<code>webpack</code>' . t(' to do this compilation, you can see the webpack configuration file at ') . '<code>@theme/webpack.config.js</code>.</p>';
    $markup .= $this->renderPrevAndNextPages();

    return ['#markup' => $markup];
  }

  public function scssStructure() {
    $markup = '';
    $markup .= '<div data-iframe-append-src="https://www.youtube.com/embed/Xr-YqnmMf3M"></div>';
    $markup .= '<h2>' . t('Overview') . '</h2>';
    $markup .= '<p>' . t('SCSS is a pre-processor for CSS with a lot of capabilities. It primarily helps with keeping style code organized and reducing repetition. Let\'s look at how the SCSS files are structured.') . '<p>';
    $markup .= '<p>' . t('The entry point to our SCSS files is <code>src/scss/main.scss</code>. This is the one file that is being pulled into our webpack compilation and from this file, all other SCSS files are imported. It\'s important whenever you create a new SCSS file, to make sure that you have included it here.') . '</p>';
    $markup .= '<p>' . t('From here the SCSS code is broken up into two folders, <code>modules</code> and <code>partials</code>.') . '</p>';
    $markup .= '<h2>' . t('SCSS Modules') . '</h2>';
    $markup .= '<p>' . t('This is not to be confused with Drupal modules, which is something completely different. The <code>modules</code> folders contains helper code that doesn\'t actually output any styles. Here we have <code>_mixins.scss</code> and <code>_variables.scss</code>. Mixins are bits of reusable SCSS (Kint of like functions for styles) that can be included in your other SCSS files by writing <code>@include NAME-OF-MIXIN</code>. The <code>_variables.scss</code> file contains variables that can be referenced throughout the rest of your styles. The most notable variables here are the `$primary-color` and `$secondary-color` variables. Try changing these and recompiling your styles, and you\'ll see that it updates several different components on your site.') . '</p>';
    $markup .= '<br><h2>' . t('SCSS Partials') . '</h2>';
    $markup .= '<p>' . t('Inside the <code>partials</code> folder, there are several more folders. These folders attempt to group the styles by the components which they apply to. This structure is similar to the folder structure inside of the theme\'s <code>templates</code> folder.') . '</p>';
    $markup .= $this->renderPrevAndNextPages();

    return ['#markup' => $markup];
  }

  public function jsStructure() {
    $markup .= '<h2>' . t('Overview') . '</h2>';
    $markup .= '<p>' . t('While SCSS is relatively easy to understand for someone who knows CSS, the JavaScript structure being used may be confusing for someone who has primarily used jQuery.') . '</p>';
    $markup .= '<p>' . t('The use of Webpack allows for using ES6, which are newer features and some syntactic sugar on top of regular JavaScript. It allows JavaScript to be more modular and object-oriented. Webpack is able to take that code and process it in a way that is backwards compatible for older browsers.') . '</p>';
    $markup .= '<br><h2>' . t('You Don\'t Have to Do It This Way') . '</h2>';
    $markup .= '<p>' . t('Before diving deeper into the JavaScript structure, it\'s important to say you don\'t have to follow this structure if you don\'t feel comfortable with it. The easiest alternative is to create another javascript file right next to `index.js`. For example, add `@theme/src/js/custom.js`. Then go to `civic.libraries.yml` and add the following line right under `dist/bundle.js`.') . '</p>';
    $markup .= '<code class="code-block">
      src/js/custom.js:{}
    </code>';
    $markup .= '<p>' . t('Then you can add all the JavaScript you want in this file, and leave alone the existing JavaScript code in ') . '<code>src/js/index.js</code>' . t(' and ') . '<code>src/js/classes</code>' . t('. Your new javascript file will not be included in Webpack\'s compilation process.') . '</p>';
    $markup .= '<br><h2>' . t('Understanding index.js') . '</h2>';
    $markup .= '<p>' . t('The syntax in this file utilizes ES6, which is simply newer features of the JavaScript language. This syntax allows for the JavaScript code to be more modular and object-oriented. Instead of just writing out free flowing JavaScript, we create a `class` and then instantiate that class. In `index.js`, a `class` named `CivicThemeIndex` is declared. In this class, methods are declared. One of these methods is `intantiateClasses`. This function instantiates classes that we pass to it and then calls their init method. If a selector is provided, the class will be instantiated once for every element found in the DOM. If no elements are found, then no classes will be instatiated. This helps prevent unnecessary JavaScript from being added to the page.') . '</p>';
    $markup .= '<h2>' . t('Adding Additional JavaScript Following This Pattern') . '</h2>';
    $markup .= '<p>' . t('To add additional javascript following this pattern, follow these steps:') . '</p>';
    $markup .= '<ol>';
    $markup .= '<li>' . t('Add a new file inside of ') . '<code>src/js/classes</code></li>';
    $markup .= '<li>' . t('In that new file, declare your class name.') . '</li>';
    $markup .= '<li>' . t('Add an ') . '<code>init</code>' . t(' method to be called on instantiation.') . '</li>';
    $markup .= '<li>' . t('Add a default export statement at the bottom of the file.') . '</li>';
    $markup .= '<li>' . t('At the top of ') . '<code>src/js/index.js</code>' . t(', import your new file.') . '</li>';
    $markup .= '<li>' . t('Add a call to ') . '<code>this.instantiateClasses</code>' . t(' in either ') . '<code>initOnDocumentLoad</code>' . t(' or ') . '<code>initOnWindowLoad</code>.</li>';
    $markup .= '<li>' . t('Pass the imported name of your class into the method.') . '</li>';
    $markup .= '<li>' . t('Optionally pass in a query selector to the method.') . '</li>';
    $markup .= '</ol>';
    $markup .= '<br><h3>' . t('Adding JavaScript Class Example') . '</h3>';
    $markup .= '<p><em>src/js/classes/example.js</em></p>';
    $markup .= '<code class="code-block">
      <span class="code-keyword">class</span> <span class="code-class">Example</span> {<br>
        &nbsp;&nbsp;<span class="code-function">constructor</span>(domEl) {<br>
          &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-keyword">this</span>.domEl = domEl;<br>
        &nbsp;&nbsp;}<br>
        <br>
        &nbsp;&nbsp;<span class="code-function">init</span>() {<br>
          &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-comment">// Do Something</span><br>
        &nbsp;&nbsp;}<br>
      }<br>
      <br>
      <span class="code-keyword">export default</span> <span class="code-class">Example</span>;
    </code>';
    $markup .= '<br><p><em>src/js/index.js</em></p>';
    $markup .= '<code class="code-block">
      <span class="code-keyword">import</span> <span class="code-class">Example</span> <span class="code-keyword">from</span> \'./classes/example.js\';<br>
      <br>
      class <span class="code-class">CivicThemeIndex</span> {<br>
          &nbsp;&nbsp;<span class="code-function">initOnDocumentLoad</span>() {<br>
          &nbsp;&nbsp;<span class="code-keyword">this</span>.<span class="code-function">instantiateClasses</span>(<span class="code-class">Example</span>, \'.example-element\');<br>
        }<br>
      <br>
      <span class="code-comment">// Rest of code not included</code><br>
    </code>';
    $markup .= $this->renderPrevAndNextPages();

    return ['#markup' => $markup];
  }

  public function themeComponentsOverview() {
    $markup = '';
    $markup .= '<h2>' . t('Overview') . '</h2>';
    $markup .= $this->renderRouteArray('components');
    
    return ['#markup' => $markup];
  }

  public function themeFile() {
    $markup = '';
    $markup .= '<div data-iframe-append-src="https://www.youtube.com/embed/uClVp4Gd2C4"></div>';
    $markup .= '<h2>' . t('The') . ' civic.theme ' . t('File') . '</h2>';
    $markup .= '<p>' . t('Open up <code>civic.theme</code> in the root of the theme folder. If you\'re familiar with Drupal theming, this file includes preprocess functions that "hook" into Drupal at certain points of it\'s processing and allow us to add, remove, or alter variables.') . '</p>';
    $markup .= '<p>' . t('To keep this file clean, all of the business logic has been extracted into PHP classes within the <code>/src</code> folder. If you open up one of these PHP classes, you\'ll see that each one is using public static functions with the same variables being passed through. Each function has a comment above stating what function in <code>civic.theme</code> is calling it.') . '</p>';
    
    $markup .= $this->renderPrevAndNextPages();

    return ['#markup' => $markup];
  }

  public function translations() {
    $markup = '<p>' . t('Translations involves two separate processes, that need to work together.') . '</p>';
    $markup .= '<ol>';
    $markup .= '<li>' . t('Google Translate') . '</li>';
    $markup .= '<li>' . t('Drupal\'s Professional Translations') . '</li>';
    $markup .= '</ol>';
    $markup .= '<p>' . t('Google Translate is an external script from Google that uses JavaScript to translate text on the page. Drupal\'s professional translations allow content creators to create different versions of a Node or a Block, and professionally write what the translation should be.') . '</p>';
    $markup .= '<p>' . t('When we try to get these two things to work in tandem, there are two issues to address:') . '</p>';
    $markup .= '<ol>';
    $markup .= '<li>' . t('If a node is professionally translated, we don\'t want Google to try to translate that translation, or it will be a mess.') . '</li>';
    $markup .= '<li>' . t('Even though a node can be professionally translated, we still want Google Translate to translate the other things on the page (Header, Footer, etc.).') . '</li>';
    $markup .= '</ol>';
    $markup .= '<p>' . t('These problems are addressed in the following three files:') . '</p>';
    $markup .= '<code class="code-block">
      templates/layout/html.html.twig<br>
      src/Translations.php<br>
      src/js/classes/Translations.js
    </code>';
    $markup .= '<p>' . t('In the html template, we start by adding a <code>notranslate</code> class to the html and body tags. Google Translate stores preferred translations in a cookie, which can have unexpected results. Adding these wrapper classes ensures that the translation won\'t happen until we tell it to.') . '</p>';
    $markup .= '<p>' . t('The PHP file looks to see what language is selected. If there\'s a professionally translated version of the current page that the user isn\'t viewing, it will redirect them there. It then sets variables to be read in our JavaScript about what the default language is, what the selected language is, and whether Google Translate should translate the page.') . '</p>';
    $markup .= '<p>' . t('In the JavaScript file, we check the variables to see if we\'re viewing a professionally translated page. If we are, then we add the <code>notranslate</code> class to the <code>content region</code>. Then we remove the <code>notranslate</code> class from the body and html tags and perform the Google Translate.') . '</p>';

    $markup .= $this->renderPrevAndNextPages();

    return ['#markup' => $markup];
  }

  public function modularPages() {
    $markup = '';
    $markup .= '<div data-iframe-append-src="https://www.youtube.com/embed/cWhd6B5rV0k"></div>';
    $markup .= '<h2>' . t('Overview') . '</h2>';
    $markup .= '<p>' . t('Modular pages is one of our content types. It is great for making home pages and landing pages, because you can drop in the components you need. It essentially alows you to drop in a several smaller templates instead of building the page from one single template. Each of these smaller templates that you drop in is a paragraph type.') . '</p>';
    $markup .= '<p>' . t('When you create a new Modular Page, you will see there is the <code>Title</code> and <code>Language</code> fields. The only other field is <code>Sections</code>. From here you can choose one of the available sections of <code>paragraph types</code> to add to the page. Once you have added in several, you can move their positions around by dragging and dropping. Let\'s look at each of the initially provided <code>paragraph types</code> in a little more detail.') . '</p>';
    $markup .= '<br><h3>' . t('Basic Section') . '</h3>';
    $markup .= '<p>' . t('This section provides a <code>Content</code> field with a CKEditor. One note here, the Text format <code>Basic HTML</code> has been configured to behave the same as <code>Full HTML</code>, meaning you can add icons, classes, etc. We have found in our other sites that we always give each content editor access to the <code>Full HTML</code> and almost every CKEditor section gets changed to that Text format, so this just saves the time and hassle.') . '</p>';
    $markup .= '<p>' . t('There is also a field at the bottom named ') . $this->inlineLink('additional_styles') . t('. You will see this show up on several of the <code>paragraph types</code>. This is explained as its own theme component ') . $this->inlineLink('additional_styles', 'here') . '.</p>';
    $markup .= '<br><h3>' . t('BG Image Section') . '</h3>';
    $markup .= '<p>' . t('This section is just like the <code>Basic Section</code>, though it also has a field to add a background image and a field to declare the background position. Using position ') . '<code>center</code>' . t(' looks best in most cases, though there will be situations to use other positions. You may also notice there is no ') . '<code>alt</code>' . t(' attribute for this image. This is because background images are decorative, they don\'t use ') . '<code>img</code>' . t(' tags, and don\'t use alternative text.') . '<p>';
    $markup .= '<br><h3>' . t('Embedded Blocks Section') . '</h3>';
    $markup .= '<p>' . t('The embedded blocks section allows you to embed blocks. This is very helpful for blocks that you want in the middle of your content. It is primarily designed to put ') . '<code>views</code>' . t(' into Modular Pages. A couple quick notes on this section.');
    $markup .= '<ol>';
    $markup .= '<li>' . t('If you place blocks in Column 1 and nothing in Column 2, those blocks will be full-width.') . '</li>';
    $markup .= '<li>' . t('A block needs to be placed somewhere in the Blocks layout page, before it can be selected here. It is recommended to put the block in the "No Location" region. Like it sounds, the "No Location" region is not rendered in any of the TWIG page templates, but allows a place for blocks to be referenced.') . '</li>';
    $markup .= '</ol>';
    $markup .= '<br><h3>' . t('Map Section') . '</h3>';
    $markup .= '<p>' . t('The Map Section integrates a Google Map onto the page and allows you to add custom location markers to that map. Additionally, there is built in functionality for dynamic filtering based on location category, city, and zipcode.') . '</p>';
    $markup .= '<br><h3>' . t('Search Section') . '</h3>';
    $markup .= '<p>' . t('If used at all, this section will typically only be used once or a twice in a website. It was designed to be the hero section of a website\'s homepage. It was also designed to be used in conjunction with the ' . $this->moduleLink('autocomplete_inputs') .' module.') . '</p>';

    $markup .= $this->renderPrevAndNextPages();

    return ['#markup' => $markup];
  }

  public function additionalStyles() {
    $markup = '<h2>' . t('Overview') . '</h2>';
    $markup .= '<p>' . t('One of the most customizable and expandable parts of this theme is the "Additional Styles" field. Several of the paragraph types have this field, here is how it works.');
    $markup .= '<ol>';
    $markup .= '<li>' . t('The field references taxonomy terms') . '</li>';
    $markup .= '<li>' . t('By default the taxonomy vocabulary that is referenced is "Programmed Styles"') . '</li>';
    $markup .= '<li>' . t('Each of these referenced taxonomy terms has a "Classes" field.') . '</li>';
    $markup .= '<li>' . t('Inside of the cooresponding paragraph\'s TWIG templates, they call the ') . '<code>additional_styles</code>' . t(' macro') . '</li>';
    $markup .= '<li>' . t('This macro loops through each referenced taxonomy term, gets the associated class or classes, and then adds those classes to the paragraph wrapper.') . '</li>';
    $markup .= '<li>' . t('Then those classes can be targeted for CSS styles.') . '</li>';
    $markup .= '<li>' . t('Some default styles can be found at ') . '<code>src/scss/partials/_programmed-styles.scss</code></li>';
    $markup .= '</ol>';
    $markup .= '<p>' . t('At first glance, this sounds kind of cumbersome, but it\'s a great way for the developer to control and expand what styles are available to content creators. You can see that the default ones have a naming convention of category, colon, and then value (Example: "Background: Primary Color"), which should make it easier for content creators to find the type of styles they\'re looking for.') . '</li>';
    $markup .= '<br><h2>' . t('Expanding the Additional Styles Functionality') . '</h2>';
    $markup .= '<p>' . t('Perhaps you want to have additional programmed styles that are only available for the "BG Image Section" paragraph type. Consider creating a new Vocabulary called "Programmed Styles: BG Image Section". Inside that vocabulary add the existing field ') . '<code>field_classes</code>' . t(', and then add a few terms.') . '</p>';
    $markup .= '<p>' . t('Then navigate to "Admin > Structure > Paragraphs > BG Image Section > Manage Fields". Click "Edit" for "Additional Styles". Then under "Reference Type" check your new vocabulary "Programmed Styles: BG Image Section". Now your new taxonomy terms can be added to this paragraph type, but not any of the other paragraph types.') . '</p>';
    $markup .= $this->renderPrevAndNextPages();

    return ['#markup' => $markup];
  }

  public function recurringEvents() {
    $markup = '';
    $markup .= '<div data-iframe-append-src="https://www.youtube.com/embed/CSfmWVF3PRw"></div>';
    $markup .= '<p>' . t('A common requested feature is to make an event recurring. Like an event that happens every Monday or the first Friday of every month.') . '</p>';

    $markup .= '<h2>' . t('For the Content Creators') . '</h2>';
    
    $markup .= '<br><h3>' . t('Single Node') . '</h3>';
    $markup .= '<p>' . t('The key thing to understand is that there is not a separate node or page for every occurrence of the event. Rather there is one node with one recurring date field. That date field then contains the multiple dates of our event. When creating or editing an event node, you can add the date, choose if it happens all day, choose whether or not to make it recurring, and then set my recurring pattern.') . '</p>';
    
    $markup .= '<h3>' . t('Date Specific Content') . '</h3>';
    $markup .= '<p>' . t('Sometimes, in addition to the general event description, you might want additional content for one specific occurrence of the date. To handle that, we add a <code>Date Specific Content</code> item. Pick which date you want this content to show up on, then fill in the content you want. Make sure that you’ve done your math correctly and the selected date is one of the date occurrences, or the content will never show up.') . '</p> ';
    
    $markup .= '<h3>' . t('Two Major Questions') . '</h3>';
    $markup .= '<p>' . t('Based on that, it brings up two major questions. One, how do we make views of events show each instance of an event? And two, how do me make sure viewing an individual page contains the right information.') . '</p> ';
    
    $markup .= '<h3>' . t('Multiple Items instead of just one') . '</h3> ';
    $markup .= '<p>' . t('Let’s look at our Events page view. You can see that each occurrence of the event shows up as it’s own item instead of just one. Let’s take a look at the view configuration to see how this was accomplished.') . '</p> ';
    
    
    $markup .= '<h3>' . t('Relationship Property') . '</h3>';
    $markup .= '<p>' . t('The key here is using the “Relationship” property. This then allows us to access the start and end time fields for each date occurrence. This causes the view to return a separate entry for each occurrence.') . '</p>';
    
    $markup .= '<h3>' . t('Displaying Fields vs. Displaying Content') . '</h3> ';
    $markup .= '<p>' . t('A quick thing to note here, is we are using fields. Some views just return the content itself, and then the output is defined in the content display mode and the corresponding TWIG template. If we did that, we would not have access to this relationship. We need to use fields in order to get these occurences.') . '</p>';
    
    $markup .= '<p>' . t('In this case we’re getting a bunch of fields, setting them to hidden, and then using Custom Text as our last field to create a template that incorporates these fields. I’ll come back to go over this in more detail when I get to the Developer part of the video.') . '</p> ';
    
    $markup .= '<h3>' . t('Using the Date Query to Show the Correct Occurence	') . '</h3>';

    $markup .= '<p>' . t('Let’s now answer the next question of how to show the correct information on an individual page. As I mentioned, there is actually only one node or one page. That means that this page (Show one occurrence) and this page (show another occurrence) are actually the same page. The reason we have different dates and information, is because of this query string up here. Now a query is everything that happens after this question mark. The question mark tells the browser that this is the end of the address and everything after it is additional information to be read by the page.') . '</p>';
    
    
    $markup .= '<h3>' . t('Key/Value Pairs in Query String') . '</h3>';
    $markup .= '<p>' . t('Then we have variables or keys and their values. Here you can see we have values for start and end. This information is read and parsed in the backend code and supplies us with the correct date to our TWIG template, so it can be rendered here.') . '<p>';
    
    $markup .= '<h3>' . t('First Instance without Query') . '</h3> ';
    $markup .= '<p>' . t('If I visit this page without the query string, it takes me to the first occurrence of that event. This means it is very important to ensure that every link to an event page also contains a query with the date information to ensure the right version of the page is displayed.');
    
    $markup .= '<h3>' . t('Each link has different query') . '</h3>';
    $markup .= '<p>' . t('If I go back to the events page, you can see down here at the bottom that each of these links contains a different query string.');
    
    $markup .= '<br><br><h2>' . t('For the Developers') . '</h2>';
    
    $markup .= '<br><h3>' . t('Two Contributed Modules: Date Recur, Date Recur Modular Widget Framework') . '</h3>';
    $markup .= '<p>' . t('This is accomplished with two contributed modules and some custom functionality within our theme. The first contributed module is <code>Date Recur</code>. This is what allows us to have this field and properly stores multiple dates in the database and can then be used as a relationship inside our view. The second contributed module is <code>Date Recur Modular Widget Framework</code>. That module gives us this nice widget for picking our recurring date pattern. Without this module, there is a just a text box and we would need to write the proper iCal format for the pattern we want.') . '</p>';
    
    $markup .= '<h3>' . t('Custom Theme Functionality') . '</h3>';
    $markup .= '<p>' . t('Let’s take a look at how the theme code parses the queries to supply the correct information to our TWIG template.') . '</p>';
    
    $markup .= '<h3>' . t('Node Preprocess, No Caching') . '</h3>';
    $markup .= '<p>' . t('The process starts in the theme’s preprocess node function. First we check to see if the node type is an event. If it’s an event, it’s very important that we tell Drupal not to cache it by setting cache, max-age to zero. Drupal caches the compiled TWIG templates for faster delivery, but we don’t want to do that with events. Because every occurrence is the same node, if we cache it, then the first node visited after clearing the cache will show the correct date, but then any other link we click will show that same date. The event page needs to be dynamically built every time it’s visited.') . '</p>';
    
    $markup .= '<h3>' . t('Get Date Field') . '</h3>';
    $markup .= '<p>' . t('Make sure our field event date is set.') . '</p>';
    
    $markup .= '<h3>' . t('Check if Start Date is supplied') . '</h3>';
    $markup .= '<p>' . t('If it’s supplied that we will set our start date. If it’s not then we will get the start date from the first instance. The date recur field stores date occurrences in UTC and then stores the desired Timezone alongside of it. So you can see here we set the time in UTC then set the timezone ') . '</p>';

    $markup .= $this->renderPrevAndNextPages();
    
    return ['#markup' => $markup];
  }
}