<?php

namespace Drupal\civic;

use Symfony\Component\HttpFoundation\RedirectResponse;

class Translations {

  /**
   * Called from THEME_preprocess_page()
   */
  public static function processTranslations(&$vars) {
    // Get Default and Selected LangCodes
    $defaultLangCode = \Drupal::languageManager()->getDefaultLanguage()->getId();

    // Set Selected LangCode if value is available
    $tempstore = \Drupal::service('user.private_tempstore')->get('allegheny_translation');
    $selectedLangCode = \Drupal::languageManager()->getCurrentLanguage()->getId();

    $lns = \Drupal::service('language_negotiator');

    $prefixes = \Drupal::config('language.negotiation')->get('url.prefixes');

    if (!empty($prefixes['en'])) {
      unset($prefixes['en']);

      $prefixes = array_keys($prefixes);
    }


    $request_uri = $_SERVER['REQUEST_URI'];
    
    $uri_exploded = explode('/', $request_uri);

    $url_langcode = 'en';

    if (in_array($uri_exploded[1], $prefixes)) {
      $url_langcode = $uri_exploded[1];
    }

    $selectedLangCode = $url_langcode;

    // If the LangCode is available, save it
    if ($selectedLangCode) {
      $tempstore->set('langcode', $selectedLangCode);
    }
    else {
      // If the LangCode is not available, check and see if one is saved.
      $selectedLangCode = $tempstore->get('langcode');
    }

    if ($selectedLangCode) {
      // Attach the translation javascript and set the flag to use google translate
      // $vars['#attached']['library'][] = 'allegheny/translation';

      if ($selectedLangCode != 'en') {
        $vars['#attached']['drupalSettings']['doGoogleTranslate'] = 'yes';
      }

      // Attach these variables to be available in the JavaScript
      $vars['#attached']['drupalSettings']['defaultLangCode'] = $defaultLangCode;
      $vars['#attached']['drupalSettings']['selectedLangCode'] = $selectedLangCode;

      if (!empty($vars['node']) && $vars['node'] instanceof \Drupal\node\Entity\Node) {
        // Check for a professionally translated version of node for the selected language
        $proTranslationAvailable = $vars['node']->hasTranslation($selectedLangCode);
        $vars['#attached']['drupalSettings']['proTranslationAvailable'] = $proTranslationAvailable;

        // Check if the current route is the pro translation for the selected lanague
        $onProTranslation = \Drupal::languageManager()->getCurrentLanguage()->getId() ==
            $selectedLangCode;

        // If there is pro version and we're not viewing it, navigate to it.
        if ($proTranslationAvailable && !$onProTranslation) {
          $translation = $vars['node']->getTranslation($selectedLangCode);
          $url = $translation->toUrl('canonical')->toString();
          $response = new RedirectResponse($url, 302);
        //  $response->send();
        }
      }
    }
  }

}
