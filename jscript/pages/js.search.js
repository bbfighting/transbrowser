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
      var value_str = value_array.join('A');
    return value_str;
  }   

  // The query action
  var query_action = function()
  {
    //alert(relation_web);
    if (!query_busy)
    {
      var temp_species = $('select[name=species]').val();
      var temp_string = $('#query_box').val();

      query_busy = true;

      if (temp_string != "")
      {
        window.location.href = relation_web + "search/result/" + temp_species + "/" + encodeURIComponent(temp_string);
      }
    }

    query_busy = false;
  };

  var plot_graph = function(filename) {
    var imgsrc1 = relation_web + "textures/plot/" + filename + ".png";

    return $.Deferred (function (task) {
        var image = new Image();
        image.onload = function () {task.resolve(image);}
        image.onerror = function () {alert("ohnoseq");task.reject();}
        image.src = imgsrc1;
        image.useMap = "#direct";
    }).promise();
  }

  var plot_seqgraph = function(filename) {
    var imgsrc2 = relation_web + "textures/plot/seq_" + filename + ".png";

    return $.Deferred (function (task) {
        var image = new Image();
        image.onload = function () {task.resolve(image);}
        image.onerror = function () {alert("ohnoseq");task.reject();}
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
    var imgsrc1 = relation_web + "pages/plot/" + $("input[name=species]").val() + "/" + $("input[name=key]").val() + "/" + cor1 + "/" + cor2 + "/search/" + ribosome;
    
    console.log(imgsrc1);
    var filename = $("input[name=key]").val() + time;

            $.ajax({
              async: false,
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

  var asyncsearch = function() {
    var value = $('#query_box_text').val();
    var table_url = relation_web + "asyncs/search";
    var target_species = $('select[name=species]').val();

    if (value) {
      if ($(".resultload").length == 0 || $(".section-heading").length > 0) {
        $('#result').empty().append("<div class=\"resultload\"></div><section><div class=\"container\"><div class=\"tablerow\" id=\"content\"></div></div></section>");
      }

      $('.resultload').empty().append("<fieldset class=\"beta psi\"><div class=\"far img\"><div class=\"overlay\"></div><div class=\"loading-img\"></div><img></fieldset>");

      $.ajax({
        url: (table_url),
        cache: false,
        dataType: "html",
        type: "POST",
        data: {target_query: value, rel_web: relation_web, species: target_species},
        success: function(response) {
          $('.resultload').empty();
          $('#content').empty().append(response);
        }
      });             
    }
    else
    {
      $('.resultload').empty();
      $('#content').empty();            
    }
  }
 

  window.PageSearch = {
    initialize: function() {
      // var GetSeq = function(value) {
      //   window.open(relation_web + 'getseq/' + value, null, 'height=300,width=550,status=no,toolbar=no,menubar=no,location=no,scrollbars=yes');
      // }
      $('#example1').click(function(){
        document.getElementById("query_box_text").value = "Zdhhc5";
        // asyncsearch();
      });
      $("a[name=search]").attr("class","active-menu");
      $("input[id=all]").on("click", function(event) {
        if($("#all").prop("checked")){
          $("input[name=transcription]").prop("checked",true);
        }
        else{
          $("input[name=transcription]").prop("checked", false);
        }
      });

      $("#predictentrez").click(function() {
        var as_radio = $("input[type=radio]:checked").val();
        var species = $("input[name=species]").val();

        window.location.href = relation_web + 'search/plot/' + species + "/" + as_radio;
      });

      $("#validate_region").click(function() {
        var limit = parseInt($( "input[name=cor1]" ).attr("placeholder"));
        var cor1 = parseInt($("input[name=cor1]").val() ? $("input[name=cor1]").val() : $( "input[name=cor1]" ).attr("placeholder"));
        var cor2 = parseInt($("input[name=cor2]").val() ? $("input[name=cor2]").val() : -1);

        if (cor2 < cor1 || cor1 < limit || cor2 > -1)
          $('#errorrange').empty().append("error: Please input the range between " + limit + " ~ -1.");
        else if (cor1 == cor2)
          $('#errorrange').empty().append("error: Please input the different number.");
        else
        {
          $('#errorrange').empty();
          plot(cor1, cor2);  
        }  
      });

      $('#query_button').click(function() {
        asyncsearch();
      })

      // $('#query_button').click(function(e) {
      //   e.preventDefault();
      //   query_action();
      // })

      // if ($('#query_box').val())
      // {
      //   setInteval(function);
      //   asyncsearch();
      // }

      // $('#query_box').keypress(function(){}).keyup(function() { 
      //   asyncsearch();
      // }); 
      // $("select[name=species]").change(function() {
      //   asyncsearch();
      // })

      if ($("img").length > 0) {
        var cor1 = 0;
        var cor2 = -1;

        plot(cor1, cor2);
      }


      $("button[type=reset]").click(function() {
        $('#result').empty();
      })
      // $(".cor_mouse").each(function() {
      //       $(this).mouseover(function() {
      //         var id = $(this).attr('id');
      //         $(this).attr("title", id);

      //       });

      //       $(this).mouseout(function() {
      //         $(this).removeAttr( "title");
      //       });

      //   });

      // $(".cor_mouse")
      //   .mouseover(function() {
      //     var id = $(this).attr('id');
      //     $(this).attr("title", id);
      //   });
        // .mouseout(function() {
        //   $(this).removeAttr( "title" )
        // });

      // Register the event for enter key
      // $(document).keypress(function(e) {
      //   if (e.which == '13')
      //   {
      //     e.preventDefault();
      //     return false;
      //   }
      // });

      $("form").submit(function () {
        return false;
      });

      $(document).keypress(function(e) {
        if (e.which == '13' && $('#query_box_text').is(":focus")) {
          asyncsearch();
        }
      });

      // $(document).keypress(function(e) {
      //   if (e.which == '13' && $('#query_button').is(":focus"))
      //   {
      //     e.preventDefault();
      //     return false;
      //   }
      // });
    }
  };
  
})(window);

$(document).ready(function() {
  window.PageSearch.initialize();
});