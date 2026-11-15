<!DOCTYPE html>
<html data-bs-theme="dark">
<head>
	<title>aMule - Control Panel - Preferences</title>
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
  function formCommandSubmit(command) {
    var frm=document.forms.mainform
    frm.command.value=command
    frm.submit()
  }

  var initvals = new Object;

  <?php
    // apply new options before proceeding

    if ( ($HTTP_GET_VARS["Submit"] == "Apply") && ($_SESSION["guest_login"] == 0) ) {
      $file_opts = array("check_free_space", "extract_metadata",
        "ich_en","aich_trust", "preview_prio","save_sources", "resume_same_cat",
        "min_free_space", "new_files_paused", "alloc_full", "alloc_full_chunks",
        "new_files_auto_dl_prio", "new_files_auto_ul_prio"
      );
      $conn_opts = array("max_line_up_cap","max_up_limit",
        "max_line_down_cap","max_down_limit", "slot_alloc",
        "tcp_port","udp_dis","max_file_src","max_conn_total","autoconn_en");
      $webserver_opts = array("use_gzip", "autorefresh_time");

      $all_opts;
      foreach ($conn_opts as $i) {
        $curr_value = $HTTP_GET_VARS[$i];
        if ( $curr_value == "on") $curr_value = 1;
        if ( $curr_value == "") $curr_value = 0;

        $all_opts["connection"][$i] = $curr_value;
      }
      foreach ($file_opts as $i) {
        $curr_value = $HTTP_GET_VARS[$i];
        if ( $curr_value == "on") $curr_value = 1;
        if ( $curr_value == "") $curr_value = 0;

        $all_opts["files"][$i] = $curr_value;
      }
      foreach ($webserver_opts as $i) {
        $curr_value = $HTTP_GET_VARS[$i];
        if ( $curr_value == "on") $curr_value = 1;
        if ( $curr_value == "") $curr_value = 0;

        $all_opts["webserver"][$i] = $curr_value;
      }
      //var_dump($all_opts);
      amule_set_options($all_opts);
    }

    $opts = amule_get_options();

    $opt_groups = array("connection", "files", "webserver");

    foreach ($opt_groups as $group) {
      $curr_opts = $opts[$group];
      //var_dump($curr_opts);
      foreach ($curr_opts as $opt_name => $opt_val) {
        echo 'initvals["', $opt_name, '"] = "', $opt_val, '";';
      }
    }
  ?>

  function focusName(name) {
    document.getElementsByName(name)[0].focus();
  }


  function toggleStatus(object, target) {
    check = document.getElementsByName(object)[0];
    text = document.getElementsByName(target)[0];
    if (check.checked) {
      text.disabled = false;
    } else {
      text.disabled = true;
    }
  }

  function init_data() {
    var frm = document.forms.mainform

    var str_param_names = new Array(
      "max_line_down_cap", "max_line_up_cap",
      "max_up_limit", "max_down_limit", "max_file_src",
      "slot_alloc", "max_conn_total",
      "tcp_port", "udp_port",
      "min_free_space",
      "autorefresh_time"
      )
    for(i = 0; i < str_param_names.length; i++) {
      frm[str_param_names[i]].value = initvals[str_param_names[i]];
    }
    var check_param_names = new Array(
      "autoconn_en", "reconn_en", "udp_dis", "new_files_paused",
      "aich_trust", "alloc_full", "alloc_full_chunks",
      "check_free_space", "extract_metadata", "ich_en",
      "new_files_auto_dl_prio", "new_files_auto_ul_prio",
      "use_gzip"
      )
    for(i = 0; i < check_param_names.length; i++) {
      frm[check_param_names[i]].checked = initvals[check_param_names[i]] == "1" ? true : false;
      console.log(check_param_names[i]);
    }
    frm["min_free_space"].disabled = initvals["check_free_space"] == "1" ? false : true;
  }

  document.addEventListener('DOMContentLoaded', init_data);

  </script>
</head>

