<!DOCTYPE html>
<html data-bs-theme="dark">
<head>
	<title>aMule - Control Panel - Shared</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="#172631">
	<link rel="icon" type="image/x-icon" href="/amule/favicon.ico">

	<!-- Bootstrap 5 e stili del tema (tutti nel file amule.css) -->
	<link rel="stylesheet" href="bootstrap-5.3.8.min.css">
	<link rel="stylesheet" href="amule.css">
	<script src="bootstrap-5.3.8.bundle.min.js"></script>
	<script src="amule.js"></script>
<script>
function formCommandSubmit(command)
	{
		<?php
			if ($_SESSION["guest_login"] != 0) {
					echo 'alert("You logged in as guest - commands are disabled");';
					echo "return;";
			}
		?>
		var frm=document.forms.mainform
		frm.command.value=command
		frm.submit()
	}
function selectAll(check)
	{
		var checkboxes = document.querySelectorAll('input[type="checkbox"]');
		if (check.checked)
		{
			checkboxes.forEach(function(checkbox) {
				checkbox.checked = true;
			});			
		}
		else
		{
			checkboxes.forEach(function(checkbox) {
				checkbox.checked = false;
			});
		}
	}
</script>
</head>

<body class="page-shared animated fadeIn">
	<!-- Barra di navigazione (uguale in tutte le pagine) -->
	<nav class="navbar fixed-top amule-navbar">
		<div class="container">
			<a class="navbar-brand" href="#"><img src="logo-nav-brax.png" class="logo-nav" alt=""><span class="brand-text">aMule WebUI</span></a>
			<div class="amule-menu">
				<div class="btn-group">
					<a class="btn btn-icon navbar-link" title="Downloads and Uploads" href="amuleweb-main-dload.php"><span class="bi bi-arrow-left-right"></span><span class="btn-label">Transfer</span></a>
					<a class="btn btn-icon navbar-link active" title="Sharing" href="amuleweb-main-shared.php" aria-current="page"><span class="bi bi-share-fill"></span><span class="btn-label">Shared</span></a>
					<a class="btn btn-icon navbar-link" title="Search" href="amuleweb-main-search.php"><span class="bi bi-search"></span><span class="btn-label">Search</span></a>
					<a class="btn btn-icon navbar-link" title="Servers" href="amuleweb-main-servers.php"><span class="bi bi-hdd-stack-fill"></span><span class="btn-label">Server</span></a>
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

    <!-- Commands -->
    <form name="mainform" action="amuleweb-main-shared.php" method="post">
    <div class="panel panel-tasks">
  		<div class="panel-body container panel-center">
    		<div class="form-inline form-tasks">
    		<input type="hidden" name="command">
    		<div class="btn-group">
    			<a class="btn btn-icon" href="javascript:formCommandSubmit('priodown');" title="Lower priority">
						<span class="bi bi-arrow-down-circle"></span><span class="btn-label">Lower Priority</span></a>
    			<a class="btn btn-icon" href="javascript:formCommandSubmit('reload');" title="Refresh">
						<span class="bi bi-arrow-clockwise"></span><span class="btn-label">Refresh</span></a>
    			<a class="btn btn-icon" href="javascript:formCommandSubmit('prioup');" title="Higher priority">
						<span class="bi bi-arrow-up-circle"></span><span class="btn-label">High Priority</span></a>
    		</div>
    		<!-- Inserting filtering php -->
    		<div class="btn-group">
     			<select name="select" id="filter" class="form-select">
     				<option selected>All</option>
     				<option>Low</option>
     				<option>Normal</option>
     				<option>High</option>
     				<option>Release</option>
     			</select>
    			<a class="btn btn-icon btn-filter" href="javascript:formCommandSubmit('setprio');" title="Filter">
						<span class="bi bi-check2-square"></span><span class="btn-label">Filter</span></a>
    			<?php
    				if ($_SESSION["guest_login"] != 0) {
					    echo '<br><br><span class="label label-warning">You logged in as guest - commands are disabled</span>';
					}
				?>
			</div>
  		</div>
  		</div>
	</div>

	<!-- Center table -->
	<div class="container-fluid panel-tr">

		<!-- Table Download -->
		<div class="panel panel-last">
		<div class="panel-heading panel-center"><h4>SHARED FILES</h4></div>
			<div class="table-responsive"><table class="table">
				<thead>
					<tr>
						<th><input type="checkbox" name="selectAllFiles" onclick='selectAll(this);'></th>
						<th><a href="amuleweb-main-shared.php?sort=name">File name</a></th>
						<th><a href="amuleweb-main-shared.php?sort=xfer">Transferred</a> <a href="amuleweb-main-shared.php?sort=xfer_all">(Total)</a></th>
						<th><a href="amuleweb-main-shared.php?sort=req">Requested</a> <a href="amuleweb-main-shared.php?sort=req_all">(Total)</a></th>
						<th><a href="amuleweb-main-shared.php?sort=acc">Accepted Rqst</a> <a href="amuleweb-main-shared.php?sort=acc_all">(Total)</a></th>
						<th><a href="amuleweb-main-shared.php?sort=size">Size</a></th>
						<th><a href="amuleweb-main-shared.php?sort=prio">Priority</a></th>
					</tr>
				</thead>
				<tbody>

				<?php
					function CastToXBytes($size) {
						if ( $size < 1024 ) {
							$result = $size . " b";
						} elseif ( $size < 1048576 ) {
							$result = ($size / 1024.0) . " kb";
						} elseif ( $size < 1073741824 ) {
							$result = ($size / 1048576.0) . " mb";
						} else {
							$result = ($size / 1073741824.0) . " gb";
						}
						return $result;
					}

					function StatusString($file) {
						if ( $file->status == 7 ) {
							return '<span class="label label-info">Paused</span>';
						} elseif ( $file->src_count_xfer > 0 ) {
							return '<span class="label label-success">Downloading</span>';
						} else {
							return '<span class="label label-warning">Waiting</span>';
						}
					}

					function StatusCode($file) {
						if ( $file->status == 7 ) {
							return 1; // Paused
						} elseif ( $file->src_count_xfer > 0 ) {
							return 0; // downloading
						} else {
							return -1; // waiting
						}
					}

					function PrioString($file) {
						$prionames = array(0 => "Low", 1 => "Normal", 2 => "High",
							3 => "Very high", 4 => "Very low", 5=> "Auto", 6 => "Release");
						$result = $prionames[$file->prio];
						if ( $file->prio_auto == 1) {
							$result = $result . "&nbsp(auto)";
						}
						return $result;
					}

					function PrioStringSorter($file) {
						$prionames = array(0 => "Low", 1 => "Normal", 2 => "High",
							3 => "High", 4 => "Low", 5=> "Normal", 6 => "Release");
						$result = $prionames[$file->prio];
						return $result;
					}

					$sort_order;$sort_reverse;

					function my_cmp($a, $b)	{
						global $sort_order, $sort_reverse;

						switch ( $sort_order) {
							case "size": $result = $a->size > $b->size; break;
							case "name": $result = $a->name > $b->name; break;
							case "xfer": $result = $a->xfer > $b->xfer; break;
							case "xfer_all": $result = $a->xfer_all > $b->xfer_all; break;
							case "acc": $result = $a->accept > $b->accept; break;
							case "acc_all": $result = $a->accept_all > $b->accept_all; break;
							case "req": $result = $a->req > $b->req; break;
							case "req_all": $result = $a->req_all > $b->req_all; break;
							case "prio": $result = PrioSort($a) < PrioSort($b); break;
						}

						if ( $sort_reverse ) {
							$result = !$result;
						}
						//var_dump($sort_reverse);
						return $result;
					}

					//
					// perform command before processing content
					//
					//var_dump($HTTP_GET_VARS);
					if (($HTTP_GET_VARS["command"] != "") && ($_SESSION["guest_login"] == 0)) {
						//amule_do_download_cmd($HTTP_GET_VARS["command"]);
						foreach ( $HTTP_GET_VARS as $name => $val) {
							// this is file checkboxes
							if ( (strlen($name) == 32) and ($val == "on") ) {
								//var_dump($name);var_dump($val);
								amule_do_shared_cmd($name, $HTTP_GET_VARS["command"]);
							}
						}
						if ($HTTP_GET_VARS["command"] == "reload") {
							amule_do_reload_shared_cmd();
						}
					}
					$shared = amule_load_vars("shared");

					$sort_order = $HTTP_GET_VARS["sort"];

					if ( $sort_order == "" ) {
						$sort_order = $_SESSION["shared_sort"];
					} else {
						if ( $_SESSION["sort_reverse"] == "" ) {
							$_SESSION["sort_reverse"] = 0;
						} else {
							$_SESSION["sort_reverse"] = !$_SESSION["sort_reverse"];
						}
					}
					//var_dump($_SESSION);
					$sort_reverse = $_SESSION["sort_reverse"];
					if ( $sort_order != "" ) {
						$_SESSION["shared_sort"] = $sort_order;
						usort(&$shared, "my_cmp");
					}

					if ($HTTP_GET_VARS["select"] == "All" || $HTTP_GET_VARS["select"] == "") {
						foreach ($shared as $file) {

							echo '<tr>';
							echo '<td>', '<div class="checkbox download-checkbox"><input type="checkbox" name="', $file->hash, '" >', "</div></td>";
							echo '<td>', '<div><label><b>', $file->name, "</b></label></div></td>";
							echo '<td>', CastToXBytes($file->xfer), " (", CastToXBytes($file->xfer_all),")</td>";
							echo '<td>', $file->req, " (", $file->req_all, ")</td>";
							echo '<td>', $file->accept, " (", $file->accept_all, ")</td>";
							echo '<td>', CastToXBytes($file->size), "</td>";
							echo '<td>', PrioString($file), "</td>";;
							echo '</tr>';
						}
					} else {
						foreach ($shared as $file) {
							if ($HTTP_GET_VARS["select"] == PrioStringSorter($file)) {
								echo '<tr>';
								echo '<td>', '<div class="checkbox download-checkbox"><input type="checkbox" name="', $file->hash, '" >', "</div></td>";
								echo '<td>', '<div><label><b>', $file->name, "</b></label></div></td>";
								echo '<td>', CastToXBytes($file->xfer), " (", CastToXBytes($file->xfer_all),")</td>";
								echo '<td>', $file->req, " (", $file->req_all, ")</td>";
								echo '<td>', $file->accept, " (", $file->accept_all, ")</td>";
								echo '<td>', CastToXBytes($file->size), "</td>";
								echo '<td>', PrioString($file), "</td>";;
								echo '</tr>';
							}
						}
					}
				?>

				</tbody>
			</table></div>
		</div>
		</div>
		</form>

	<!-- Piè di pagina: link ed2k e stato delle connessioni -->
	<div id="footer">
		<form name="formlink" method="post" class="form-inline" action="amuleweb-main-shared.php" role="form" id="formed2link">
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
									    if (strlen($linkn) > 0) {
									        amule_do_ed2k_download_cmd("ed2k://" . $linkn, $target_cat_idx);
									    }
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
