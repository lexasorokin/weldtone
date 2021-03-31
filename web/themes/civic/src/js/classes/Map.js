class Map {
  constructor() {
    this.mapLocations = drupalSettings.mapLocations;
    this.filterZip = document.getElementById('filter-zip');
    this.filterCity = document.getElementById('filter-city');
    this.filterTax1 = document.getElementById('filter-taxonomy-1');
    this.filterTax2 = document.getElementById('filter-taxonomy-2');
    this.filtersSubmit = document.getElementById('map-filter-submit');
    this.filterMarkers = this.filterMarkers.bind(this);
  }
  
  init() {
    this.mapLocations.forEach((location) => this.createLocation(location));
    this.filtersSubmit.addEventListener('click', this.filterMarkers);
    // console.log('[Locations]', this.mapLocations);
  }

  filterMarkers(e) {
    e.preventDefault();

    this.mapLocations.forEach((location) => {
      if (this.zipFilter(location) 
        && this.cityFilter(location)
        && this.taxonomyFilter(location, this.filterTax1) 
        && this.taxonomyFilter(location, this.filterTax2)) {
        location.marker.setMap(map);
      } else {
        location.marker.setMap(null);
      }
    });
  }

  strip(string) {
    return string.toLowerCase().replace(/\s/g, '');
  }

  zipFilter(location) {
    if (this.filterZip === null) {
      return true;
    }
    
    return this.filterZip.value === '' || this.filterZip.value === location.zip;
  }

  cityFilter(location) {
    if (this.filterCity === null) {
      return true;
    }

    return this.filterCity.value === '' || this.strip(this.filterCity.value) === this.strip(location.city);
  }

  taxonomyFilter(location, taxFilter) {
    if (taxFilter === null || taxFilter.value === '0') {
      return true;
    }

    for (let i = 0; i < location.tags.length; i += 1) {
      if (location.tags[i].tid === taxFilter.value) {
        return true;
      }
    }
    
    return false;
  }

  createLocation(location) {
    const infowindow = new google.maps.InfoWindow({
      content: this.createInfoWindow(location),
    });

    const marker = new google.maps.Marker({
      position: {
        lat: parseFloat(location.lat),
        lng: parseFloat(location.lng),
      },
      map: map,
      title: location.name,
      // animation: google.maps.Animation.DROP,
    });

    marker.addListener('click', () => {
      infowindow.open(map, marker);
    });

    location.marker = marker;
  }

  generateDirectionsUrl(location) {
    let directionsUrl = 'https://google.com/maps/dir//';
    directionsUrl += location.addr.split(' ').join('+') + ',+';
    directionsUrl += location.city.split(' ').join('+') + ',+';
    directionsUrl += location.state + '+';
    directionsUrl += location.zip;
    directionsUrl += '/@' + location.lat + ',' + location.lng + ',15z';

    return directionsUrl;
  }

  renderTagIcons(tags) {
    return tags.reduce((html, tag) => {
      switch (tag.iconType) {
        case 'icon':
          html += `<div class="pl-3"><i class="${tag.iconValue} fa-3x"></i></div>`;
          return html;
        case 'image':
          html += `<img class="pl-3" src="${tag.iconValue}">`
          return html;
        default:
          return html;
      }
    }, '');
  }

  createInfoWindow(location) {
    return `
      <div class="map-info-window">
        <div class="address-container">
          <h5>${location.name}</h5>
          <p>
            ${location.addr}<br>
            ${location.city}, ${location.state} ${location.zip}
          </p>
        </div>
        <div class="d-flex">
          <div>
            <button class="btn btn-primary btn-sm mb-1">View &#8227;</button>
            <br>
            <a class="btn btn-primary btn-sm" target="_blank" href="${this.generateDirectionsUrl(location)}">
              Get Directions &#8227;
            </a>
          </div>
          <div class="">
            <div class="flex justify-start align-center map-icons-container">
              ${this.renderTagIcons(location.tags)}
            </div>
          </div>
        </div>
      </div>`; 
  }
}

export default Map;