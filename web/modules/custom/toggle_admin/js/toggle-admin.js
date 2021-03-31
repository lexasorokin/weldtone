function ToggleAdmin() {
  this.body = document.querySelector('body');
  this.nodeId = drupalSettings.toggleAdmin && drupalSettings.toggleAdmin.nodeId;
  this.toggleLocalTasks = drupalSettings.toggleLocalTasks;
  this.enabledButtons = drupalSettings.toggleAdmin ? drupalSettings.toggleAdmin.enabledButtons : {};
  this.buttonContainer = document.createElement('div');
  this.clearCacheButton = document.querySelector('a[data-drupal-link-system-path="admin/flush"]');
  this.runCronButton = document.querySelector('a[data-drupal-link-system-path="run-cron"]');
  this.dragging = false;
  this.bottom = 20;
  this.right = 20;
  this.icons = {
    'edit-page': '<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="pencil-alt" class="svg-inline--fa fa-pencil-alt fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M497.9 142.1l-46.1 46.1c-4.7 4.7-12.3 4.7-17 0l-111-111c-4.7-4.7-4.7-12.3 0-17l46.1-46.1c18.7-18.7 49.1-18.7 67.9 0l60.1 60.1c18.8 18.7 18.8 49.1 0 67.9zM284.2 99.8L21.6 362.4.4 483.9c-2.9 16.4 11.4 30.6 27.8 27.8l121.5-21.3 262.6-262.6c4.7-4.7 4.7-12.3 0-17l-111-111c-4.8-4.7-12.4-4.7-17.1 0zM124.1 339.9c-5.5-5.5-5.5-14.3 0-19.8l154-154c5.5-5.5 14.3-5.5 19.8 0s5.5 14.3 0 19.8l-154 154c-5.5 5.5-14.3 5.5-19.8 0zM88 424h48v36.3l-64.5 11.3-31.1-31.1L51.7 376H88v48z"></path></svg>',
    'clear-cash': '<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="trash" class="svg-inline--fa fa-trash fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M432 32H312l-9.4-18.7A24 24 0 0 0 281.1 0H166.8a23.72 23.72 0 0 0-21.4 13.3L136 32H16A16 16 0 0 0 0 48v32a16 16 0 0 0 16 16h416a16 16 0 0 0 16-16V48a16 16 0 0 0-16-16zM53.2 467a48 48 0 0 0 47.9 45h245.8a48 48 0 0 0 47.9-45L416 128H32z"></path></svg>',
    'run-cron': '<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="clock" class="svg-inline--fa fa-clock fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M256,8C119,8,8,119,8,256S119,504,256,504,504,393,504,256,393,8,256,8Zm92.49,313h0l-20,25a16,16,0,0,1-22.49,2.5h0l-67-49.72a40,40,0,0,1-15-31.23V112a16,16,0,0,1,16-16h32a16,16,0,0,1,16,16V256l58,42.5A16,16,0,0,1,348.49,321Z"></path></svg>',
    'recent-logs': '<svg aria-hidden="true" focusable="false" data-prefix="far" data-icon="list-alt" class="svg-inline--fa fa-list-alt fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M464 32H48C21.49 32 0 53.49 0 80v352c0 26.51 21.49 48 48 48h416c26.51 0 48-21.49 48-48V80c0-26.51-21.49-48-48-48zm-6 400H54a6 6 0 0 1-6-6V86a6 6 0 0 1 6-6h404a6 6 0 0 1 6 6v340a6 6 0 0 1-6 6zm-42-92v24c0 6.627-5.373 12-12 12H204c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h200c6.627 0 12 5.373 12 12zm0-96v24c0 6.627-5.373 12-12 12H204c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h200c6.627 0 12 5.373 12 12zm0-96v24c0 6.627-5.373 12-12 12H204c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h200c6.627 0 12 5.373 12 12zm-252 12c0 19.882-16.118 36-36 36s-36-16.118-36-36 16.118-36 36-36 36 16.118 36 36zm0 96c0 19.882-16.118 36-36 36s-36-16.118-36-36 16.118-36 36-36 36 16.118 36 36zm0 96c0 19.882-16.118 36-36 36s-36-16.118-36-36 16.118-36 36-36 36 16.118 36 36z"></path></svg>',
    'admin-toggle': '<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="bars" class="svg-inline--fa fa-bars fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M16 132h416c8.837 0 16-7.163 16-16V76c0-8.837-7.163-16-16-16H16C7.163 60 0 67.163 0 76v40c0 8.837 7.163 16 16 16zm0 160h416c8.837 0 16-7.163 16-16v-40c0-8.837-7.163-16-16-16H16c-8.837 0-16 7.163-16 16v40c0 8.837 7.163 16 16 16zm0 160h416c8.837 0 16-7.163 16-16v-40c0-8.837-7.163-16-16-16H16c-8.837 0-16 7.163-16 16v40c0 8.837 7.163 16 16 16z"></path></svg>',
    'settings': '<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="cog" class="svg-inline--fa fa-cog fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M487.4 315.7l-42.6-24.6c4.3-23.2 4.3-47 0-70.2l42.6-24.6c4.9-2.8 7.1-8.6 5.5-14-11.1-35.6-30-67.8-54.7-94.6-3.8-4.1-10-5.1-14.8-2.3L380.8 110c-17.9-15.4-38.5-27.3-60.8-35.1V25.8c0-5.6-3.9-10.5-9.4-11.7-36.7-8.2-74.3-7.8-109.2 0-5.5 1.2-9.4 6.1-9.4 11.7V75c-22.2 7.9-42.8 19.8-60.8 35.1L88.7 85.5c-4.9-2.8-11-1.9-14.8 2.3-24.7 26.7-43.6 58.9-54.7 94.6-1.7 5.4.6 11.2 5.5 14L67.3 221c-4.3 23.2-4.3 47 0 70.2l-42.6 24.6c-4.9 2.8-7.1 8.6-5.5 14 11.1 35.6 30 67.8 54.7 94.6 3.8 4.1 10 5.1 14.8 2.3l42.6-24.6c17.9 15.4 38.5 27.3 60.8 35.1v49.2c0 5.6 3.9 10.5 9.4 11.7 36.7 8.2 74.3 7.8 109.2 0 5.5-1.2 9.4-6.1 9.4-11.7v-49.2c22.2-7.9 42.8-19.8 60.8-35.1l42.6 24.6c4.9 2.8 11 1.9 14.8-2.3 24.7-26.7 43.6-58.9 54.7-94.6 1.5-5.5-.7-11.3-5.6-14.1zM256 336c-44.1 0-80-35.9-80-80s35.9-80 80-80 80 35.9 80 80-35.9 80-80 80z"></path></svg>',
  };

  this.init = function() {
    this.buttonContainer.classList.add('admin-control-buttons-container');
    this.body.appendChild(this.buttonContainer);

    this.createButtons();
    this.addKeyboardListeners();
    this.addDragFunctionality();
  }


  this.createButtons = function() {
    var that = this;

    if (this.nodeId && this.enabledButtons.edit) {
      this.createButton('edit-page', ['fas', 'fa-pencil-alt'], 'Edit Page', 'Alt + E', '/node/' + that.nodeId + '/edit');
    }

    if (this.enabledButtons.clearCache) {
      this.createButton('clear-cash', ['fas', 'fa-trash'], 'Clear Cache', 'Alt + C', function() {
        that.clearCacheButton.click();
      });
    }

    if (this.enabledButtons.cron) {
      this.createButton('run-cron', ['fas', 'fa-clock'], 'Run CRON', 'Alt + R', function() {
        that.runCronButton.click();
      });
    }

    if (this.enabledButtons.logs) {
      this.createButton('recent-logs', ['far', 'fa-list-alt'], 'Recent Logs', 'Alt + L', '/admin/reports/dblog');
    }

    this.createButton('admin-toggle', ['fas', 'fa-bars'], 'Toggle Admin Menu', 'Alt + T', function() {
      that.toggleAdmin();
    });

    this.createButton('settings', ['fas', 'fa-cog'], 'Toggle Admin Settings', '', '/admin/config/user-interface/toggle-admin');
  }

  this.toggleAdmin = function() {
    this.body.classList.toggle('toggle-admin');
    
    if (this.toggleLocalTasks) {
      this.body.classList.toggle('toggle-local-tasks');
    }
  }

  this.addKeyboardListeners = function() {
    var that = this;
    document.addEventListener('keydown', function(e) {
      // Action on "alt + c"      
      if (e.keyCode === 67 && e.altKey && that.enabledButtons.clearCache) {
        that.clearCacheButton.click();
      }

      // Action on "alt + e"
      if (e.keyCode === 69 && e.altKey && that.nodeId && that.enabledButtons.edit) {
        window.location = '/node/' + that.nodeId + '/edit';
      }

      // Action on "alt + l"
      if (e.keyCode === 76 && e.altKey && that.enabledButtons.logs) {
        window.location = '/admin/reports/dblog';
      }

      // Action on "alt + r"
      if (e.keyCode === 82 && e.altKey && that.enabledButtons.cron) {
        that.runCronButton.click();
      }

      // Action on "alt + t"
      if (e.keyCode === 84 && e.altKey) {
        that.toggleAdmin();
      }
    });
  }

  this.createButton = function(buttonClass, iconClasses, name, keyShortcut, onClick) {
    var that = this;
    var button = null;

    if (typeof(onClick) === 'function') {
      button = document.createElement('button');
      button.addEventListener('click', function() {
        if (that.dragging) {
          that.dragging = false;
        } else {
          onClick();
        }
      });
    } else {
      button = document.createElement('a');
      button.href = onClick;
      button.draggable = false;

      if (name === 'Recent Logs') {
        button.target = '_blank';
      }

      button.addEventListener('click', function(e) {
        if (that.dragging) {
          that.dragging = false;
          e.preventDefault();
        }
      });
    }
    
    button.classList.add('admin-control-button');
    button.name = name;
    button.setAttribute('aria-label', name);
    button.title = keyShortcut ? name + ' (' + keyShortcut + ')' : name;
    
    button.classList.add(buttonClass);
    button.innerHTML = this.icons[buttonClass];
    
    this.buttonContainer.appendChild(button);
  }

  this.addDragFunctionality = function() {
    var that = this;
    let x = 0
    let y = 0;

    this.buttonContainer.addEventListener('mousemove', function(e) { 
      // Check if the left mouse button is down
      if (e.buttons === 1) {
        that.buttonContainer.classList.add('dragging');
        // Don't start the drag funcionality until it's been dragged 5 pixels
        // This helps prevent small accidental dragging
        if (x > 5 || y > 5 || x < -5 || y < -5) {
          that.dragging = true;
          that.bottom -= e.movementY;
          that.right -= e.movementX;
          that.buttonContainer.style.bottom = that.bottom + 'px';
          that.buttonContainer.style.right = that.right + 'px';
        } else {
          x -= e.movementX;
          y -= e.movementY;
        }
      }
    });

    this.buttonContainer.addEventListener('mouseup', function() {
      that.buttonContainer.classList.remove('dragging');
      x = 0;
      y = 0;
    });
  }
}


document.addEventListener('DOMContentLoaded', function() {
  var toggleAdmin = new ToggleAdmin();
  toggleAdmin.init();
});