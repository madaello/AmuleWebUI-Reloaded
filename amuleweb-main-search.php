<!DOCTYPE html>
<html data-bs-theme="dark">
<head>
	<title>aMule - Control Panel - Search</title>
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
			echo "<script>
				setInterval(() => {
				 if (document.querySelectorAll('[action=\"amuleweb-main-search.php\"] input:checked').length > 0) {
				   return;
				 }
				 location.reload();
				}, 1000 * " . $_SESSION["auto_refresh"] . ");
				</script>";
		}
	?>
	<script>
	function formCommandSubmit(command, confermato)
		{
			<?php
				if ($_SESSION["guest_login"] != 0) {
						echo 'alert("You logged in as guest - commands are disabled");';
						echo "return;";
				}
			?>
			if ( command == "download" && !confermato ) {
				var boxchecked = document.querySelectorAll('input[type="checkbox"]:checked');
				var selectedFiles = Object.values(boxchecked).filter(selected => selected.name != 'selectAllFiles').length;
				if (selectedFiles == 0)
					return;
				// finestra di conferma nei colori del tema (amule.js); se si conferma, riparte da qui
				amuleConfirm("Download selected " + selectedFiles + (selectedFiles == 1 ? " file?" : " files?"), "Download",
					function () { formCommandSubmit(command, true); });
				return;
			}
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

<body class="page-search animated fadeIn">
	<!-- Barra di navigazione (uguale in tutte le pagine) -->
	<nav class="navbar fixed-top amule-navbar">
		<div class="container">
			<a class="navbar-brand" href="#"><img src="logo-nav-brax.png" class="logo-nav" alt=""><span class="brand-text">aMule WebUI</span></a>
			<div class="amule-menu">
				<div class="btn-group">
					<a class="btn btn-icon navbar-link" title="Downloads and Uploads" href="amuleweb-main-dload.php"><span class="bi bi-arrow-left-right"></span><span class="btn-label">Transfer</span></a>
					<a class="btn btn-icon navbar-link" title="Sharing" href="amuleweb-main-shared.php"><span class="bi bi-share-fill"></span><span class="btn-label">Shared</span></a>
					<a class="btn btn-icon navbar-link active" title="Search" href="amuleweb-main-search.php" aria-current="page"><span class="bi bi-search"></span><span class="btn-label">Search</span></a>
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

	<a href="#" id="scroll" title="Back to top"><span></span></a>

	<!-- Comandi: ricerca -->
	<form action="amuleweb-main-search.php" method="post" name="mainform">
	<input type="hidden" name="command">
	<div class="panel panel-tasks">
		<div class="panel-body container panel-center">
			<div class="search-form">
				<div class="input-group search-main">
					<a class="btn btn-info" href="amuleweb-main-search.php?search_sort=<?php echo($HTTP_GET_VARS["sort"]);?>" title="Refresh to see the results">
						<span class="bi bi-arrow-clockwise bi-white"></span>
					</a>
					<input type="text" placeholder="Text query..." name="searchval" class="form-control" size="70">
					<select class="form-select search-type" name="searchtype">
						<option>Local</option>
						<option selected>Global</option>
						<option>Kad</option>
					</select>
					<input class="btn btn-info search-btn" name="Search" type="submit" value="Search" onClick="javascript:formCommandSubmit('search');">
				</div>
				<div class="search-filters">
					<div class="input-group search-avail">
						<span class="input-group-text">Availability</span>
						<input type="text" class="form-control" name="avail" size="10">
					</div>
					<div class="input-group search-size">
						<span class="input-group-text">Min size</span>
						<input type="text" class="form-control" name="minsize" size="5">
						<select class="form-select" name="minsizeu">
						<option>Byte</option>
						<option>KByte</option>
						<option selected>MByte</option>
						<option>GByte</option>
						</select>
						<span class="input-group-text">Max size</span>
						<input type="text" class="form-control" name="maxsize" size="5">
						<select class="form-select" name="maxsizeu">
						<option>Byte</option>
						<option>KByte</option>
						<option selected>MByte</option>
						<option>GByte</option>
						</select>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="panel panel-tasks">
		<div class="panel-body container panel-center">
			<div class="input-group search-download">
				<span class="input-group-text">For each element selected</span>
				<a class="btn btn-success" title="Download" href="javascript:formCommandSubmit('download');">Download</a>
				<span class="input-group-text">in category</span>
				<select class="form-select" name="targetcat">
				<?php
                	$cats = amule_get_categories();
                	foreach($cats as $c) {
                		echo "<option>", $c, "</option>";
                	}
                ?>
				</select>
			</div>
		</div>
	</div>

	<!-- BEGIN OF CENTRAL BODY -->
	<div class="container-fluid panel-tr">
		<!-- Table Download -->
		<div class="panel panel-last">
		<div class="panel-heading panel-center"><h4>SEARCH RESULTS</h4></div>
			<div class="table-responsive"><table class="table">
				<thead>
					<tr>
						<th><input type="checkbox" name="selectAllFiles" onclick='selectAll(this);'></th>
						<th><a href="amuleweb-main-search.php?sort=name">File name</a></th>
						<th><a href="amuleweb-main-search.php?sort=size">Size</a></th>
						<th><a href="amuleweb-main-search.php?sort=sources">Sources</a></th>
					</tr>
				</thead>
				<tbody>

				<?php
					function CastToXBytes($size) {
						if ( $size < 1024 ) {
							$result = $size . " b";
						} elseif ( $size < 1048576 ) {
							$result = ($size / 1024.0) . "kb";
						} elseif ( $size < 1073741824 ) {
							$result = ($size / 1048576.0) . "mb";
						} else {
							$result = ($size / 1073741824.0) . "gb";
						}
						return $result;
					}

					$sort_order;$sort_reverse;

					function my_cmp($a, $b) {
						global $sort_order, $sort_reverse;

						switch ( $sort_order) {
							case "size": $result = $a->size > $b->size; break;
							case "name": $result = $a->name > $b->name; break;
							case "sources": $result = $a->sources > $b->sources; break;
						}

						if ( $sort_reverse ) {
							$result = !$result;
						}

						return $result;
					}

					function str2mult($str) {
						$result = 1;
						switch($str) {
							case "Byte":	$result = 1; break;
							case "KByte":	$result = 1024; break;
							case "MByte":	$result = 1012*1024; break;
							case "GByte":	$result = 1012*1024*1024; break;
						}
						return $result;
					}

					function cat2idx($cat) {
					        	$cats = amule_get_categories();
					        	$result = 0;
					        	foreach($cats as $i => $c) {
					        		if ( $cat == $c) $result = $i;
					        	}
					    		return $result;
					}

					if ($_SESSION["guest_login"] == 0) {
						if ( $HTTP_GET_VARS["command"] == "search") {
							$search_type = -1;
							switch($HTTP_GET_VARS["searchtype"]) {
								case "Local": $search_type = 0; break;
								case "Global": $search_type = 1; break;
								case "Kad": $search_type = 2; break;
							}
							$min_size = $HTTP_GET_VARS["minsize"] == "" ? 0 : $HTTP_GET_VARS["minsize"];
							$max_size = $HTTP_GET_VARS["maxsize"] == "" ? 0 : $HTTP_GET_VARS["maxsize"];

							$min_size *= str2mult($HTTP_GET_VARS["minsizeu"]);
							$max_size *= str2mult($HTTP_GET_VARS["maxsizeu"]);

							amule_do_search_start_cmd($HTTP_GET_VARS["searchval"],
								//$HTTP_GET_VARS["ext"], $HTTP_GET_VARS["filetype"],
								"", "",
								$search_type, $HTTP_GET_VARS["avail"], $min_size, $max_size);
						} elseif ( $HTTP_GET_VARS["command"] == "download") {
							foreach ( $HTTP_GET_VARS as $name => $val) {
								// this is file checkboxes
								if ( (strlen($name) == 32) and ($val == "on") ) {
									$cat = $HTTP_GET_VARS["targetcat"];
									$cat_idx = cat2idx($cat);
									amule_do_search_download_cmd($name, $cat_idx);
								}
							}
						} else {
						}
					}
					$search = amule_load_vars("searchresult");

					$sort_order = $HTTP_GET_VARS["sort"];

					if ( $sort_order == "" ) {
						$sort_order = $_SESSION["search_sort"];
					} else {
						if ( $_SESSION["search_sort_reverse"] == "" ) {
							$_SESSION["search_sort_reverse"] = 0;
						} else {
							$_SESSION["search_sort_reverse"] = !$_SESSION["search_sort_reverse"];
						}
					}

					$sort_reverse = $_SESSION["search_sort_reverse"];
					if ( $sort_order != "" ) {
						$_SESSION["search_sort"] = $sort_order;
						usort(&$search, "my_cmp");
					}

					foreach ($search as $file) {
						echo '<tr>';
						echo '<td><input type="checkbox" name="', $file->hash, '" ></td>';
						echo '<td><label>',$file->name,'</label></td>';
						echo '<td>', CastToXBytes($file->size), '</td>';
						echo '<td><span class="badge badge-default">', $file->sources, '</span></td>';
						echo '</tr>';
					}


				?>
				</tbody>
			</table></div>
		</div>
	</div>
	</form>

	<!-- Piè di pagina: link ed2k e stato delle connessioni -->
	<div id="footer">
		<form name="formlink" method="post" class="form-inline" action="amuleweb-main-search.php" role="form" id="formed2link">
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
