(function(window) { 
  window.PageSearch = {
    initialize: function() {
      $("a[name=contact]").attr("class","active-menu");
    }
  };
})(window);

$(document).ready(function() {
  window.PageSearch.initialize();
});