<body class="page-prefs animated fadeIn">
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
					<a class="btn btn-icon navbar-link" title="Statistics" href="amuleweb-main-stats.php"><span class="bi bi-bar-chart-fill"></span><span class="btn-label">Stats</span></a>
				</div>
				<div class="btn-group">
					<a class="btn btn-icon navbar-link active" title="Configurations" href="amuleweb-main-prefs.php" aria-current="page"><span class="bi bi-gear-fill"></span><span class="btn-label">Settings</span></a>
					<a class="btn btn-icon navbar-link" title="Log" href="amuleweb-main-log.php"><span class="bi bi-flag-fill"></span><span class="btn-label">Logs</span></a>
					<a class="btn btn-icon navbar-link" title="Exit" href="login.php"><span class="bi bi-power"></span><span class="btn-label">Exit</span></a>
				</div>
			</div>
		</div>
	</nav>

    <!-- Center body -->
    <form name="mainform" action="amuleweb-main-prefs.php" method="post">
           <!-- Commands -->
    <div class="panel panel-tasks">
      <div class="panel-body container panel-center">
        <div class="form-inline form-tasks">
            <?php
              if ($_SESSION["guest_login"] == 0) {
                echo '<input class="btn btn-warning" type="submit" name="Submit" value="Apply">';
              } else {
                echo '<input class="btn btn-warning" type="submit" name="Submit" value="Apply" disabled>';
                echo '<br><br><span class="label label-warning">You logged in as guest - commands are disabled</span>';
              }
            ?>
          </div>
        </div>
      </div>

         <div class="container-fluid panel-tr panel-last">
      <div class="panel">
      <div class="panel-heading panel-center"><h4>PREFERENCES</h4></div>

        <div class="prefs-box">
          <b class="form-control pref-section">WEBSERVER</b>

          <div class="input-group pref-row">
            <label class="form-control pref-label" onclick="javascript:focusName('autorefresh_time')">
                <input type="checkbox" class="form-check-input" name="nothing" disabled>&nbsp;&nbsp;
                Page refresh interval
              </label>
              <input type="text" class="form-control pref-value" name="autorefresh_time">
          </div>
          <div class="input-group pref-row">
            <label class="form-control pref-label">
                <input type="checkbox" class="form-check-input" name="use_gzip">&nbsp;&nbsp;
                Use gzip compression
              </label>
              <input type="text" class="form-control pref-value" disabled="true" name="nothing">
          </div>

          <b class="form-control pref-section">BANDWIDTH LIMITS</b>

          <div class="input-group pref-row">
            <label class="form-control pref-label" onclick="javascript:focusName('max_down_limit')">
                <input type="checkbox" class="form-check-input" name="nothing" disabled>&nbsp;&nbsp;
                Max download rate
              </label>
              <input type="text" class="form-control pref-value" name="max_down_limit">
          </div>
          <div class="input-group pref-row">
            <label class="form-control pref-label" onclick="javascript:focusName('max_up_limit')">
                <input type="checkbox" class="form-check-input" name="nothing" disabled>&nbsp;&nbsp;
                Max Upload Rate
              </label>
              <input type="text" class="form-control pref-value" name="max_up_limit">
          </div>
          <div class="input-group pref-row">
            <label class="form-control pref-label" onclick="javascript:focusName('slot_alloc')">
                <input type="checkbox" class="form-check-input" name="nothing" disabled>&nbsp;&nbsp;
                Slot Allocation
              </label>
              <input type="text" class="form-control pref-value" name="slot_alloc">
          </div>

          <b class="form-control pref-section">CONNECTION SETTINGS</b>

          <div class="input-group pref-row">
            <label class="form-control pref-label" onclick="javascript:focusName('max_conn_total')">
                <input type="checkbox" class="form-check-input" name="nothing" disabled>&nbsp;&nbsp;
                Max total connections
              </label>
              <input type="text" class="form-control pref-value" name="max_conn_total">
          </div>
          <div class="input-group pref-row">
            <label class="form-control pref-label" onclick="javascript:focusName('max_file_src')">
                <input type="checkbox" class="form-check-input" name="nothing" disabled>&nbsp;&nbsp;
                Max sources per file
              </label>
              <input type="text" class="form-control pref-value" name="max_file_src">
          </div>
          <div class="input-group pref-row">
            <label class="form-control pref-label">
                <input type="checkbox" class="form-check-input" name="autoconn_en">&nbsp;&nbsp;
                Autoconnect at startup
              </label>
              <input type="text" class="form-control pref-value" disabled="true" name="nothing">
          </div>
          <div class="input-group pref-row">
            <label class="form-control pref-label">
                <input type="checkbox" class="form-check-input" name="reconn_en">&nbsp;&nbsp;
                Reconnect when connection lost
              </label>
              <input type="text" class="form-control pref-value" disabled="true" name="nothing">
          </div>

          <b class="form-control pref-section">CONNECTION SETTINGS</b>

          <div class="input-group pref-row">
            <label class="form-control pref-label" onclick="javascript:focusName('tcp_port')">
                <input type="checkbox" class="form-check-input" name="nothing" disabled>&nbsp;&nbsp;
                TCP Port
              </label>
              <input type="text" class="form-control pref-value" name="tcp_port">
          </div>
          <div class="input-group pref-row">
            <label class="form-control pref-label" onclick="javascript:focusName('udp_port')">
                <input type="checkbox" class="form-check-input" name="nothing" disabled>&nbsp;&nbsp;
                UDP Port
              </label>
              <input type="text" class="form-control pref-value" name="udp_port">
          </div>
          <div class="input-group pref-row">
            <label class="form-control pref-label">
                <input type="checkbox" class="form-check-input" name="udp_dis">&nbsp;&nbsp;
                Disable UDP connections
              </label>
              <input type="text" class="form-control pref-value" disabled="true" name="nothing">
          </div>

        <b class="form-control pref-section">LINE CAPACITY (STATISTICS)</b>

          <div class="input-group pref-row">
            <label class="form-control pref-label" onclick="javascript:focusName('max_line_down_cap')">
                <input type="checkbox" class="form-check-input" name="nothing" disabled>&nbsp;&nbsp;
                Max download rate
              </label>
              <input type="text" class="form-control pref-value" name="max_line_down_cap">
          </div>
          <div class="input-group pref-row">
            <label class="form-control pref-label" onclick="javascript:focusName('max_line_up_cap')">
                <input type="checkbox" class="form-check-input" name="nothing" disabled>&nbsp;&nbsp;
                Max upload rate
              </label>
              <input type="text" class="form-control pref-value" name="max_line_up_cap">
          </div>

          <b class="form-control pref-section">FILE SETTINGS</b>

          <div class="input-group pref-row">
            <label class="form-control pref-label">
                <input type="checkbox" class="form-check-input" name="check_free_space"
                   onclick="javascript:toggleStatus('check_free_space','min_free_space')">&nbsp;&nbsp;
                Check free minimum space (MB)
              </label>
              <input type="text" class="form-control pref-value" name="min_free_space">
          </div>
          <div class="input-group pref-row">
            <label class="form-control pref-label">
                <input type="checkbox" class="form-check-input" name="new_files_auto_dl_prio">&nbsp;&nbsp;
                Added download with auto priority
              </label>
              <input type="text" class="form-control pref-value" disabled="true" name="nothing">
          </div>
          <div class="input-group pref-row">
            <label class="form-control pref-label">
                <input type="checkbox" class="form-check-input" name="new_files_auto_ul_prio">&nbsp;&nbsp;
                New shared files with auto priority
              </label>
              <input type="text" class="form-control pref-value" disabled="true" name="nothing">
          </div>
          <div class="input-group pref-row">
            <label class="form-control pref-label">
                <input type="checkbox" class="form-check-input" name="ich_en">&nbsp;&nbsp;
                I.C.H. active
              </label>
              <input type="text" class="form-control pref-value" disabled="true" name="nothing">
          </div>
          <div class="input-group pref-row">
            <label class="form-control pref-label">
                <input type="checkbox" class="form-check-input" name="aich_trust">&nbsp;&nbsp;
                AICH trusts every hash (not recommended)
              </label>
              <input type="text" class="form-control pref-value" disabled="true" name="nothing">
          </div>
          <div class="input-group pref-row">
            <label class="form-control pref-label">
                <input type="checkbox" class="form-check-input" name="alloc_full_chunks">&nbsp;&nbsp;
                Alloc full chunks for <code>.part</code> files
              </label>
              <input type="text" class="form-control pref-value" disabled="true" name="nothing">
          </div>
          <div class="input-group pref-row">
            <label class="form-control pref-label">
                <input type="checkbox" class="form-check-input" name="alloc_full">&nbsp;&nbsp;
                Alloc full disk space for <code>.part</code> files
              </label>
              <input type="text" class="form-control pref-value" disabled="true" name="nothing">
          </div>
          <div class="input-group pref-row">
            <label class="form-control pref-label">
                <input type="checkbox" class="form-check-input" name="new_files_paused">&nbsp;&nbsp;
                Add files to download in pause
              </label>
              <input type="text" class="form-control pref-value" disabled="true" name="nothing">
          </div>
          <div class="input-group pref-row">
            <label class="form-control pref-label">
                <input type="checkbox" class="form-check-input" name="extract_metadata">&nbsp;&nbsp;
                Extract MetaData tags
              </label>
              <input type="text" class="form-control pref-value" disabled="true" name="nothing">
          </div>

        </div>


      </div>
    </div>
    </form>

	<!-- Piè di pagina: link ed2k e stato delle connessioni -->
	<div id="footer">
		<form name="formlink" method="post" class="form-inline" action="amuleweb-main-prefs.php" role="form" id="formed2link">
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
