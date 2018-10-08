<?php

// generic postmark sender

$pmkey = getenv('POSTMARK_KEY'); // or from somewhere else

use Postmark\PostmarkClient;
$pmclient = new PostmarkClient($pmkey);

function sendEmail($emaildata){
  global $pmclient;

  // pm specific address formatting
  $sender_formatted = $emaildata['sender_name'].' <'.$emaildata['sender_email'].'>';
  $recip_formatted = $emaildata['recip_name'].' <'.$emaildata['recip_email'].'>';

  // attempt html2text conversion of message to plaintext
  try {
    // convert it via Html2Text
    $plaintext = Html2Text\Html2Text::convert($emaildata['main_content']);
  } catch (\Exception $e) {
    // if all else fails, very rough conversion
    $plaintext = strip_tags(str_replace("\n", "<p>", $emaildata['main_content']));
  };

  // build message object
  $themessage = [
     'To' => $recip_formatted,
	   'From' => $sender_formatted,
     'TemplateId' => $emaildata['template'],
     'TemplateModel' => [
       'Subject' => $emaildata['subject'],
       'main_content' => $emaildata['main_content'],
       'plaintext_content' => $plaintext
     ]
  ];

  try {
    // send via PMA
    $sendresult = $pmclient->sendEmailBatch([$themessage]);

    if ($sendresult[0]['ErrorCode'] === 0){
      // send OK
      $ret_code = '200'; // 200 ok
      $ret_msg = $sendresult[0]['MessageID']; // return PMA id of email
    }
    else {
      // handled error
      $ret_code = $sendresult[0]['ErrorCode'];
      $ret_msg = $sendresult[0]['Message'];
    }

  } catch (\Exception $e) {
    // unhandled error
    $ret_code =  $e->httpStatusCode;
    $ret_msg = $e->message;
  };

  // return that data
  return array('code' => $ret_code, 'msg' => $ret_msg);

};


?>
