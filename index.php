<?php
include ("assets/gnoted.php");
if ($require_auth == "true") {
require ("assets/gatekeeper.php");
  }
if ($_REQUEST["note"] == "") {
   $active = "active";
  }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="assets/note.png">

    <title>Gnoted</title>

    <!-- Bootstrap core CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootswatch/3.3.6/readable/bootstrap.min.css" rel="stylesheet" integrity="sha384-/x/+iIbAU4qS3UecK7KIjLZdUilKKCjUFVdwFx8ba7S/WvxbrYeQuKEt0n/HWqTx" crossorigin="anonymous">

    <!-- Custom styles for this template -->
    <link href="assets/offcanvas.css" rel="stylesheet">
  </head>

  <body>
    <div class="navbar navbar-fixed-top navbar-inverse" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href=".">Gnoted</a>
        </div>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="<?=$active?>"><a href=".">Home</a></li>
            <li class="divider"></li>
            <li><a target="_blank" href="//wiki.gnome.org/Apps/Gnote/">Gnote</a></li>
            <li><a target="_blank" href="//wiki.gnome.org/Apps/Tomboy/">Tomboy Notes</a></li>
          </ul>
	   <ul class="nav navbar-nav navbar-right">
            <li><a href="?logout=1">Logout</a></li>
          </ul>
        </div><!-- /.nav-collapse -->
      </div><!-- /.container -->
    </div><!-- /.navbar -->

    <div class="container">

      <div class="row row-offcanvas row-offcanvas-right">
        
        <div class="col-xs-6 col-sm-3 sidebar-offcanvas" id="sidebar" role="navigation">
          <div class="list-group">
            <a class="list-group-item active" href="#"><b>Notes :</b></a>
            <?php foreach($notes as $note) { ?>
                <a class="list-group-item" href=".?note=<?=$note["id"]?>"><b><?=$note["content"]["title"];?></b></a>
            <?php } ?>
          </div>
        </div><!--/span-->

        <div class="col-xs-12 col-sm-9">
          <p class="pull-left visible-xs">
            <button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas">Toggle List</button>
          </p>

          <?php if ($_REQUEST["note"] == ""): ?>
             <div class="jumbotron">
               <h2 style="text-decoration: underline;">Gnoted</h2>
               <p>Welcome to Gnoted, the secure, web based note reader for Gnote and Tomboy desktop notes.</p>
               <p>To get started, simply click on the title of a Note on the left to display its contents.</p>
             </div>
          <?php else:
            $notes = getNotes();
            $note = $notes[$_REQUEST["note"]];
            $note["content"] = getNote($note["id"], $note["rev"], true); ?>

            <div class="row">
               <div class="well col-12">
                  <?php echo $note["content"]["text"];?>
               </div>
            </div><!--/row-->

          <?php endif ?>

        </div><!--/span-->

      </div><!--/row-->

      <hr>

      <footer>
        <p>&copy; 2016 <a target="_blank" href="http://unfettered.net">Unfettered</a></p>
      </footer>

    </div><!--/.container-->

    <!-- Bootstrap core JavaScript -->
    <!-- Placed at the end of the document for faster page loads-->
    <script src="https://code.jquery.com/jquery-1.12.3.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="assets/offcanvas.js"></script>
  </body>
</html>
