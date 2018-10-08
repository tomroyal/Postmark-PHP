<?php

// send test email

// require in from composer
require('./vendor/autoload.php');
// use pma function
include('./sendviapostmark.php');

$emailtext = '<h2>This Is A Test</h2><p>This is a test email via PMA with a <a href="http://www.google.com">link</a> in it.</p>';
$subject = 'Test emails are the best emails!';

$thisemail = array(
  'template' => 8524780,
  'main_content' => $emailtext,
  'subject' => $subject,
  'sender_email' => 'to@domain.com',
  'sender_name' => 'A Sender',
  'recip_name' => 'A Recipient',
  'recip_email' => 'from@domain.com'
);
$testsend = sendEmail($thisemail);

print_r($testsend);

?>
