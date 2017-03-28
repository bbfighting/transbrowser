(function(window) { 
  window.PageSearch = {
    initialize: function() {
      $("a[name=tutorial]").attr("class","active-menu");
    }
  };
})(window);

$(document).ready(function() {
  window.PageSearch.initialize();
});