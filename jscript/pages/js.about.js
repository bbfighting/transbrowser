(function(window) { 
  window.PageSearch = {
    initialize: function() {
      $("a[name=about]").attr("class","active-menu");
    }
  };
})(window);

$(document).ready(function() {
  window.PageSearch.initialize();
});