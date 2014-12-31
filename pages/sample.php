<!DOCTYPE html>
<html lang="en" ng-app='queryTerminal'>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!--<link rel="icon" href="../../favicon.ico">-->

    <title>Dashboard Template for Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <link href="resources/css/bootstrap.min.css" rel="stylesheet">
    <link href="resources/css/main.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#/Home">OCITerm</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse" ng-controller='TopNavController as nav'>
          <ul class="nav navbar-nav navbar-right">
            <li ng-repeat='item in nav.items'><a href="{{item.href}}">{{item.label}}</a></li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="container-fluid">
      <div class="row">
      <div ng-view=""></div>
        
              </div>
    </div>
        <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="resources/js/angular.js"></script>
    <script src="resources/js/angular-route.min.js"></script>
    <script src="resources/js/angular-input-match.js"></script>
    <script src="resources/js/app.js"></script>
    <script src="resources/js/services/user.js"></script>
  </body>
</html>