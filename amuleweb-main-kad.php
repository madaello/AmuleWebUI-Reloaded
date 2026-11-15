<!DOCTYPE html>
<html data-bs-theme="dark">
<head>
	<title>aMule - Control Panel - KAD</title>
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
  function formCommandSubmit(command, confermato)
  {
  	if ( command == "cancel" && !confermato ) {
  		amuleConfirm("Delete selected files?", "Delete", function () { formCommandSubmit(command, true); }, true);
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

  </script>
</head>

<body class="page-kad animated fadeIn">
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
					<a class="btn btn-icon navbar-link active" title="Kademlia" href="amuleweb-main-kad.php" aria-current="page"><span class="bi bi-asterisk"></span><span class="btn-label">Kad</span></a>
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

	<!-- Comandi: connessione a Kad tramite un nodo noto -->
	<form name="mainform" action="amuleweb-main-kad.php" method="post" role="form">
	<div class="panel panel-tasks">
		<div class="panel-body container panel-center">
			<div class="input-group kad-bootstrap">
				<span class="input-group-text kad-title">Bootstrap from Node</span>
				<input class="form-control kad-ip" type="text" name="ip3" maxlength="3" placeholder="255" inputmode="numeric" aria-label="IP 1">
				<span class="input-group-text kad-sep">.</span>
				<input class="form-control kad-ip" type="text" name="ip2" maxlength="3" placeholder="255" inputmode="numeric" aria-label="IP 2">
				<span class="input-group-text kad-sep">.</span>
				<input class="form-control kad-ip" type="text" name="ip1" maxlength="3" placeholder="255" inputmode="numeric" aria-label="IP 3">
				<span class="input-group-text kad-sep">.</span>
				<input class="form-control kad-ip" type="text" name="ip0" maxlength="3" placeholder="255" inputmode="numeric" aria-label="IP 4">
				<span class="input-group-text kad-sep">:</span>
				<input class="form-control kad-port" type="text" name="port" maxlength="5" placeholder="Port" inputmode="numeric" aria-label="Port">
				<input class="btn btn-warning" type="submit" name="Submit" value="Connect">
			</div>
		</div>
	</div>
	</form>

  <!-- BEGIN OF CENTRAL BODY -->
  <div class="container-fluid panel-tr panel-last">
    <div class="panel">
    <div class="panel-heading panel-center"><h4>KAD STATUS</h4></div>
      <?php
        function print_table($kad1, $kad2) {
          echo '<table class="table status-table">
          <thead>
            <tr>
              <th>Parameter</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Connection</td>
              <td>'.$kad1.'</td>
            </tr>
            <tr>
              <td>Firewall</td>
              <td>'.$kad2.'</td>
            </tr>
            <tr>
              <td colspan="2" class="text-center">
                <img src="amule_stats_kad.png" width="500" height="200" border="0" alt="" title="" />
              </td>
            </tr>
          </tbody>
        </table>';
        }

        function print_kad1($status) {
          if ($status["kad_connected"] == 1) {
            if ($status["kad_firewalled"] == 0) { return '<span class="label label-success">OK</span>'; }
            else { return '<span class="label label-warning">OK</span>'; }
          } else {
            return '<span class="label label-danger">ERROR</span>';
          }
        }
        function print_kad2($status) {
            if ($status["kad_firewalled"] == 0) { return '<span class="label label-success">OK</span>'; }
            else { return '<span class="label label-danger">ERROR</span>'; }
        }

        $stats = amule_get_stats();
        //$stats_tree = amule_load_vars("stats_tree");

        $kad1 = print_kad1($stats);
        $kad2 = print_kad2($stats);

        print_table($kad1, $kad2);
      ?>
    </div>
  </div>

	<!-- Piè di pagina: link ed2k e stato delle connessioni -->
	<div id="footer">
		<form name="formlink" method="post" class="form-inline" action="amuleweb-main-kad.php" role="form" id="formed2link">
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
