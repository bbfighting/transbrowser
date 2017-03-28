<!DOCTYPE html>
<html lang="en" style="overflow: auto;">
  <head>
    <!-- This part should be adjusted <base href="http://cosbi2.ee.ncku.edu.tw/hahaha/"> -->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="pragma" content="no-cache" />
    <link rel="icon" href="<?php echo WEBSITE_PATH;?>textures/research.ico">

    <title>Translation Browser</title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo WEBSITE_PATH;?>css/bootstrap.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="jscript/ie8.responsive.file.warning.js"></script><![endif]-->
    <script src="<?php echo WEBSITE_PATH;?>jscript/ie.emulation.modes.warning.js"></script>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- font Awesome -->
    <link href="<?php echo WEBSITE_PATH;?>css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Custom styles for this template -->
    <link href="<?php echo WEBSITE_PATH;?>css/carousel.css" rel="stylesheet">
    <link href="<?php echo WEBSITE_PATH;?>css/agency.css" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Kaushan+Script' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700' rel='stylesheet' type='text/css'>
    <script>
        var relation_web = "<?php echo WEBSITE_PATH;?>"
        function GetSeq(value) {
            window.open(relation_web + 'getseq/' + value, null, 'height=300,width=550,status=no,toolbar=no,menubar=no,location=no,scrollbars=yes');
        }
    </script>
    <style>
        p {white-space: pre;}
    </style>
  </head>
<!-- NAVBAR
================================================== -->
  <body style="min-width: 1300px">
    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand page-scroll" href="<?php echo WEBSITE_PATH;?>home">IRES Omnibus</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li>
                          <a class="page-scroll" href="<?php echo WEBSITE_PATH;?>home">Home</a>
                    </li>
                    <li> 
                        <a class="page-scroll" href="<?php echo WEBSITE_PATH;?>predict">Predict</a>
                    </li>
                    <li class="active">
                        <a class="page-scroll" href="<?php echo WEBSITE_PATH;?>entrez">Entrez</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="<?php echo WEBSITE_PATH;?>tutorial">Tutorial</a>
                    </li>   
                    <li>
                        <a class="page-scroll" href="<?php echo WEBSITE_PATH;?>about">About</a>
                    </li>                 
                    <li>
                        <a class="page-scroll" href="<?php echo WEBSITE_PATH;?>contact">Contact</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>


    <!-- Carousel
    ================================================== -->
    <div class="carousel">
      <!-- Indicators -->
      <div class="carousel-inner">
        <div class="item active">
          <div class="container">
            <div class="carousel-caption">
              <h1>Entrez</h1>
                <form>
                    <div class="box-body">
                        <div class="form-inline">
<!--                             <div class="form-group">
                                <select name="SelectSpecies" id="SelectSpecies" onchange="s_reset()" class="form-control">
                                    <option value="hs" selected="selected">Human</option>
                                    <option value="mus">Mouse</option>
                                    <option value="rat">Rat</option>
                                    <option value="chicken">Chicken</option>
                                    <option value="cattle">Cattle</option>
                                    <option value="pig">Pig</option>
                                    <option value="sheep">Sheep</option>
                                    <option value="dog">Dog</option>
                                    <option value="cat">Cat</option>
                                    <option value="drosophila">Drosophila</option>
                                    <option value="c_elegans">C_elegans</option>
                                </select>
                            </div> -->
<!--                             <div class="form-group">
                                <select name="SelectType" id="SelectType" class="form-control">
                                    <option value="official_symbol" selected="selected">Official Symbol</option>
                                    <option value="fullname">Full Name</option>
                                    <option value="id">Gene ID</option>
                                </select>
                            </div> -->
                            <div class="form-group">
                                <input name="TermTextField" id="query_box" type="text" class="form-control" autocomplete="off" size="40">
                            </div>
                        </div><br>
                        <div class="form-group">
                            <label for="comment">Choosing the type and keying in the name you want to search for human genome.<br/>For exmaple: Select &quot;official symbol&quot; and type &quot;Jun&quot;, then click Submit button.</label>
                            <!-- <p style="white-space: pre;">Choosing the type and keying in the name you want to search for human genome.<br/>For exmaple: Select &quot;official symbol&quot; and type &quot;Jun&quot;, then click Submit button.</p> -->
                        </div>
                    </div><!-- /.box-body -->
                    <div class="box-footer">
                        <button type="button" class="btn-primary" id="query_button">Submit</button>
                        <button type="reset" class="btn-primary">Reset</button>
                    </div>
                </form>
            </div>
          </div>
        </div>
      </div>
    </div><!-- /.carousel -->



    <!-- Marketing messaging and featurettes
    ================================================== -->
    <!-- Wrap the rest of the page in another container to center all the content. -->

    <div class="container marketing" style="width:1170px; !important">


        <!-- Marketing messaging and featurettes
        ================================================== -->
        <!-- Wrap the rest of the page in another container to center all the content. -->
        <!-- Services Section -->
        <div id="result">
            <?php echo $result?>
        </div>

      <!-- FOOTER -->
        <footer>
            <p>Copyright &copy; 2015, National Cheng Kung University All rights reserved. Computational Systems Biology Laboratory<span class="pull-right"><a href="#">Back to top</a></span></p>
        </footer>

    </div><!-- /.container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="<?php echo WEBSITE_PATH;?>jscript/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="<?php echo WEBSITE_PATH;?>jscript/ie10.viewport.bug.workaround.js"></script>
    <script src="<?php echo WEBSITE_PATH;?>jscript/pages/js.entrez.js" type="text/javascript"></script>
    <script src="<?php echo WEBSITE_PATH;?>jscript/classie.js"></script>
    <script src="<?php echo WEBSITE_PATH;?>jscript/cbpAnimatedHeader.js"></script>
  </body>
</html>