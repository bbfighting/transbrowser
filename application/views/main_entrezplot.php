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
                <a class="navbar-brand page-scroll" href="#">IRES Omnibus</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li>
                          <a class="page-scroll" href="#services">Home</a>
                    </li>
                    <li> 
                        <a class="page-scroll" href="#portfolio">Predict</a>
                    </li>
                    <li class="active">
                        <a class="page-scroll" href="#about">Entrez</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#team">Tutorial</a>
                    </li>   
                    <li>
                        <a class="page-scroll" href="#team">About</a>
                    </li>                 
                    <li>
                        <a class="page-scroll" href="#contact">Contact</a>
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
                <form id="form" method="post" action="<?php echo WEBSITE_PATH;?>entrez">
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
                            <div class="form-group">
                                <select name="SelectType" id="SelectType" class="form-control">
                                    <option value="official_symbol" selected="selected">Official Symbol</option>
                                    <option value="fullname">Full Name</option>
                                    <option value="id">Gene ID</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <input name="TermTextField" type="text" class="form-control" autocomplete="off" size="40">
                            </div>
                        </div><br>
                        <div class="form-group">
                            <label for="comment">Choosing the type and keying in the name you want to search for human genome.<br/>For exmaple: Select &quot;official symbol&quot; and type &quot;Jun&quot;, then click Submit button.</label>
                            <!-- <p style="white-space: pre;">Choosing the type and keying in the name you want to search for human genome.<br/>For exmaple: Select &quot;official symbol&quot; and type &quot;Jun&quot;, then click Submit button.</p> -->
                        </div>
                    </div><!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn-primary">Submit</button>
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
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <h2 class="section-heading">Result</h2>
                        <h3 class="section-subheading text-muted">Search : NM_000014</h3>
                    </div>
                </div>
                <div class="row">
                    <form role=\"form\" method=\"post\" enctype=\"multipart/form-data\">
                        <div class="form-inline">
                            <label for="comment">Choose region : </label>
                            <div class="form-group">
                                <input type="text" class="form-control" name="cor1" maxlength="5" placeholder="other" style="width: 100px">
                            </div> &nbsp;&nbsp;&nbsp;~&nbsp;&nbsp;&nbsp;
                            <div class="form-group">
                                <input type="text" class="form-control" name="cor2" maxlength="5" placeholder="other" style="width: 100px">
                            </div> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <button type="button" class="btn btn-primary" data-dismiss="modal" id="validate_region"><i class="fa fa-exchange"></i> Change region</button>              
                        </div>    
                    </form>         
                    </br>          
                    <fieldset class="beta psi">
                        <legend><a class="anchor" id="translation_map">translation_map</a></legend>
                        <div class="far img"><img src="<?php echo WEBSITE_PATH;?>pages/plot/NM_182832" id="img1"></div>
                    </fieldset>
                    <fieldset class="beta psi">
                        <legend><a class="anchor" id="Sequence map">Sequence map</a></legend>
                        <div class="far img"><img src="<?php echo WEBSITE_PATH;?>pages/plotseq/NM_002228" id="img2"></div>
                    </fieldset>
                </div>
            </div>
        </section>



      <!-- START THE FEATURETTES -->

      <hr class="featurette-divider">

      <!-- /END THE FEATURETTES -->


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
    <script src="<?php echo WEBSITE_PATH;?>jscript/pages/custom.search.js" type="text/javascript"></script>
    <script src="<?php echo WEBSITE_PATH;?>jscript/classie.js"></script>
    <script src="<?php echo WEBSITE_PATH;?>jscript/cbpAnimatedHeader.js"></script>
  </body>
</html>