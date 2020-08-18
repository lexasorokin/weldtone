class Translations {
  constructor(dropdownWrapper) {
    this.translateLanguageCodes = ['es', 'ru', 'ar', 'zh-hans'];
    this.dropdownWrapper = dropdownWrapper;
    this.dropdownButton = dropdownWrapper.querySelector('.button.language-link')
    this.linkEndingsToNotTranslate = ['.pdf', '.doc', '.docx', '.png', '.jpg', '.jpeg', '.xls', '.psd', '.csv'];
    this.anchorTags = document.querySelectorAll('.layout-container main a');
  }
  
  init() {
    this.updateLinksToMatchLanguageCode();
    this.performGTranslate();
  }

  updateLinksToMatchLanguageCode() {
    const pathPrefix = location.pathname.split('/')[1];

    // Check if the pathname starts with a langcode
    if (pathPrefix.length === 2 && this.translateLanguageCodes.indexOf(pathPrefix) >= 0) {
      const origin = location.origin;
      const originWithLangCode = location.origin + '/' + pathPrefix;
      const originLength = location.origin.length;

      this.anchorTags.forEach((anchor) => {
        // Loop through the links to see if they're pointing to a file, in which case we don't change the link.
        let ignoreLink = false;
        this.linkEndingsToNotTranslate.forEach((linkEnding) => {
          if (anchor.href.indexOf(linkEnding) === anchor.href.length - linkEnding.length) {
            ignoreLink = true;
          }
        });

        // Check that the link is internal and that it doesn't already have the langcode prefix
        if (!ignoreLink && anchor.href.indexOf(origin) === 0 && anchor.href.indexOf(originWithLangCode) === -1) {
          anchor.href = originWithLangCode + anchor.href.slice(originLength);
        }
      });
    }
  }

  toggleTranslateStatus(selector, shouldTranslate) {
    const element = document.querySelector(selector);

    if (element) {
      if (shouldTranslate) {
        element.classList.remove('notranslate');
      } else {
        element.classList.add('notranslate');
      }
    }
  }

  performGTranslate() {
    if (drupalSettings.doGoogleTranslate === 'yes') {
      if (drupalSettings.defaultLangCode !== drupalSettings.selectedLangCode) {
        this.dropdownWrapper.classList.add('notranslate');

        this.toggleTranslateStatus('#toolbar-administration', false);
        this.toggleTranslateStatus('body', true);
        this.toggleTranslateStatus('html', true);

        if (drupalSettings.proTranslationAvailable) {
          this.toggleTranslateStatus('.region--content', false);
        }

        try {
          if (drupalSettings.selectedLangCode == 'zh-hans') {
            doGTranslate(drupalSettings.defaultLangCode + '|zh-CN');
          } else {
            doGTranslate(drupalSettings.defaultLangCode + '|' + drupalSettings.selectedLangCode);
          }
        } catch(e) {
          console.log('Translation Error.', e);
        }
      }
    } else {
      // This helps prevents other languages from getting stuck
      setTimeout(() => {
        doGTranslate('en|en');
      }, 5000);
    }
  }
}

export default Translations;