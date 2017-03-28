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
    <link href="<?php echo WEBSITE_PATH;?>css/bootstrap/bootstrap.css" rel="stylesheet">

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
    <link href="<?php echo WEBSITE_PATH;?>css/simple-sidebar.css" rel="stylesheet">
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
    <!-- Carousel
    ================================================== -->
    <div class="navbar navbar-default navbar-fixed-top">
      <!-- Indicators -->
      <div class="carousel-inner">
        <div class="container">
          <div class="carousel-caption">
            <a class="navbar-brandIRES" style="text-decoration: none;">IRES omnibus</a>
          </div>
        </div>
      </div>
    </div><!-- /.carousel -->

    <!-- Navigation side -->
    <nav class="navbar-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav" id="main-menu">
                <li class="text-center"><a href="<?php echo WEBSITE_PATH;?>home" name="home"><i class="fa fa-home fa-2x"></i></br>Home</a></li>
                <li class="text-center"><a href="<?php echo WEBSITE_PATH;?>prediction" name="prediction"><i class="fa fa-home fa-2x"></i></br>IRES prediction</a></li>
                <li class="text-center"><a href="<?php echo WEBSITE_PATH;?>search" name="search"><i class="fa fa-home fa-2x"></i></br>search</a></li>
                <li class="text-center"><a href="<?php echo WEBSITE_PATH;?>tutorial" name="tutorial"><i class="fa fa-home fa-2x"></i></br>Tutorial</a></li>
                <li class="text-center"><a href="<?php echo WEBSITE_PATH;?>about" name="about"><i class="fa fa-home fa-2x"></i></br>About</a></li>
                <li class="text-center"><a href="<?php echo WEBSITE_PATH;?>contact" name="contact"><i class="fa fa-home fa-2x"></i></br>Contact</a></li>
            </ul>         
        </div>            
    </nav>  
    <!-- /. NAV SIDE  -->

    <div id="page-wrapper" style="width:1250px; !important">
        <div id="page-inner">
            <h3><i class="fa <?php echo $icon;?>"></i> <?php echo $title;?></h3>
            <hr class="star-primary">
            <!-- <div class="hrline"><div/> -->
            <!-- Services Section -->
            <div id="mainbar">
            </div>
            <div id="result">
            </div>
        </div>
    </div>
    <!-- FOOTER -->
    <footer>
        <p>Copyright &copy; 2015, National Cheng Kung University All rights reserved. Computational Systems Biology Laboratory<span class="pull-right"><a href="#">Back to top</a></span></p>
    </footer>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="<?php echo WEBSITE_PATH;?>jscript/bootstrap/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="<?php echo WEBSITE_PATH;?>jscript/ie10.viewport.bug.workaround.js"></script>
    <script src="<?php echo WEBSITE_PATH;?>jscript/pages/<?php echo $jsfile;?>" type="text/javascript"></script>
  </body>
</html>