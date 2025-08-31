<?php
/*========================================================================================
* +-+-+-+-+-+ Project    : V1P3RBOX
* |V|1|P|3|R| Author     : V1P3Rツ
* +-+-+-+-+-+ Version    : V4
========================================================================================*/

  /* Main Rulez */
  error_reporting(0);
  @set_time_limit(ini_get('0'));
  
  /* Require Files */
  require_once '../../includes/Functions.php';

?>

<!DOCTYPE html>

<html>

<head>

  <meta charset="utf-8">

  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>V1P3RCHECK</title>

  <meta name="robots" content="noindex, nofollow, noimageindex">

  <meta name="description" content="">

  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="shortcut icon nofollow" href="https://iili.io/2S57Ap1.md.png">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.css">

  <style>

    .ui.container{padding:20px}h2{margin-bottom:0!important}table{width:100%!important;max-width:100%!important}th a{color:#00b5ad}

  </style>

</head>

<body>

  <section class="ui container fluid">

    <div align="center">
      
      <h2 class="ui icon header">
      
        <i class="viacoin icon"></i>
      
        <div class="content">
      
          V1P3RCHECKツ
      
          <div class="sub header">IP Blacklist Check application.</div>
      
        </div>
      
      </h2>
    
    </div>

    <table class="ui teal single line selectable celled striped fixed three column table small">

      <thead>
      
        <tr>

          <th colspan="3">

            Please Wait While Checking <?php echo $_GET['ServerIP']; ?> In Multiple DNSBLs...
          
          </th>

          <th class="center aligned">
          
            <small>
              
              &copy; 2018-<?php echo date('Y'); ?> By <a href="https://t.me/V1P3R_404/" target="_blank">V1P3Rツ</a> - All rights reserved.
            
            </small>

          </th>

        </tr>

      </thead>

      <tbody>

      </tbody>

        <?php

          if(isset($_GET['ServerIP']) && $_GET['ServerIP'] != null):

            $ServerIP = $_GET['ServerIP'];

            echo (filter_var($ServerIP, FILTER_VALIDATE_IP)) ? V1P3R_Blacklist($ServerIP) : '<tr><td colspan="4">Please enter a valid IP</td></tr>';
          
          else:

            echo '<tr><td colspan="4">No IP Found</td></tr>';

          endif;

        ?>

    </table>

  </section>

</body>

</html>
