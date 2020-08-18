class FixedHeader {
  constructor(header) {
    this.header = header;
    this.layoutContainer = document.querySelector('.layout-container');
    this.swapAbsoluteAndFixed = this.swapAbsoluteAndFixed.bind(this);
  }

  init() {
    window.addEventListener('scroll', this.swapAbsoluteAndFixed);
    this.swapAbsoluteAndFixed();
  }

  swapAbsoluteAndFixed() {
    const top  = this.layoutContainer.getBoundingClientRect().top;
    const isCurrentlyFixed = this.header.classList.contains('is-fixed');

    if (top >= 0 && isCurrentlyFixed) {
      this.header.classList.remove('is-fixed');
    } else if (top < 0 && !isCurrentlyFixed) {
      this.header.classList.add('is-fixed');
    }
  }
}

export default FixedHeader;