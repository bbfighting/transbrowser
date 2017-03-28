(function(window) { 
  window.PageSearch = {
    initialize: function() {
      $('#example_hs').DataTable();
      $('#example_mouse').DataTable();
    }
  };
})(window);

$(document).ready(function() {
  window.PageSearch.initialize();
});