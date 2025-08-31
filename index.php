<?php
/*========================================================================================
* +-+-+-+-+-+ Project    : V1P3RBOX
* |V|1|P|3|R| Author     : V1P3Rツ
* +-+-+-+-+-+ Version    : V4
========================================================================================*/

  /* Main Rulez */
  error_reporting(0);
  @set_time_limit(ini_get('0'));

  /* Main Headers */ 
  header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
  header('Cache-Control: post-check=0, pre-check=0', false);
  header('Pragma: no-cache');
  header('Access-Control-Allow-Origin: *');
  
  /* Set Main Variables */
  $version   = '4';

  $sessionId = sha1(uniqid());

  $jsonFile  = './database/All.json';

  $jsonData  = array_reverse(json_decode(file_get_contents($jsonFile), true));

  $smtpList  = $jsonData['smtp'];

  $offers    = $jsonData['offers'];

  ksort($smtpList);

  ksort($offers);

  /* ==========================================================================
     Start Send
     ========================================================================== */
  if($_SERVER['REQUEST_METHOD'] == 'POST'):

    if(isset($_POST['user']) && isset($_POST['pass'])):

      setcookie('isValid', $sessionId, time() + 86400, '/');

      exit(header('refresh: 0;'));

    else:

      /* Get All Post Values */
      $data = json_decode(file_get_contents('php://input'), true)['data'];
    
      /* Require Files */
      require_once './includes/Functions.php';

      /* Run Script */
      exit(

        json_encode(

          V1P3R_StartSend($sessionId, $data)

        )

      );

    endif;

  endif;

?>

<!DOCTYPE html>

<html>

<head>

  <meta charset="utf-8">

  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>V1P3RBOX</title>

  <meta name="robots" content="noindex, nofollow, noimageindex">

  <meta name="description" content="">

  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="shortcut icon nofollow" href="https://iili.io/2S57Ap1.md.png">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/semantic-ui@2.5.0/dist/semantic.min.css">

  <link rel="stylesheet" href="./public/stylesheets/style.css">

</head>

