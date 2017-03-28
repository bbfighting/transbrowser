(function(window) { 
  window.PageSearch = {
    initialize: function() {
      $("a[name=browse]").attr("class","active-menu");
      $("button[name=ch]").click(function() {
      	if ((".btn-danger")) {
      		$(".btn-danger").removeAttr( "class").attr("class", "btn btn-info");
      	}

      	$("#error").empty();
      	$(this).removeAttr( "class");
		$(this).attr("class", "btn btn-danger");
		//$(this).attr("id", "clickch");
      });

      $("button[type=reset]").click(function() {
      	if ((".btn-danger")) {
      		$(".btn-danger").removeAttr( "class").attr("class", "btn btn-info");
      	}

      	$("#error").empty();
      });

      $("#query_button").click(function() {
      	// if ((".btn-danger").val()) {
      	// 	var species = $('select[name=species]').val();
      	// }
      	// else
      	// {
      	// 	$("#error").append("Please click character");
      	// }
      	var ch = $('.btn-danger').val();

      	if (!ch) 
      		$("#error").append("Please click character");
      	else
      	{
      		var species = $('select[name=species]').val();
          $(".load").append("<fieldset class=\"beta psi\"><div class=\"far img\"><div class=\"overlay\"></div><div class=\"loading-img\"></div><img></fieldset>");
      		window.location.href = relation_web + 'browse/' + species + "/" + ch;
      	}
      });      
    }
  };
})(window);

$(document).ready(function() {
  window.PageSearch.initialize();
});