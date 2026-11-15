<!DOCTYPE html>
<html data-bs-theme="dark">
<head>
	<title>aMule - Control panel - Statistics</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="#172631">
	<link rel="icon" type="image/x-icon" href="/amule/favicon.ico">

	<!-- Bootstrap 5 e stili del tema (tutti nel file amule.css) -->
	<link rel="stylesheet" href="bootstrap-5.3.8.min.css">
	<link rel="stylesheet" href="amule.css">
	<script src="bootstrap-5.3.8.bundle.min.js"></script>
	<script src="amule.js"></script>
<?php
  if ( $_SESSION["auto_refresh"] > 0 ) {
    echo "<meta http-equiv=\"refresh\" content=\"", $_SESSION["auto_refresh"], '">';
  }
  amule_load_vars("stats_graph");
?>
  <script>

    var openImg = new Image();
    openImg.src = "tree-open.gif";
    var closedImg = new Image();
    closedImg.src = "tree-closed.gif";

    function showBranch(branch){
    	var objBranch = document.getElementById(branch).style;
    	if(objBranch.display=="block")
    		objBranch.display="none";
    	else
    		objBranch.display="block";
    }

    function swapFolder(img){
    	objImg = document.getElementById(img);
    	if(objImg.src.indexOf('tree-closed.gif')>-1)
    		objImg.src = openImg.src;
    	else
    		objImg.src = closedImg.src;
    }

  </script>
</head>

