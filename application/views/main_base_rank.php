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
    <!-- Ionicons -->
    <link href="<?php echo WEBSITE_PATH;?>css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo WEBSITE_PATH;?>css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <!-- Bootstrap core CSS -->
    <link href="<?php echo WEBSITE_PATH;?>css/bootstrap.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.datatables.net/1.10.11/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="jscript/ie8.responsive.file.warning.js"></script><![endif]-->
    <script src="<?php echo WEBSITE_PATH;?>jscript/ie.emulation.modes.warning.js"></script>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        p {white-space: pre;}
    </style>
  </head>
<!-- NAVBAR
================================================== -->
  <body style="margin: 100px">
    <!-- Carousel
    ================================================== -->
    <?php echo $result;?>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.11/js/dataTables.bootstrap.min.js"></script>
    <script src="<?php echo WEBSITE_PATH;?>jscript/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="<?php echo WEBSITE_PATH;?>jscript/ie10.viewport.bug.workaround.js"></script>
    <script src="<?php echo WEBSITE_PATH;?>jscript/pages/<?php echo $jsfile;?>" type="text/javascript"></script>
  </body>
</html>