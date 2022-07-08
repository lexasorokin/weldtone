class HeaderSearch {
  constructor(searchContainer) {
    this.searchForm;
    this.searchInput;
    this.searchContainer = searchContainer;
    this.searchButton = this.searchContainer.querySelector('#header-search-button');

    this.handleFormSubmit = this.handleFormSubmit.bind(this);
    this.handleInputChange = this.handleInputChange.bind(this);
    this.handleSearchButtonClick = this.handleSearchButtonClick.bind(this);
  }

  init() {
    this.createSearchForm();
    this.setEventListners();
    this.searchButton.setAttribute('aria-label', 'Open Search Form');
  }

  setEventListners() {
    this.searchForm.addEventListener('submit', this.handleFormSubmit);
    this.searchButton.addEventListener('click', this.handleSearchButtonClick);
    console.log(this.searchInput);
    this.searchInput.addEventListener('keyup', this.handleInputChange);
  }

  createSearchForm() {
    this.searchForm = document.createElement('form');
    this.searchForm.classList.add('header-search-form');

    this.searchInput = document.createElement('input');
    this.searchInput.id = 'search-keywords';
    this.searchInput.setAttribute('type', 'text');

    var searchInputLabel = document.createElement('label');
    searchInputLabel.classList.add('sr-only');
    searchInputLabel.setAttribute('for', 'search-keywords');
    searchInputLabel.innerText = 'Searh Keywords';

    this.searchForm.appendChild(searchInputLabel);
    this.searchForm.appendChild(this.searchInput);
    this.searchContainer.appendChild(this.searchForm);
  };

  handleInputChange() {
    if (this.searchInput.value.length > 0) {
      this.searchButton.setAttribute('aria-label', 'Submit Search Form');
    } else {
      this.searchButton.setAttribute('aria-label', 'Close Search Form');
    };
  }

  handleSearchButtonClick() {
    if (this.searchInput.value.length > 1) {
      this.handleFormSubmit();
    } else if (this.searchForm.classList.contains('active')) {
      this.searchButton.setAttribute('aria-label', 'Open Search Form');
      this.searchForm.classList.remove('active');
    } else {
      this.searchForm.classList.add('active');
      this.searchButton.setAttribute('aria-label', 'Close Search Form');
      this.searchInput.focus();
    }
  }

  handleFormSubmit(e) {
    if (e) {
      e.preventDefault();
    }
    
    window.location = `/search?keys=${escape(this.searchInput.value)}`;
  }
}

export default HeaderSearch;