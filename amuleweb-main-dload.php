<!DOCTYPE html>
<html data-bs-theme="dark">
<head>
	<title>aMule - Control Panel - Downloads and Uploads</title>
	<?php
		if ( $_SESSION["auto_refresh"] > 0 ) {
			echo "<meta http-equiv=\"refresh\" content=\"", $_SESSION["auto_refresh"], '">';
		}
	?>
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
	function formCommandSubmit(command, confermato)
	{
		if ( command == "cancel" && !confermato ) {
			var boxchecked = document.querySelectorAll('input[type="checkbox"]:checked');
			var selectedFiles = Object.values(boxchecked).filter(selected => selected.name != 'selectAllFiles').length;
			if (selectedFiles == 0)
				return;
			// finestra di conferma nei colori del tema (amule.js); se si conferma, riparte da qui
			amuleConfirm("Delete selected " + selectedFiles + (selectedFiles == 1 ? " file?" : " files?"), "Delete",
				function () { formCommandSubmit(command, true); }, true);
			return;
		}
		if ( command != "filter" ) {
			<?php
				if ($_SESSION["guest_login"] != 0) {
						echo 'alert("You logged in as guest - commands are disabled");';
						echo "return;";
				}
			?>
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

<body class="page-dload animated fadeIn">
	<!-- Barra di navigazione (uguale in tutte le pagine) -->
	<nav class="navbar fixed-top amule-navbar">
		<div class="container">
			<a class="navbar-brand" href="#"><img src="logo-nav-brax.png" class="logo-nav" alt=""><span class="brand-text">aMule WebUI</span></a>
			<div class="amule-menu">
				<div class="btn-group">
					<a class="btn btn-icon navbar-link active" title="Downloads and Uploads" href="amuleweb-main-dload.php" aria-current="page"><span class="bi bi-arrow-left-right"></span><span class="btn-label">Transfer</span></a>
					<a class="btn btn-icon navbar-link" title="Sharing" href="amuleweb-main-shared.php"><span class="bi bi-share-fill"></span><span class="btn-label">Shared</span></a>
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
    <form action="amuleweb-main-dload.php" method="post" name="mainform">
    <input type="hidden" name="command">
    <div class="panel panel-tasks">
  		<div class="panel-body container panel-center">
    		<div class="form-inline form-tasks">
    		<div class="btn-group">
    			<a class="btn btn-icon" href="javascript:formCommandSubmit('pause');" title="Pause">
						<span class="bi bi-pause-fill"></span><span class="btn-label">Pause</span></a>
    			<a class="btn btn-icon" href="javascript:formCommandSubmit('resume');" title="Resume">
						<span class="bi bi-play-fill"></span><span class="btn-label">Resume</span></a>
    		</div>
    		<div class="btn-group">
    			<a class="btn btn-icon" href="javascript:formCommandSubmit('priodown');" title="Lower priority">
						<span class="bi bi-arrow-down-circle"></span><span class="btn-label">Lower Priority</span></a>
    			<a class="btn btn-icon" href="javascript:formCommandSubmit('cancel');" title="Remove">
						<span class="bi bi-x-circle"></span><span class="btn-label">Remove</span></a>
    			<a class="btn btn-icon" href="javascript:formCommandSubmit('prioup');" title="Higher priority">
						<span class="bi bi-arrow-up-circle"></span><span class="btn-label">High Priority</span></a>
    		</div>
    		<!-- Inserting filtering php -->
    		<div class="btn-group">
     		<?php
    			$all_status = array("all", "Waiting", "Paused", "Downloading");
 				if ( $HTTP_GET_VARS["command"] == "filter") {
 					$_SESSION["filter_status"] = $HTTP_GET_VARS["status"];
 					$_SESSION["filter_cat"] = $HTTP_GET_VARS["category"];
 				}
        		if ( $_SESSION["filter_status"] == '') $_SESSION["filter_status"] = 'all';

        		if ( $_SESSION["filter_cat"] == '') $_SESSION["filter_cat"] = 'all';

        		echo '<select name="status" id="filter" class="form-select"> ';
        		foreach ($all_status as $s) {
        			echo (($s == $_SESSION["filter_status"]) ? '<option selected>' : '<option>'), $s, '</option>';
        		}
        		echo '</select>';

        		echo '<select name="category" id="category" class="form-select">';
				$cats = amule_get_categories();
				foreach($cats as $c) {
					echo (($c == $_SESSION["filter_cat"]) ? '<option selected>' : '<option>'), $c, '</option>';
				}
				echo '</select>';
    		?>
    		<a class="btn btn-icon btn-filter" href="javascript:formCommandSubmit('filter');" title="Filter">
					<span class="bi bi-funnel-fill"></span><span class="btn-label">Filter</span></a>
    		<?php
    			if ($_SESSION["guest_login"] != 0) {
				    echo '<br><br><span class="label label-warning">You logged in as guest - commands are disabled</span>';
				}
			?>
			</div>
  		</div>
  		</div>
	</div>
	<!-- BEGIN OF CENTRAL BODY -->
	<div class="container-fluid panel-tr">

		<!-- Table Download -->
		<div class="panel panel-download">
		<div class="panel-heading panel-center"><h4>DOWNLOAD</h4></div>
			<div class="table-responsive"><table class="table">
				<thead>
					<tr>
						<th><input type="checkbox" name="selectAllFiles" onclick='selectAll(this);'></th>
						<th><a href="amuleweb-main-dload.php?sort=name">File name</a></th>
						<th><a href="amuleweb-main-dload.php?sort=size">Size</a></th>
						<th><a href="amuleweb-main-dload.php?sort=size_done">Completed</a></th>
						<th><a href="amuleweb-main-dload.php?sort=speed">Speed</a></th>
						<th><a href="amuleweb-main-dload.php?sort=progress">Progress</a></th>
						<th><a href="amuleweb-main-dload.php?sort=srccount">Sources</a></th>
						<th><a href="amuleweb-main-dload.php?sort=status">Status</a></th>
						<th><a href="amuleweb-main-dload.php?sort=prio">Priority</a></th>
					</tr>
				</thead>
				<tbody>
				<?php
					function CastToXBytes($size, &$count) {
						$count += $size;
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

					function StatusClass($file) {
						if ( $file->status == 7 ) {
							return '<span class="label label-info">Paused</span>';
						} elseif ( $file->src_count_xfer > 0 ) {
							return '<span class="label label-success">Downloading</span>';
						} else {
							return '<span class="label label-warning">Waiting</span>';
						}
					}

					function StatusString($file) {
						if ( $file->status == 7 ) {
							return 'Paused';
						} elseif ( $file->src_count_xfer > 0 ) {
							return 'Downloading';
						} else {
							return 'Waiting';
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

					//
					// declare it here, before any function reffered it in "global"
					//
					$sort_order; $sort_reverse;

					function my_cmp($a, $b)	{
						global $sort_order, $sort_reverse;

						switch ( $sort_order) {
							case "size": $result = $a->size > $b->size; break;
							case "size_done": $result = $a->size_done > $b->size_done; break;
							case "progress": $result = (((float)$a->size_done)/((float)$a->size)) > (((float)$b->size_done)/((float)$b->size)); break;
							case "name": $result = $a->name > $b->name; break;
							case "speed": $result = $a->speed > $b->speed; break;
							case "scrcount": $result = $a->src_count > $b->src_count; break;
							case "status": $result = StatusClass($a) > StatusClass($b); break;
							case "prio": $result = $a->prio < $b->prio; break;
						}

						if ( $sort_reverse ) {
							$result = !$result;
						}
						//var_dump($sort_reverse);
						return $result;
					}

					function create_prg_bar($file) {

						$done = ((float)$file->size_done*100)/((float)$file->size);
						$status = StatusCode($file);

						switch (StatusCode($file)) {
							case -1: // waiting
								$status = 'class="progress-bar progress-bar-warning"';
								break;
							case 0: // downloading
								$status = 'class="progress-bar progress-bar-info"';
								break;
							case 1: // paused
								$status ='class="progress-bar progress-bar-info"';
								break;
							default: // paused
								$status ='class="progress-bar progress-bar-danger"';
								break;
						}
						echo '	<div class="progress"
						            data-toggle="popover" data-placement="bottom" data-title="Segments"
								    data-html="true" data-trigger="hover" data-content='. "'" . $file->progress . "'" . '>
								    <div '.$status.' role="progressbar" aria-valuenow="'.$done.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$done.'%"></div>
								</div>';
					}
					function create_tooltip($name) {
						echo '	<div class="texte"
									data-toggle="popoverTooltip" data-placement="bottom"
									data-html="true" data-trigger="hover" >
									<b>&nbsp;'. $name .'</b>
								
								</div>
								<div class="popper-content hide">&nbsp;'. $name .'</div>';
					}
					// perform command before processing content
					if ( ($HTTP_GET_VARS["command"] != "") && ($_SESSION["guest_login"] == 0) ) {
						foreach ( $HTTP_GET_VARS as $name => $val) {
							// this is file checkboxes
							if ( (strlen($name) == 32) and ($val == "on") ) {
								//var_dump($name);
								amule_do_download_cmd($name, $HTTP_GET_VARS["command"]);
							}
						}
						// check "filter-by-status" settings
						if ( $HTTP_GET_VARS["command"] == "filter") {
							//var_dump($_SESSION);
							$_SESSION["filter_status"] = $HTTP_GET_VARS["status"];
							$_SESSION["filter_cat"] = $HTTP_GET_VARS["category"];
						}
					}
					if ( $_SESSION["filter_status"] == "") $_SESSION["filter_status"] = "all";
					if ( $_SESSION["filter_cat"] == "") $_SESSION["filter_cat"] = "all";
					$countSize = 0;
					$countCompleted = 0;
					$countSpeed = 0;
					$downloads = amule_load_vars("downloads");
					$fakevar=0;
					$sort_order = $HTTP_GET_VARS["sort"];

					if ( $sort_order == "" ) {
						$sort_order = $_SESSION["download_sort"];
					} else {
						if ( $_SESSION["download_sort_reverse"] == "" ) {
							$_SESSION["download_sort_reverse"] = 0;
						} else {
							if ( $HTTP_GET_VARS["sort"] != '') {
								$_SESSION["download_sort_reverse"] = !$_SESSION["download_sort_reverse"];
							}
						}
					}
					//var_dump($_SESSION);
					$sort_reverse = $_SESSION["download_sort_reverse"];
					if ( $sort_order != "" ) {
						$_SESSION["download_sort"] = $sort_order;
						usort(&$downloads, "my_cmp");
					}
					// Prepare categories index array
					$cats = amule_get_categories();
					foreach($cats as $i => $c) {
						$cat_idx[$c] = $i;
					}

					foreach ($downloads as $file) {
						$filter_status_result = ($_SESSION["filter_status"] == "all") or
							($_SESSION["filter_status"] == StatusString($file));

						$filter_cat_result = ($_SESSION["filter_cat"] == "all") or
							($cat_idx[ $_SESSION["filter_cat"] ] == $file->category);

						if ( $filter_status_result and $filter_cat_result) {
							print "<tr>";
								// Checkbox
								echo "<td class='texte'>", '<div class="checkbox download-checkbox"><input type="checkbox" name="', $file->hash, '" >', "</div></td>";
								// Name
								echo "<td class='texte texte-full-name'>", '<label>', create_tooltip($file->name) , "</label></td>";
								echo "<td class='texte'>", CastToXBytes($file->size, $countSize), "</td>";
								// Size
								echo "<td class='texte'>", CastToXBytes($file->size_done, $countCompleted), "&nbsp;(",
									((float)$file->size_done*100)/((float)$file->size), "%)</td>";
								// Speed
								echo "<td class='texte'>", ($file->speed > 0) ? (CastToXBytes($file->speed, $countSpeed) . "/s") : "-", "</td>";
								// Progress
								echo "<td><div>", create_prg_bar($file),"</div><div class='progress-bar-complex'>", $file->progress, "</div></td>";
								//echo "<td class='texte'>", create_prg_bar($file), "</td>";
								// Sources
								echo "<td class='texte'>";
								if ( $file->src_count_not_curr != 0 ) {
									echo $file->src_count - $file->src_count_not_curr, " / ";
								}
								echo $file->src_count, " ( ", $file->src_count_xfer, " ) ";
								if ( $file->src_count_a4af != 0 ) {
									echo "+ ", $file->src_count_a4af;
								}
								echo "</td>";
								// Status
								echo "<td class='texte'>", StatusClass($file), "</td>";
								// Priority
								echo "<td class='texte'>", PrioString($file), "</td>";
							echo "</tr>";
							
						}
					}
					if (count($downloads) > 0 and $countSize > 0) {
						print "<tr>";
						echo "<td class='total'></td>";
						echo "<td class='total total-label'>Total</td>";
						echo "<td class='total'>", CastToXBytes($countSize, $fakevar), "</td>";
						echo "<td class='total'>", CastToXBytes($countCompleted, $fakevar), "&nbsp;(",
							($countSize > 0) ? ((float)$countCompleted*100)/((float)$countSize) : "0", "%)</td>";
						echo "<td class='total'>", ($countSpeed > 0) ? (CastToXBytes($countSpeed, $fakevar) . "/s" ) : "", "</td>";
						echo "<td class='total'></td>";
						echo "<td class='total'></td>";
						echo "<td class='total'></td>";
						echo "<td class='total'></td>";
						echo "</tr>";
					}
					?>
				</tbody>
			</table></div>
		</div>
		</form>

		<!-- Table Upload -->
		<div class="panel panel-upload">
		<div class="panel-heading panel-center"><h4>UPLOAD</h4></div>
			<div class="table-responsive"><table class="table">
				<thead>
					<tr>
						<th>File Name</th>
						<th>Username</th>
						<th>Up</th>
						<th>Down</th>
						<th>Speed</th>
					</tr>
				</thead>
				<tbody>
				<?php
					function CastToXBytes($size, &$count) {
						$count += $size;
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
					function utf8_rawurldecode($raw_url_encoded){
						$enc = rawurldecode($raw_url_encoded);
						if(utf8_encode(utf8_decode($enc))==$enc){;
							return rawurldecode($raw_url_encoded);
						}else{
							return utf8_encode(rawurldecode($raw_url_encoded));
						}
					}
					function create_tooltip($name) {
						echo '	<div class="texte"
									data-toggle="popoverTooltip" data-placement="bottom"
									data-html="true" data-trigger="hover" >
									<b>&nbsp;'. $name .'</b>
								
								</div>
								<div class="popper-content hide">&nbsp;'. $name .'</div>';
					}
					$countUploadDimension = 0;
					$countDownloadDimension = 0;
					$countSpeed = 0;
					$uploads = amule_load_vars("uploads");
					$fakevar=0;

					foreach ($uploads as $file) {
						echo "<tr>";
						// Name
						echo "<td class='texte texte-full-name'><b>", create_tooltip($file->name) , "</b></td>";
						// User name
						echo "<td class='texte'>", $file->user_name, "</td>";
						// Upload dimension
						echo "<td class='texte'>", CastToXBytes($file->xfer_up, $countUploadDimension), "</td>";
						// Download dimension
						echo "<td class='texte'>", CastToXBytes($file->xfer_down, $countDownloadDimension), "</td>";
						// Speed
						echo "<td class='texte'>", ($file->xfer_speed > 0) ? (CastToXBytes($file->xfer_speed, $countSpeed) . "/s") : "", "</td>";
						echo "</tr>";
					}
					if (count($uploads)>0) {
						echo "<tr>";
						echo "<td class='total'></td>";
						echo "<td class='total total-label'>Total</td>";
						echo "<td class='total'>", CastToXBytes($countUploadDimension, $fakevar), "</td>";
						echo "<td class='total'>", CastToXBytes($countDownloadDimension, $fakevar), "</td>";
						echo "<td class='total'>", CastToXBytes($countSpeed, $fakevar) . "/s", "</td>";
						echo "</tr>";
					}
				?>
				</tbody>
			</table></div>
		</div>


	</div>
	<!-- Piè di pagina: link ed2k e stato delle connessioni -->
	<div id="footer">
		<form name="formlink" method="post" class="form-inline" action="amuleweb-main-dload.php" role="form" id="formed2link">
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
