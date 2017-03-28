(function(window) { 
  var query_busy = false;

  var trans_to_str = function() {
    var value_array = [];
    $("input[name=transcription]:checked").each(function() {
      value_array.push($(this).attr("value"));
    })
    if (value_array.length == 0)
      var value_str = "no";
    else
      var value_str = value_array.join('_');
    return value_str;
  }   

  // The query action
  var query_action = function()
  {
    //alert(relation_web);
    if (!query_busy)
    {
      var temp_string = $('#query_box').val();
      var temp_type = $('#SelectType').val();

      query_busy = true;

      if (temp_string != "")
      {
        window.location.href = relation_web + "entrez/search/" + encodeURIComponent(temp_string) + "/" + encodeURIComponent(temp_type);
      }
    }

    query_busy = false;
  };

  var plot_graph = function(filename) {
    var imgsrc1 = relation_web + "textures/plot/" + filename + ".png";

    return $.Deferred (function (task) {
        var image = new Image();
        image.onload = function () {task.resolve(image);}
        image.onerror = function () {task.reject();}
        image.src = imgsrc1;
        image.useMap = "#direct";
    }).promise();
  }

  var plot_seqgraph = function(filename) {
    var imgsrc2 = relation_web + "textures/plot/seq_" + filename + ".png";

    return $.Deferred (function (task) {
        var image = new Image();
        image.onload = function () {task.resolve(image);}
        image.onerror = function () {task.reject();}
        image.src = imgsrc2;
        image.useMap = "#seq";
    }).promise();
    // $('.farseq').empty().append("<button type=\"button\" class=\"img-circle\"><img src=\"" + relation_web + "textures/refresh.png\"></button>");
  }

  var plot = function(cor1, cor2) {
    $('.far').append("<div class=\"overlay\"></div><div class=\"loading-img\"></div>");
    $('.farseq').append("<div class=\"overlay\"></div><div class=\"loading-img\"></div>");
    var data_sent = {};
    var time = $.now();
    data_sent['time'] = time;
    var ribosome = trans_to_str();
    var imgsrc1 = relation_web + "pages/plot/" + $("input[name=species]").val() + "/" + $("input[name=key]").val() + "/" + cor1 + "/" + cor2 + "/predict/" + ribosome;
    var filename = $("input[name=key]").val() + time;

            $.ajax({
              url: (imgsrc1),
              cache: false,
              dataType: "html",
              type: "POST",
              data: data_sent,
              success: function(response) {
                var contents_list = $.parseJSON(response);
                $.when(plot_graph(filename)).done(function (image) {
                  $('.far').empty().append("<div class=\"imgline\" style=\"position:relative\"></div>");
                  $('.far').append(image);
                  $('.far').append(contents_list['mapdirect']);

                  $(".cor_mouse").each(function() {
                        $(this).mouseover(function() {
                          var id = $(this).attr('id');

                          var coords = $(this).attr('coords').split(',');
                          var box = {
                            left: coords[0],
                            height: coords[3]
                          };
                          //$(this).attr("title", box.left);
                          // var position = $(this).offset();
                          // $(this).attr("title", position.left);
                          $('.imgline').append("<style>.box:before{content: \"" + id + "\";}</style>");
                          $('.imgline').append("<div class=\"box\" style=\"left:" + box.left + "px\"><div class=\"stright\" style=\"height:" + box.height + "px;border-left: 1px solid #1565AE; left:" + box.left + "px\"></div></div>");
                        });

                        $(this).mouseout(function() {
                          // $(this).removeAttr("title");
                          $('.imgline').empty();
                        });
                  });
                });
                $.when(plot_seqgraph(filename)).done(function (image) {
                  $('.farseq').empty().append(image);
                  $('.farseq').append(contents_list['mapseq']);
                });
              }
            });
  }

  window.PageSearch = {
    initialize: function() {
      // var GetSeq = function(value) {
      //   window.open(relation_web + 'getseq/' + value, null, 'height=300,width=550,status=no,toolbar=no,menubar=no,location=no,scrollbars=yes');
      // }


      var ex = ">Homo sapiens jun proto-oncogene (JUN), 5UTR mRNA\nGACAUCAUGGGCUAUUUUUAGGGGUUGACUGGUAGCAGAUAAGUGUUGAGCUCGGGCUGGAUAAGGGCUCAGAGUUGCACUGAGUGUGGCUGAAGCAGCGAGGCGGGAGUGGAGGUGCGCGGAGUCAGGCAGACAGACAGACACAGCCAGCCAGCCAGGUCGGCAGUAUAGUCCGAACUGCAAAUCUUAUUUUCUUUUCACCUUCUCUCUAACUGCCCAGAGCUAGCGCCUGUGGCUCCCGGGCUGGUGUUUCGGGAGUGUCCAGAGAGCCUGGUCUCCAGCCGCCCCCGGGAGGAGAGCCCUGCUGCCCAGGCGCUGUUGACAGCGGCGGAAAGCAGCGGUACCCACGCGCCCGCCGGGGGAAGUCGGCGAGCGGCUGCAGCAGCAAAGAACUUUCCCGGCUGGGAGGACCGGAGACAAGUGGCAGAGUCCCGGAGCGAACUUUUGCAAGCCUUUCCUGCGUCUUAGGCUUCUCCACGGCGGUAAAGACCAGAAGGCGGCGGAGAGCCACGCAAGAGAAGAAGGACGUGCGCUCAGCUUCGCUCGCACCGGUUGUUGAACUUGGGCGAGCGCGAGCCGCGGCUGCCGGGCGCCCCCUCCCCCUAGCAGCGGAGGAGGGGACAAGUCGUCGGAGUCCGGGCGGCCAAGACCCGCCGCCGGCCGGCCACUGCAGGGUCCGCACUGAUCCGCUCCGCGGGGAGAGCCGCUGCUCUGGGAAGUGAGUUCGCCUGCGGACUCCGAGGAACCGCUGCGCCCGAAGAGCGCUCAGUGAGUGACCGCGACUUUUCAAAGCCGGGUAGCGCGCGCGAGUCGACAAGUAAGAGUGCGGGAGGCAUCUUAAUUAACCCUGCGCUCCCUGGAGCGAGCUGGUGAGGAGGGCGCAGCGGGGACGACAGCCAGCGGGUGCGUGCGCUCUUAGAGAAACUUUCCCUGUCAAAGGCUCCGGGGGGCGCGGGUGUCCCCCGCUUGCCAGAGCCCUGUUGCGGCCCCGAAACUUGUGCGCGCAGCCCAAACUAACCUCACGUGAAGUGACGGACUGUUCUAUGACUGCAAAGAUG"
                  + "\n\n>NM_001286968\nGAGGCUAUAAGAGGGCGCACAAGUGGCGCGGCGCAGGAGCCGCCGCCAGUGGAGGGCCGGGCGCUGCGGCCGCGGCCGGGGCGGGCGCAGGGCCGAGCGGACGGGGGGGCGCGGGCCCCCCGGGAGGCCGCGGCCACUCCCCCCCGGGCCGGCGCGGCGGGGGAGGCGGAGGAUGGAAACACCCUUCUACGGCGAUGAGGCGCUGAGCGGCCUGGGCGGCGGCGCCAGUGGCAGCGGCGGCAGCUUCGCGUCCCCGGGCCGCUUGUUCCCCGGGGCGCCCCCGACGGCCGCGGCCGGCAGC";

      $("a[name=predict]").attr("class","active-menu");

      $('#example1').click(function(){
        document.getElementById("comment").value = ex;
      });

      $("#validate_region").click(function() {
        var limit = parseInt($( "input[name=cor1]" ).attr("placeholder"));
        var cor1 = parseInt($("input[name=cor1]").val() ? $("input[name=cor1]").val() : $( "input[name=cor1]" ).attr("placeholder"));
        var cor2 = parseInt($("input[name=cor2]").val() ? $("input[name=cor2]").val() : -1);

        if (cor2 < cor1 || cor1 < limit || cor2 > -1)
          $('#errorrange').empty().append("<div class=\"alert-box error\"><span>error: </span>Please input the range between " + limit + " ~ -1.</div>");
        else if (cor1 == cor2)
          $('#errorrange').empty().append("<div class=\"alert-box error\"><span>error: </span>Please input the different number.</div>");
        else
        {
          $('#errorrange').empty();
          plot(cor1, cor2);  
        }  
      });

      $("#predictradio").click(function() {
        var as_radio = $("input[type=radio]:checked").val();
        window.location.href = relation_web + 'predict/plot/' + as_radio;
      });


      if ($("img").length > 0) {
        var cor1 = 0;
        var cor2 = -1;

        plot(cor1, cor2);   
      }

      $("#example2").click(function() {
        window.open(relation_web + 'getexample', null, 'height=300,width=550,status=no,toolbar=no,menubar=no,location=no,scrollbars=yes');
      });

      // Register the event for enter key
      $(document).keypress(function(e)
      {
        if (e.which == '13' && $('#query_box').is(":focus"))
        {
          e.preventDefault();
          return false;
        }
      });      
    }
  };
})(window);

$(document).ready(function() {
  window.PageSearch.initialize();
});