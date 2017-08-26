<?php
define('GNOTED_PRONK', true);
// https://www.youtube.com/watch?v=Jis_CrJ4zUI
require ("config.php");
require ("assets/gnoted.php");
if ($require_auth == true) require ("assets/gatekeeper.php");
$inverse = array("slate", "spacelab");
if( in_array( $THEME, $inverse ) ) {
	$navbar = null;
} else {
	$navbar = "navbar-inverse";
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
		<link href="https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/<?=$THEME;?>/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">

		<!-- Custom styles for this template -->
		<link href="assets/offcanvas.css" rel="stylesheet">
	</head>

	<body>
		<div class="navbar navbar-fixed-top <?=$navbar;?>" role="navigation">
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
						<li><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Notebooks<span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
							<?php foreach($tags as $tag) { ?>
							<li><a href=".?notebook=<?=$tag;?>" id=<?=$tag;?>><?=$tag;?></a></li>
							<?php } ?>
							</ul>
						</li>
					</ul>

					<ul class="nav navbar-nav navbar-right">
<li class="dropdown">
						<li>
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Companion Apps<span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li><a target="_blank" href="//wiki.gnome.org/Apps/Gnote/" data-toggle="tooltip" title="Linux" >Gnote</a></li>
								<li class="divider"></li>
								<li><a target="_blank" href="//wiki.gnome.org/Apps/Tomboy/" data-toggle="tooltip" title="Windows, MacOS, Linux">Tomboy Notes</a></li>
								<li class="divider"></li>
								<li><a target="_blank" href="https://play.google.com/store/apps/details?id=org.tomdroid" data-toggle="tooltip" title="Android">Tomdroid Notes</a></li>
							</ul>
						</li>
						<?php if ($require_auth == true) echo '<li><a href="?logout=1">Logout</a></li>'; ?>
					</ul>
				</div><!-- /.nav-collapse -->
			</div><!-- /.container -->
		</div><!-- /.navbar -->

		<div class="container">

			<div class="row row-offcanvas row-offcanvas-right">

				<div class="col-xs-6 col-sm-3 sidebar-offcanvas" id="sidebar" role="navigation">
					<div class="list-group">

						<?php
						if(isset($_REQUEST["notebook"]) ) { 
							$notebook = $_REQUEST["notebook"];
						?>
						<a class="list-group-item active" ><b><?=$notebook?> Notes</b></a>
						<?php
							foreach($notes as $note) {
								if( $note["content"]["tag"] == $notebook ) {
						?>
						<a class="list-group-item" href=".?notebook=<?=$notebook;?>&note=<?=$note["id"]?>"><b><?=$note["content"]["title"];?></b></a>
						<?php			
								}
							}
						} else { 
						?>
						<a class="list-group-item active" ><b>All Notes</b></a>
						<?php
							foreach($notes as $note) { 
								if( $note["content"]["tag"] !== "template" ) { 
						?>
						<a class="list-group-item" href=".?note=<?=$note["id"]?>"><b><?=$note["content"]["title"];?></b></a>
						<?php
								}
							}
						} 
						?>

					</div>
				</div><!--/span-->

				<div class="col-xs-12 col-sm-9">
					<p class="pull-left visible-xs">
						<button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas">Toggle List</button>
					</p>

					<?php
						if(!isset($_REQUEST["note"]) && !isset($_REQUEST["notebook"])) :
					?>
					<div class="jumbotron">
						<h2 style="text-decoration: underline;">Gnoted</h2>
						<p>Welcome to Gnoted, the secure, web based note reader for Gnote and Tomboy desktop notes.</p>
						<p>To get started, either click on the title of a Note to display its contents or select a Notebook from the dropdown menu.</p>
						<p>When viewing  note you can click on its Notebook button to view only notes in that Notebook. If the Notebook has already been selected and you click on an individual Note's Notebook button, you will stay in the Notebook, but willclear that Note.</p>
						<p>Click on the "Gnoted" menu button to clear the Notebook selection and display all Notes.</p>
					</div>

					<?php 
						elseif(!isset($_REQUEST["note"]) && isset($_REQUEST["notebook"])):
?>
					<div class="jumbotron">
						<h2 style="text-decoration: underline;">Notebook: <?=$_REQUEST["notebook"];?></h2>
						<p>Click on the title of a Note to display its contents.</p>
						<p>Click on the "Gnoted" menu button to clear the Notebook selection and display all Notes.</p>
					</div>
					<?php
						else:
							$notes = getNotes();
						if(isset($_REQUEST["note"]) && $_REQUEST["note"] !== null) {
							$note = $notes[$_REQUEST["note"]];
							$note["content"] = getNote($note["id"], $note["rev"], true);
						}
					?>

					<div class="row">
						<div class="well col-12">
							<?php 
								if(isset($_REQUEST["note"]) && $_REQUEST["note"] !== null && isset($_REQUEST["notebook"])) {
									echo $note["content"]["text"]."<hr><a href='.?notebook=".$note["content"]["tag"]."' class='btn btn-info btn-xs'>".$note["content"]["tag"]."</a>";
								} elseif(isset($_REQUEST["note"]) && $_REQUEST["note"] !== null) {
									echo $note["content"]["text"]."<hr><a href='.?notebook=".$note["content"]["tag"]."&note=".$note["id"]."' class='btn btn-info btn-xs'>".$note["content"]["tag"]."</a>";
								}
							?>
						</div>
					</div><!--/row-->

							<?php endif ?>

				</div><!--/span-->

			</div><!--/row-->

			<hr>

			<footer>
				<p>&copy; 2017 <a target="_blank" href="https://unfettered.net">Unfettered</a> | <a target="_blank" href="https://github.com/joshp23/Gnoted">Gnoted Home</a></p>
			</footer>

		</div><!--/.container-->

		<!-- Bootstrap core JavaScript -->
		<!-- Placed at the end of the document for faster page loads-->
		<script src="https://code.jquery.com/jquery-1.12.3.min.js"></script>
		<script src="//netdna.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<script src="assets/offcanvas.js"></script>

	</body>
</html>
