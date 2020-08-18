(function($, Drupal) {
  /**
   * @constructor
   * @param {Object} input - The DOM input element 
   * @param {Array} options - All available autocomplete options (Created in autocomplete.module)
   * @param {Object} bgColors - Contains the bg color(s) for autocomplete options
   */
  function AutocompleteInput(input, options, bgColors) {
    this.inputEl = input;
    this.options = options;
    this.bgColors = bgColors;
    this.previousLength = 0;
    this.activeOptions = [];
    this.optionsContainerEl = null;
    this.focusedOption = null;

    this.init = function() {
      // Disable the default autocomplete
      this.inputEl.setAttribute('autocomplete', 'off');
      this.inputEl.parentElement.style.position = 'relative';
      this.inputEl.addEventListener('input', this.handleInputChange.bind(this));
      this.inputEl.addEventListener('keydown', this.handleKeyPressOnInput.bind(this));
      document.addEventListener('click', this.removeRenderedOptions.bind(this));
      
      this.options = this.sortArray(this.options);
      this.inputEl.focus();
    }
    
    /**
     * @param {Array} array - All available autocomplete options
     * @return {Array} The sorter array
     */
    this.sortArray = function(array) {
      return array.sort(function(a, b) {
        if (a.text < b.text) {
          return -1;
        }
        if (a.text > b.text) {
          return 1;
        }
        return 0;
      });
    } 

    /**
     * This function is fired whenever the input value changes
     * 
     * If we have active options and the input value is
     * getting longer, then we only need to filter through
     * the options we already have.
     * 
     * If we don't have active options or the input value
     * is getting shorter, then we need to look through all
     * options for matches.
     */
    this.handleInputChange = function() {
      if (this.inputEl.value.length < 2) {
        this.activeOptions = [];
      } else if (this.inputEl.value.length < this.previousLength) {
        this.activeOptions = [];
        this.searchAllOptions();
      } else if (this.activeOptions.length) { 
          this.searchActiveOptions();
      } else if (this.inputEl.value.length < 3) {
        this.searchAllOptions();
      }

      this.previousLength = this.inputEl.value.length;

      this.removeRenderedOptions();
      if (this.activeOptions.length) {
        this.renderOptions();
      }
    }

    /**
     * Removes DOM Elements from the page
     */
    this.removeRenderedOptions = function() {
      if (this.optionsContainerEl) {
        this.optionsContainerEl.remove();
      }
    }

    /**
     * This function creates the DOM Elements
     * and appends them to the input options container
     * 
     * This may be unfamilar syntax if you are used to
     * creating DOM elements with jQuery -> $('<button type="button">).
     * Or with React and JSX -> return <button type="button">
     */
    this.renderOptions = function() {
      this.optionsContainerEl = document.createElement('div');
      this.optionsContainerEl.classList.add('autocomplete-input-options-container');
      this.inputEl.parentElement.appendChild(this.optionsContainerEl);

      var that = this;
      this.activeOptions.forEach(function(option, index) {
        var optionEl = document.createElement('button');
        optionEl.setAttribute('type', 'button');
        optionEl.classList.add('autocomplete-input-option');
        optionEl.style.backgroundColor = (index + 1) % 2 === 0 ? that.bgColors.even : that.bgColors.odd
        var spanEl = document.createElement('span');
        spanEl.innerText = option.text;
        optionEl.appendChild(spanEl);
        that.optionsContainerEl.appendChild(optionEl);
        optionEl.addEventListener('keydown', that.handleKeyPressOnOptions.bind(that));
        optionEl.addEventListener('click', function() {
          that.inputEl.value = option.text;
          that.inputEl.focus();
          that.handleInputChange();

          if (option.link) {
            if (drupalSettings.selectedLangCode == 'es') {
              window.location = '/es' + option.link;
            } else {
              window.location = option.link;
            }
          }
        });
      });
    }

    this.searchAllOptions = function() {
      var that = this;
      this.options.forEach(function(option) {
        if (option.text.toLowerCase().indexOf(that.inputEl.value.toLowerCase()) >= 0) {
          that.activeOptions.push(option);
        }
      });
    }

    /**
     * Adds keyboard controls
     */
    this.handleKeyPressOnInput = function(e) {
      if (this.activeOptions.length) {
        // Down Arrow
        if (e.keyCode === 40) {
          e.preventDefault();
          this.focusedOption = 0;
          this.optionsContainerEl.childNodes[this.focusedOption].focus();
        // Up Arrow
        } else if (e.keyCode === 38) {
          e.preventDefault();
          this.focusedOption = this.optionsContainerEl.childNodes.length - 1;
          this.optionsContainerEl.childNodes[this.focusedOption].focus();
        }
      }
    }

    /**
     * Adds keyboard controls
     */
    this.handleKeyPressOnOptions = function(e) {
      // Down Arrow
      if (e.keyCode === 40) {
        e.preventDefault();
        if (this.focusedOption === null || this.focusedOption + 1 >= this.optionsContainerEl.childNodes.length) {
          this.inputEl.focus();
          this.focusedOption = null;
        } else {
          this.focusedOption = this.focusedOption + 1;
          this.optionsContainerEl.childNodes[this.focusedOption].focus();
        }
      // Up Arrow
      } else if (e.keyCode === 38) {
        e.preventDefault();
        if (this.focusedOption === null || this.focusedOption === 0) {
          this.inputEl.focus();
          this.focusedOption = null;
        } else {
          this.focusedOption = this.focusedOption - 1;
          this.optionsContainerEl.childNodes[this.focusedOption].focus();
        }
      }
    }

    this.searchActiveOptions = function() {
      var newActiveOptions = [];

      var lowerInputVal = this.inputEl.value.toLowerCase();
      this.activeOptions.forEach(function(option) {
        if (lowerInputVal !== option.text.toLowerCase() && option.text.toLowerCase().indexOf(lowerInputVal) >= 0) {
          newActiveOptions.push(option);
        }
      });

      this.activeOptions = newActiveOptions;
    }
  }

  Drupal.behaviors.autocompleteInputs = {
    attach: function(context) {
      $(window).once('autocompleteInputsWindowLoad').on('load', function() {
        drupalSettings.autocompleteInputs.forEach(function(input) {
          var inputEl = document.querySelector(input.selector);
          if (inputEl) {
            var autocompleteInput = new AutocompleteInput(inputEl, input.autocompleteOptions, input.bgColors);
            autocompleteInput.init();
          }
        });
      });
    }
  }
})(jQuery, Drupal);