<body>

  <?php if(!isset($_COOKIE['isValid'])): ?>

    <div class="background">
      
      <div class="ui tiny modal">
       
        <div class="header text">

          <img src="https://iili.io/2SEtk3G.md.png" alt="logo">
        
        </div>
       
        <div class="content">
       
          <form id="sFrm" class="ui form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            
            <div class="field">
            
              <label>

                Username

              </label>
            
              <input type="text" name="user" placeholder="Your Username" autocomplete="off">
            
            </div>
            
            <div class="field">
            
              <label>

                Password

              </label>
            
              <input type="text" name="pass" placeholder="Your Password" autocomplete="off">
            
            </div>
            
            <button id="login" class="ui large fluid black button" type="submit">
              
              Login

            </button>

          </form>
       
        </div>
      
      </div>

    </div>

  <?php else: ?>

    <!-- Start Message -->
    <div class="ui tiny red hidden message">
      
      <i class="close icon"></i>
      
      <div class="content">
      
        <div class="header">
      
          There Is An Error:
      
        </div>
      
        <p>

          Add Recipients First Before Sending Your Message.

        </p>
      
      </div>
    
    </div> <!-- End Message -->

    <!-- Start Aside -->
    <aside class="ui left visible vertical inverted sidebar menu">

      <div class="item" tabindex="0">

        <img class="ui image" src="https://iili.io/2SEtk3G.md.png">

      </div>

      <a class="item" data-href="#Servers" tabindex="0">

        <i class="hdd outline icon"></i>

        Delivery Servers

      </a>

      <a class="item" data-href="#Tools" tabindex="0">

        <i class="lightbulb outline icon"></i>

        Tools Section

      </a>

      <a class="item" data-href="#Header" tabindex="0">

        <i class="file alternate outline icon"></i>

        Message Header

      </a>

      <a class="item" data-href="#Body" tabindex="0">

        <i class="envelope outline icon"></i>

        Message Body

      </a>

      <a class="item" data-href="#Recipients" tabindex="0">

        <i class="address book outline icon"></i>

        Recipients

      </a>

      <a class="item" data-href="#Recipients" tabindex="0">

        <i class="info circle icon"></i>

        Send Status

      </a>

      <div class="item" tabindex="0">

        <div id="CheckScore" class="ui grey fluid animated fade button">

          <div class="visible content">Spam Score</div>

          <div class="hidden content">

            <i class="tachometer alternate icon"></i>

          </div>

        </div>

      </div>

      <div class="item" tabindex="0">

        <div id="start" class="ui teal fluid animated fade button">

          <div class="visible content">Start</div>

          <div class="hidden content">

            <i class="paper plane icon"></i>

          </div>

        </div>

      </div>

      <div class="item visibility" tabindex="0">

        <div id="stop" class="ui fluid animated fade button">

          <div class="visible content">Stop</div>

          <div class="hidden content">

            <i class="ban icon"></i>

          </div>

        </div>

      </div>

      <div class="item visibility" tabindex="0">

        <div class="ui teal tiny indicating progress">

          <div class="bar"></div>

          <div class="label"></div>

        </div>

      </div>

      <a class="item" href="https://t.me/V1P3RBOX_404/" target="_blank">

        <small>
          
          <i class="sync alternate icon"></i>

          Check For Update

        </small>

      </a>

    </aside> <!-- End Aside -->

    <!-- Start Section -->
    <section>

      <!-- Start Header -->
      <header>

        <!-- Start Breadcrumb -->
        <div class="ui mini breadcrumb">

          <div class="active section">
            
            PHP Version
          
          </div>

          <i class="right angle icon divider"></i>

          <div class="section">

            <?php echo phpversion(); ?>

          </div>

          <i class="right arrow icon divider"></i>

          <div class="active section">
            
            ServerIP: <?php echo $_SERVER['SERVER_ADDR']; ?>
          
          </div>

          <i class="right angle icon divider"></i>

          <a

            class="section"

            href="<?php echo 'https://ipapi.co/' . $_SERVER['SERVER_ADDR'] . '/'; ?>"

            target="_blank"

          >

            IP Geolocation

          </a>

          <i class="right arrow icon divider"></i>
          
          <a

            class="section"

            href="<?php echo './public/tools/V1P3RCHECK.php?ServerIP=' . $_SERVER['SERVER_ADDR']; ?>"

            target="_blank"

          >
            
            Check Blacklist 

          </a>
          
        </div> <!-- End Breadcrumb -->
        
      </header> <!-- End Header -->

      <!-- Start Form -->
      <form id="sFrm" class="ui form" method="post" action="">

        <div class="ui text">

          <h4 data-href="#Servers" class="ui top attached block header">

            Delivery Servers

          </h4>

          <div class="ui bottom attached segment">

            <div class="two fields">

              <div class="field">

                <label>

                  Servers

                </label>

                <textarea

                  name="server"

                  placeholder="- If You Don't Have SMTP Login Information's, Leave Blank To Send With Localhost.&#10;- Separate Your Smtp List With A New Line.&#10;- SMTP Format: Host:Port:SSL:User:Pass&#10;- SOCKS Format: Host:Port:SSL&#10;- Supported Protocols: TLS & SSL & NOSSL"

                ></textarea>

                <select class="ui dropdown" name="smtpList">

                  <?php

                    foreach ($smtpList as $key => $smtp): ?>

                    <option value="<?php echo $smtp; ?>">

                      <?php echo $key; ?>

                    </option>

                  <?php endforeach; ?>

                </select>

              </div>

              <div class="field">

                <div class="four fields">

                  <div class="field">

                    <label>

                      Pause Send

                    </label>

                    <input

                      type="text"

                      name="pause"

                      value="0"

                    >

                  </div>

                  <div class="field">

                    <label>

                      Pause After

                    </label>

                    <input

                      type="text"

                      name="pauseAfter"

                      value="0"

                    >

                  </div>

                  <div class="field">

                    <label>

                      Rotation After

                    </label>

                    <input

                      type="text"

                      name="rotation"

                      value="0"

                    >

                  </div>

                  <div class="field">

                    <label>

                      Reconnect After

                    </label>

                    <input

                      type="text"

                      name="reconnect"

                      value="0"

                      disabled

                    >

                  </div>

                </div>

                <div class="four fields">

                  <div class="field">

                    <label>

                      Random After

                    </label>

                    <input

                      type="text"

                      name="randomSend"

                      value="0"

                    >

                  </div>
                  
                  <div class="field">

                    <label>

                      Queue Concurrency

                    </label>

                    <input

                      type="text"

                      name="concurrency"

                      value="6"

                    >

                  </div>

                  <div class="field">

                    <label>

                      Email's In Bcc

                    </label>

                    <input

                      type="text"

                      name="inBCC"

                      value="0"

                    >

                  </div>

                  <div class="field">

                    <label>

                      Debugging

                    </label>

                    <select class="ui fluid dropdown" name="debug">

                      <option value="0">

                        Off

                      </option>

                      <option value="1">

                        Client

                      </option>

                      <option value="2">

                        Server

                      </option>

                      <option value="3">

                        Connection

                      </option>

                      <option value="4">

                        Low Level

                      </option>

                    </select>

                  </div>

                </div>

              </div>

            </div>

          </div>

          <h4 data-href="#Tools" class="ui top attached block header">

            Tools Section

            <div class="ui dropdown">

              <small>

                Tools List
              
              </small>

              <i class="small list icon"></i>
            
              <div class="menu">
                
                <a class="item" href="./public/tools/V1P3RBOUNCE.php" target="_blank"> 
                
                  Bounce Remover
                
                </a>

                <a class="item" href="./public/tools/V1P3RDATA.php" target="_blank">
                  
                  Email Extractor
                
                </a>
                
                <a class="item" href="./public/tools/V1P3RCOMBO.php" target="_blank">
                
                  ISP Combolist Cracker
                
                </a>

                <a class="item" href="./public/tools/V1P3RSMTP.php" target="_blank">
                
                  SMTP Tester
                
                </a>

              </div>

            </div>

          </h4>

          <div class="ui bottom attached segment">

            <div class="fields">
              
              <div class="three wide field">

                <label>

                  Number Of Feeds

                </label>

                <input

                  type="text"

                  name="feedsRow"

                  value="4"

                >

              </div>

              <div class="nine wide field">

                <h5 class="ui header">

                  Generator Setting

                </h5>

                <div class="ui labeled input">

                  <div class="ui basic label">

                    <div class="ui toggle checkbox">

                      <input type="checkbox" tabindex="0" class="hidden" value="AZ">

                      <label>

                        Range A-Z

                      </label>

                    </div>

                    &nbsp;&nbsp;&nbsp;

                    <div class="ui toggle checkbox">

                      <input type="checkbox" tabindex="0" class="hidden" value="az">

                      <label>

                        Range a-z

                      </label>

                    </div>

                    &nbsp;&nbsp;&nbsp;

                    <div class="ui toggle checkbox">

                      <input type="checkbox" tabindex="0" class="hidden" value="09">

                      <label>

                        Range 0-9

                      </label>

                    </div>

                  </div>

                  <input type="text" name="length" placeholder="Length">

                </div>

              </div>

              <div class="four wide field">

                <h5 class="ui header">

                  Pattern Value

                </h5>

                <div class="ui action input">

                  <input type="text" name="pattern" placeholder="Pattern" autocomplete="off">

                  <button type="button" class="ui teal right labeled icon button" id="pBtn">

                    <i class="random icon"></i>

                    Generate

                  </button>

                </div>

              </div>

            </div>

            <div class="ui accordion field">
          
              <div class="active title">
          
                <i class="icon dropdown"></i>
          
                Instruction
          
              </div>
          
              <div class="content active">

                <div class="instruction">

                <div class="two fields">
                  
                  <div class="field">
                    
                    <ol class="ui list">

                      <li value="*"> General Tags

                        <ol>

                          <li value="-">

                            <span>[[AZ-az-09-{N}]]</span>:&nbsp;

                            Random String Generator.

                          </li>

                          <li value="-">

                            <span>[[date]]</span>:&nbsp;

                            Date (<?php echo date('d/m/Y') ?>).

                          </li>

                          <li value="-">

                            <span>[[time]]</span>:&nbsp;

                            Time (<?php echo date('H:i:s') ?>).

                          </li>

                          <li value="-">

                            <span>[[email]]</span>:&nbsp;

                            Reciver Email. <strong><q>BCC Value Must Be 0 To Work</q></strong>

                          </li>

                          <li value="-">

                            <span>[[user]]</span>:&nbsp;

                            Email User (email_user@domain.com). <strong><q>BCC Value Must Be 0 To Work</q></strong>

                          </li>

                          <li value="-">

                            <span>[[domain]]</span>:&nbsp;

                            Server Domain.

                          </li>

                          <li value="-">

                            <span>[[link]]</span>:&nbsp;

                            Your Link.

                          </li>

                          <li value="-">

                            <span>[[image]]</span>:&nbsp;

                            Your Embedded Image.

                          </li>

                          <li value="-">

                            <span>[[feeds]]</span>:&nbsp;

                            If You Want To Use Feeds In Your HTML Message.

                          </li>

                        </ol>

                      </li>
                      
                    </ol>

                  </div>

                  <div class="field">
                    
                    <ol class="ui list">

                      <li value="*"> Example

                        <ol>

                          <li value="-">

                            Hello <strong>[[user]]</strong> ->

                            Hello <strong>user</strong>

                          </li>

                          <li value="-">

                            Your Code Is <strong>[[AZ-az-09-{10}]]</strong> ->

                            Your Code Is <strong>A4s5FhrN9m</strong>

                          </li>

                        </ol>

                      </li>

                      <li value="*"> Note

                        <ol>

                          <li value="-">

                            You Can Read The Entire Content Of The Error Message By Hovering Over The Message.

                          </li>
                          
                          <li value="-">

                            Use 1 In Concurrency To Enable <strong><q>Reconnect After</q></strong> And Keep SMTP Connection Open After Each Message.

                          </li>

                          <li value="-">

                            Pause After, Rotation After, Reconnect After & Random After: 5 = 5 Emails.

                          </li>

                          <li value="-">

                            Pause Send: 2 = 2 Seconds. || Email's In Bcc: 1 Bcc = 1 Email.

                          </li>

                        </ol>

                      </li>
                      
                    </ol>

                  </div>

                </div>

                </div>

              </div>
            
            </div>

          </div>

          <h4 data-href="#Header" class="ui top attached block header">

            Message Header

          </h4>

          <div class="ui bottom attached segment">

            <div class="two fields">

              <div class="field">
                
                <div class="field">

                  <label>

                    Custom Header

                  </label>

                  <textarea name="headers">Message-ID: <[[az-09-{20}]]@[[domain]]>&#10;X-Mailer: V1P3RBOX v<?php echo $version; ?>-Ref[[09-{2}]]&#10;Auto-Submitted: auto-generated&#10;X-Auto-Response-Suppress: OOF, AutoReply&#10;X-Abuse: Please report abuse here <mailto:abuse@[[domain]]?c=[[09-{10}]]></textarea>

                </div>

              </div>

              <div class="field">

                <div class="two fields">

                  <div class="field">

                    <label>

                      Offers List

                    </label>

                    <select class="ui fluid dropdown" name="offers">

                      <?php

                        foreach ($offers as $key => $value): ?>

                        <option

                          value="<?php echo $key; ?>"

                          <?php if($key == 'Default') echo 'selected'; ?>

                        >

                          <?php echo $key; ?>

                        </option>

                      <?php endforeach; ?>

                    </select>

                  </div>
              
                  <div class="field">

                    <label>

                      Priority

                    </label>

                    <select class="ui fluid dropdown" name="priority">

                      <option value="">

                        Default

                      </option>

                      <option value="5">

                        Low

                      </option>

                      <option value="3" selected>

                        Normal

                      </option>

                      <option value="1">

                        High

                      </option>

                    </select>

                  </div>

                </div>
                <div class="four fields">

                  <div class="field">

                    <label>

                      Date

                    </label>

                    <select class="ui fluid dropdown" name="date">

                      <option value="0">

                        Default

                      </option>

                      <option value="1">

                        +1 Day

                      </option>

                      <option value="2">

                        +2 Days

                      </option>

                      <option value="3">

                        +3 Days

                      </option>

                      <option value="4">

                        +4 Days

                      </option>

                      <option value="5">

                        +5 Days

                      </option>

                      <option value="6">

                        +6 Days

                      </option>

                      <option value="7">

                        +7 Days

                      </option>

                      <option value="8">

                        +8 Days

                      </option>

                      <option value="9">

                        +9 Days

                      </option>

                      <option value="10">

                        +10 Days

                      </option>

                      <option value="11">

                        +11 Days

                      </option>

                      <option value="12">

                        +12 Days

                      </option>

                      <option value="13">

                        +13 Days

                      </option>

                      <option value="14">

                        +14 Days

                      </option>

                      <option value="15">

                        +15 Days

                      </option>

                      <option value="16">

                        +16 Days

                      </option>

                      <option value="17">

                        +17 Days

                      </option>

                      <option value="18">

                        +18 Days

                      </option>

                      <option value="19">

                        +19 Days

                      </option>

                      <option value="20">

                        +20 Days

                      </option>

                      <option value="21">

                        +21 Days

                      </option>

                      <option value="22">

                        +22 Days

                      </option>

                      <option value="23">

                        +23 Days

                      </option>

                      <option value="24">

                        +24 Days

                      </option>

                      <option value="25">

                        +25 Days

                      </option>

                      <option value="26">

                        +26 Days

                      </option>

                      <option value="27">

                        +27 Days

                      </option>

                      <option value="28">

                        +28 Days

                      </option>

                      <option value="29">

                        +29 Days

                      </option>

                      <option value="30">

                        +30 Days

                      </option>

                    </select>

                  </div>

                  <div class="field">

                    <label>

                      Charset

                    </label>

                    <select class="ui fluid dropdown" name="charset">

                      <option value="UTF-8">

                        UTF-8

                      </option>

                      <option value="US-ASCII">

                        US-ASCII

                      </option>

                      <option value="ISO-8859-1">

                        ISO-8859-1

                      </option>

                    </select>

                  </div>

                  <div class="field">

                    <label>

                      C.Type

                    </label>

                    <select class="ui fluid dropdown" name="contentType">

                      <option value="text/html">

                        text/html

                      </option>

                      <option value="text/plain">

                        text/plain

                      </option>

                      <option value="multipart/alternative">

                        multipart/alternative

                      </option>

                    </select>

                  </div>

                  <div class="field">

                    <label>

                      C.T Encoding

                    </label>

                    <select class="ui fluid dropdown" name="encoding">

                      <option value="7bit">

                        7bit

                      </option>

                      <option value="8bit">

                        8bit

                      </option>

                      <option value="base64">

                        Base64

                      </option>

                      <option value="binary">

                        Binary

                      </option>

                      <option value="quoted-printable">

                        Quoted-Printable

                      </option>

                    </select>

                  </div>

                </div>

                <div class="two fields">

                  <div class="field">

                    <label>

                      BCC Name

                    </label>

                    <input

                      type="text"

                      name="bccName"

                      value="[[AZ-{10}]]"

                    >

                  </div>

                  <div class="field">

                    <label>

                      BCC Mail

                    </label>

                    <input

                      type="text"

                      name="bccMail"

                    >

                  </div>

                </div>

              </div>

            </div>

            <div class="field">

              <div class="two fields">

                <div class="field">

                  <label>

                    From Name

                  </label>

                  <div class="ui left action input">

                    <select class="ui dropdown" name="fromNameEncoding">

                        <option value="">

                          Default

                        </option>

                        <option value="7bit">

                          7bit

                        </option>

                        <option value="base64" selected>

                          Base64

                        </option>

                        <option value="binary">

                          Binary

                        </option>

                        <option value="quoted-printable">

                          Quoted-Printable

                        </option>

                    </select>

                    <input

                      type="text"

                      name="fromName"

                    >

                  </div>

                </div>

                <div class="field">

                  <label>

                    Subject

                  </label>

                  <div class="ui left action input">

                    <select class="ui dropdown" name="subjectEncoding">

                      <option value="">

                        Default

                      </option>

                      <option value="7bit">

                        7bit

                      </option>

                      <option value="base64" selected>

                        Base64

                      </option>

                      <option value="binary">

                        Binary

                      </option>

                      <option value="quoted-printable">

                        Quoted-Printable

                      </option>

                    </select>

                    <input

                      type="text"

                      name="subject"

                    >

                  </div>

                </div>

              </div>
            
            </div>

            <div class="field">
              
              <div class="three fields">

                <div class="field">

                  <label>

                    From Email

                  </label>

                  <div class="ui labeled input">

                    <div class="ui basic label">

                      <div class="ui toggle checkbox">

                        <input

                          type="checkbox"

                          tabindex="0"

                          class="hidden"

                          name="emailAsLogin"

                        >

                        <label>

                          E.A.L

                        </label>

                      </div>

                    </div>

                    <input

                      type="text"

                      name="fromMail"

                      placeholder="from@[domain]"

                    >

                  </div>

                </div>

                <div class="field">

                  <label>

                    Reply-To

                  </label>

                  <div class="ui labeled input">

                    <div class="ui basic label">

                      <div class="ui toggle checkbox">

                        <input

                          type="checkbox"

                          tabindex="0"

                          class="hidden"

                          name="replyAsLogin"

                        >

                        <label>

                          R.A.L

                        </label>

                      </div>

                    </div>

                    <input

                      type="text"

                      name="replyTo"

                      placeholder="reply@[domain]"

                    >

                  </div>

                </div>

                <div class="field">

                  <label>

                    Return Path

                  </label>

                  <div class="ui labeled input">

                    <div class="ui basic label">

                      <div class="ui toggle checkbox">

                        <input

                          type="checkbox"

                          tabindex="0"

                          class="hidden"

                          name="confirmReading"

                        >

                        <label>

                          C.R

                        </label>

                      </div>

                    </div>

                    <input

                      type="text"

                      name="returnPath"

                      placeholder="return@[domain]"

                    >

                  </div>

                </div>

              </div>

            </div>

          </div>

          <h4 data-href="#Body" class="ui top attached block header">

            Message Section

          </h4>

          <div class="ui bottom attached segment">

            <div class="two fields">

              <div class="field">

                <label>

                  Creative

                </label>

                <textarea name="letter"></textarea>

              </div>

              <div class="field">

                <label>

                  Preview

                </label>

                <iframe class="preview"></iframe>

              </div>

            </div>            

            <div class="ui accordion field">
          
              <div class="title">
          
                <i class="icon dropdown"></i>
          
                Add Attachments
          
              </div>
          
              <div class="content field">

                <div class="fields">

                  <div class="three wide field">

                    <label>

                      Context Font

                    </label>

                    <input

                      type="text"

                      name="contextFont"

                      value="6px sans-serif"

                    >

                  </div>

                  <div class="three wide field">

                    <label>

                      Context Color

                    </label>

                    <input

                      type="text"

                      name="contextColor"

                      value="#FFF"

                    >

                  </div>

                  <div class="three wide field">

                    <label>

                      Context Position

                    </label>

                    <input

                      type="text"

                      name="contextPosition"

                      value="10"

                    >

                  </div>

                  <div class="seven wide field">

                    <label>

                      Attachments

                    </label>

                    <div class="ui action labeled input">

                      <div class="ui basic label">

                        <div class="ui toggle checkbox">

                          <input

                            type="checkbox"

                            tabindex="0"

                            class="hidden"

                            name="embedIMG"

                          >

                          <label>

                            Embedded Image

                          </label>

                        </div>

                      </div>

                      <input type="text" placeholder="Your File Name" readonly>

                      <input type="file" class="hiddenItem" name="attachments" multiple>

                      <div id="clear" class="ui basic label hiddenItem">

                        <i class="grey fitted trash alternate outline icon"></i>

                      </div>

                      <button id="uBtn" type="button" class="ui teal right labeled icon button">

                        <i class="cloud upload icon"></i>

                        Upload

                      </button>

                    </div>

                    <input type="hidden" name="embedType" value="">

                    <canvas class="hiddenItem" data-shape="86, 49, 80, 51, 82"></canvas>

                    <input type="hidden" name="sessionId" value="<?php echo $sessionId; ?>">

                  </div>

                </div>

              </div>
            
            </div>

            <div class="three fields">

              <div class="field">

                <label>

                  Link

                </label>

                <input

                  type="text"

                  name="link"

                  placeholder="https://example.com"

                >

              </div>
          
              <div class="field">

                <label>

                  Open Redirect

                </label>

                <select class="ui fluid dropdown" name="openRedirect">

                  <option value="">

                    No Redirect

                  </option>

                  <option value="Href">

                    Href

                  </option>

                </select>

              </div>

              <div class="field">

                <label>

                  Shortener

                </label>

                <select class="ui fluid dropdown" name="useShortener">

                  <option value="">

                    No Shortener

                  </option>

                  <option value="Shorturl.at">

                    Shorturl.at

                  </option>

                </select>

              </div>

            </div>

          </div>

          <h4 data-href="#Recipients" class="ui top attached block header">

            Recipients Setup

          </h4>

          <div class="ui bottom attached segment">

            <div class="two fields">

              <div class="field">

                <label>

                  Recipients

                </label>

                <small id="failures" class="hiddenItem" data-failures="">
                  
                  <i class="user times icon link"></i>

                  Copy Undelivered Emails

                </small>

                <textarea name="recipients"></textarea>

              </div>

              <div class="field">

                <table class="ui very basic table compact fixed four column single line status">

                </table>

              </div>

            </div>

          </div>

        </div>

      </form> <!-- End Form -->

      <!-- Start Footer -->
      <footer>

        <div>
          
          <small>

            &copy; V1P3RBOX 2018-<?php echo date('Y') ?>

            - Designed and Developed By 

            <a href="https://t.me/V1P3R_404/" target="_blank">V1P3Rツ</a>

            - All Right Reserved. 

          </small>

        </div>

        <div>
          
          <small>
            
            Version <?php echo $version; ?> Had Sa3a :)

          </small>

        </div>

      </footer> <!-- End Footer -->

    </section> <!-- End Section -->

  <?php endif; ?>

  <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/semantic-ui@2.5.0/dist/semantic.min.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/d3-collection@1.0.7/dist/d3-collection.min.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/d3-dispatch@3.0.1/dist/d3-dispatch.min.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/d3-request@1.0.6/build/d3-request.min.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/d3-queue@3.0.7/build/d3-queue.min.js"></script>

  <script src="./public/javascripts/script.js"></script>

</body>

</html>