var uri = window.location.toString();
if (uri.indexOf("?submission_message=") > 0) {
  var clean_uri = uri.substring(0, uri.indexOf('?submission_message='));
  // var clean_uri = uri.replace('?webform_redirect=true', '');
  window.history.replaceState({path:clean_uri}, '', clean_uri);
}