<body class="page-stats">
	<!-- Barra di navigazione (uguale in tutte le pagine) -->
	<nav class="navbar fixed-top amule-navbar">
		<div class="container">
			<a class="navbar-brand" href="#"><img src="logo-nav-brax.png" class="logo-nav" alt=""><span class="brand-text">aMule WebUI</span></a>
			<div class="amule-menu">
				<div class="btn-group">
					<a class="btn btn-icon navbar-link" title="Downloads and Uploads" href="amuleweb-main-dload.php"><span class="bi bi-arrow-left-right"></span><span class="btn-label">Transfer</span></a>
					<a class="btn btn-icon navbar-link" title="Sharing" href="amuleweb-main-shared.php"><span class="bi bi-share-fill"></span><span class="btn-label">Shared</span></a>
					<a class="btn btn-icon navbar-link" title="Search" href="amuleweb-main-search.php"><span class="bi bi-search"></span><span class="btn-label">Search</span></a>
					<a class="btn btn-icon navbar-link" title="Servers" href="amuleweb-main-servers.php"><span class="bi bi-hdd-stack-fill"></span><span class="btn-label">Server</span></a>
					<a class="btn btn-icon navbar-link" title="Kademlia" href="amuleweb-main-kad.php"><span class="bi bi-asterisk"></span><span class="btn-label">Kad</span></a>
					<a class="btn btn-icon navbar-link active" title="Statistics" href="amuleweb-main-stats.php" aria-current="page"><span class="bi bi-bar-chart-fill"></span><span class="btn-label">Stats</span></a>
				</div>
				<div class="btn-group">
					<a class="btn btn-icon navbar-link" title="Configurations" href="amuleweb-main-prefs.php"><span class="bi bi-gear-fill"></span><span class="btn-label">Settings</span></a>
					<a class="btn btn-icon navbar-link" title="Log" href="amuleweb-main-log.php"><span class="bi bi-flag-fill"></span><span class="btn-label">Logs</span></a>
					<a class="btn btn-icon navbar-link" title="Exit" href="login.php"><span class="bi bi-power"></span><span class="btn-label">Exit</span></a>
				</div>
			</div>
		</div>
	</nav>

	<!-- Statistiche: albero a sinistra, grafici a destra -->
	<div class="container-fluid panel-tr panel-last">
		<div class="panel">
			<div class="panel-heading panel-center"><h4>STATISTICS</h4></div>
			<div class="row g-3 stats-row">
				<div class="col-lg-4">
					<iframe name="stats" src="stats_tree.php" class="stats-tree" title="Statistics tree">liste</iframe>
				</div>
				<div class="col-lg-8">
					<div id="carousel-stats" class="carousel slide stats-carousel" data-bs-ride="carousel">
						<div class="carousel-indicators">
						<button type="button" data-bs-target="#carousel-stats" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Downloads"></button>
						<button type="button" data-bs-target="#carousel-stats" data-bs-slide-to="1" aria-label="Uploads"></button>
						<button type="button" data-bs-target="#carousel-stats" data-bs-slide-to="2" aria-label="Connections Count"></button>
						<button type="button" data-bs-target="#carousel-stats" data-bs-slide-to="3" aria-label="KAD Nodes"></button>
						</div>
						<div class="carousel-inner">
						<div class="carousel-item active">
							<img src="amule_stats_download.png" class="d-block w-100" alt="Download graph">
							<div class="carousel-caption"><h3>Downloads</h3></div>
						</div>
						<div class="carousel-item">
							<img src="amule_stats_upload.png" class="d-block w-100" alt="Upload graph">
							<div class="carousel-caption"><h3>Uploads</h3></div>
						</div>
						<div class="carousel-item">
							<img src="amule_stats_conncount.png" class="d-block w-100" alt="Connection count graph">
							<div class="carousel-caption"><h3>Connections Count</h3></div>
						</div>
						<div class="carousel-item">
							<img src="amule_stats_kad.png" class="d-block w-100" alt="KAD graph">
							<div class="carousel-caption"><h3>KAD Nodes</h3></div>
						</div>
						</div>
						<button class="carousel-control-prev" type="button" data-bs-target="#carousel-stats" data-bs-slide="prev">
							<span class="bi bi-chevron-left"></span><span class="visually-hidden">Previous</span>
						</button>
						<button class="carousel-control-next" type="button" data-bs-target="#carousel-stats" data-bs-slide="next">
							<span class="bi bi-chevron-right"></span><span class="visually-hidden">Next</span>
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Piè di pagina: link ed2k e stato delle connessioni -->
	<div id="footer">
		<form name="formlink" method="post" class="form-inline" action="amuleweb-main-stats.php" role="form" id="formed2link">
			<input class="form-control" name="ed2klink" type="text" id="ed2klink" placeholder="ed2k:// - Insert link" size="25">
			<select class="form-select" name="selectcat" id="selectcat">
				<?php
            $cats = amule_get_categories();

            if ( $HTTP_GET_VARS["Submit"] != "" ) {
              $link = $HTTP_GET_VARS["ed2klink"];
              $target_cat = $HTTP_GET_VARS["selectcat"];
              $target_cat_idx = 0;

              foreach($cats as $i => $c) {
                if ( $target_cat == $c) $target_cat_idx = $i;
              }

              if ( strlen($link) > 0 ) {
                $links = split("ed2k://", $link);
                foreach($links as $linkn) {
                    amule_do_ed2k_download_cmd("ed2k://" . $linkn, $target_cat_idx);
                }
              }
            }

            foreach($cats as $c) {
              echo  '<option>', $c, '</option>';
            }
          ?>
			</select>
			<input class="btn btn-default" type="submit" name="Submit" value="Download link">
		</form>
		<div class="footer-status">
			<?php
              $stats = amule_get_stats();
            if ( $stats["id"] == 0 ) {
              $ed2k = "Not connected";
              $ed2k_status = "danger";
            } elseif ( $stats["id"] == 0xffffffff ) {
              $ed2k = "Connecting ...";
              $ed2k_status = "info";
            } else {
              $ed2k = "Connected " . (($stats["id"] < 16777216) ? "(low)" : "(high)") . " " . $stats["serv_name"] . " " . $stats["serv_addr"];
              $ed2k_status = (($stats["id"] < 16777216) ? "warning" : "success");
            }
            if ( $stats["kad_connected"] == 1 ) {
              $kad1 = "Connected";
            if ( $stats["kad_firewalled"] == 1 ) {
              $kad2 = "(FW)";
              $kad_status = "warning";
            } else {
              $kad2 = "(OK)";
              $kad_status = "success";
            }
            } else {
              $kad1 = "Disconnected";
              $kad2 = "";
              $kad_status = "danger";
            }

            echo '<span class="label label-default">ED2k:</span> ';
            echo '<span class="label label-', $ed2k_status, '">', $ed2k, '</span> ';
            echo '<span class="label label-default">KAD:</span> ';
            echo '<span class="label label-', $kad_status, '">', $kad1, ' ', $kad2, '</span>';
          ?>
		</div>
	</div>
</body>
</html>
