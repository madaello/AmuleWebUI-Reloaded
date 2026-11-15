<!DOCTYPE html>
<html data-bs-theme="dark">
<head>
	<title>aMule Control Panel - Server List</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="#172631">
	<link rel="icon" type="image/x-icon" href="/amule/favicon.ico">

	<!-- Bootstrap 5 e stili del tema (tutti nel file amule.css) -->
	<link rel="stylesheet" href="bootstrap-5.3.8.min.css">
	<link rel="stylesheet" href="amule.css">
	<script src="bootstrap-5.3.8.bundle.min.js"></script>
	<script src="amule.js"></script>

</head>

<body class="page-servers animated fadeIn">
	<!-- Barra di navigazione (uguale in tutte le pagine) -->
	<nav class="navbar fixed-top amule-navbar">
		<div class="container">
			<a class="navbar-brand" href="#"><img src="logo-nav-brax.png" class="logo-nav" alt=""><span class="brand-text">aMule WebUI</span></a>
			<div class="amule-menu">
				<div class="btn-group">
					<a class="btn btn-icon navbar-link" title="Downloads and Uploads" href="amuleweb-main-dload.php"><span class="bi bi-arrow-left-right"></span><span class="btn-label">Transfer</span></a>
					<a class="btn btn-icon navbar-link" title="Sharing" href="amuleweb-main-shared.php"><span class="bi bi-share-fill"></span><span class="btn-label">Shared</span></a>
					<a class="btn btn-icon navbar-link" title="Search" href="amuleweb-main-search.php"><span class="bi bi-search"></span><span class="btn-label">Search</span></a>
					<a class="btn btn-icon navbar-link active" title="Servers" href="amuleweb-main-servers.php" aria-current="page"><span class="bi bi-hdd-stack-fill"></span><span class="btn-label">Server</span></a>
					<a class="btn btn-icon navbar-link" title="Kademlia" href="amuleweb-main-kad.php"><span class="bi bi-asterisk"></span><span class="btn-label">Kad</span></a>
					<a class="btn btn-icon navbar-link" title="Statistics" href="amuleweb-main-stats.php"><span class="bi bi-bar-chart-fill"></span><span class="btn-label">Stats</span></a>
				</div>
				<div class="btn-group">
					<a class="btn btn-icon navbar-link" title="Configurations" href="amuleweb-main-prefs.php"><span class="bi bi-gear-fill"></span><span class="btn-label">Settings</span></a>
					<a class="btn btn-icon navbar-link" title="Log" href="amuleweb-main-log.php"><span class="bi bi-flag-fill"></span><span class="btn-label">Logs</span></a>
					<a class="btn btn-icon navbar-link" title="Exit" href="login.php"><span class="bi bi-power"></span><span class="btn-label">Exit</span></a>
				</div>
			</div>
		</div>
	</nav>

    	<!-- Center table -->
	<div class="container-fluid panel-tr">

		<!-- Table Download -->
		<div class="panel panel-last">
		<div class="panel-heading panel-center"><h4>SERVERS</h4></div>
			<div class="table-responsive"><table class="table">
				<thead>
					<tr>
						<th class="col-actions"></th>
						<th><a href="amuleweb-main-servers.php?sort=name">Server name</a></th>
						<th><a href="amuleweb-main-servers.php?sort=desc">Description</a></th>
						<th>Address</th>
						<th><a href="amuleweb-main-servers.php?sort=users">Users</a></th>
						<th><a href="amuleweb-main-servers.php?sort=files">Files</a></th>
					</tr>
				</thead>
				<tbody>

					<?php

						$sort_order;$sort_reverse;

						function my_cmp($a, $b) {
							global $sort_order, $sort_reverse;
							switch ( $sort_order) {
								case "name": $result = $a->name > $b->name; break;
								case "desc": $result = $a->desc > $b->desc; break;
								case "users": $result = $a->users > $b->users; break;
								case "files":$result = $a->files > $b->files; break;
							}

							if ( $sort_reverse ) {
								$result = !$result;
							}
							return $result;
						}

						$servers = amule_load_vars("servers");

						$sort_order = $HTTP_GET_VARS["sort"];

						if ( ($HTTP_GET_VARS["cmd"] != "") and ($HTTP_GET_VARS["ip"] != "") and ($HTTP_GET_VARS["port"] != "")) {
							if ($_SESSION["guest_login"] == 0) {
								amule_do_server_cmd($HTTP_GET_VARS["ip"], $HTTP_GET_VARS["port"], $HTTP_GET_VARS["cmd"]);
							}
						}

						if ( $sort_order == "" ) {
							$sort_order = $_SESSION["servers_sort"];
						} else {
							if ( $_SESSION["sort_reverse"] == "" ) {
								$_SESSION["sort_reverse"] = 0;
							} else {
								$_SESSION["sort_reverse"] = !$_SESSION["sort_reverse"];
							}
						}

						$sort_reverse = $_SESSION["sort_reverse"];
						if ( $sort_order != "" ) {
							$_SESSION["servers_sort"] = $sort_order;
							usort(&$servers, "my_cmp");
						}
						foreach ($servers as $srv) {
							echo "<tr>";

							if ($_SESSION["guest_login"] != 0) {
								echo "<td class='texte' align='center'></td>";
							} else {
								echo 	'<td class="col-actions">',
											'<a href="amuleweb-main-servers.php?cmd=connect&ip=', $srv->ip, '&port=', $srv->port, '">
												<span class="bi bi-plus-circle-fill bi-add" title="Connect"></span>
											</a>',
											'<a href="amuleweb-main-servers.php?cmd=remove&ip=', $srv->ip, '&port=', $srv->port, '">
												<span class="bi bi-dash-circle-fill bi-remove" title="Remove"></span>
											</a>',
									'</td>';
							}

							echo '<td><b>', $srv->name, '</b></td>';
							echo '<td>', $srv->desc, '</td>';
							echo '<td>', $srv->addr, '</td>';
							echo '<td>', $srv->users, '</td>';
							echo '<td>', $srv->files, '</td> ';
							echo "</tr>";
						}
					?>

				</tbody>
			</table></div>
		</div>
		</div>

	<!-- Piè di pagina: link ed2k e stato delle connessioni -->
	<div id="footer">
		<form name="formlink" method="post" class="form-inline" action="amuleweb-main-servers.php" role="form" id="formed2link">
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
