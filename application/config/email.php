<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['email_sender'] = 'sender@email.tld'; // default sender use in programs
$config['email_sender_name'] = ''; // default sender name
/*------------------------------------------------------*/
$config['protocol'] = 'smtp'; // mail, sendmail, or smtp
$config['smtp_host'] = 'mail.email.tld';
$config['smtp_user'] = 'sender@email.tld';// for smtp auth->send.
$config['smtp_pass'] = 'pass';
$config['smtp_port'] = '25';
$config['smtp_timeout'] = '5';
$config['wordwrap'] = false; // Enable word-wrap.
$config['wrapchars'] = '600'; // Character count to wrap at.
$config['mailtype'] = 'html'; // Type of mail. If you send HTML email you must send it as a complete web page. Make sure you don't have any relative links or relative image paths otherwise they will not work.
$config['charset'] = 'utf-8'; // Character set (utf-8, iso-8859-1, etc.).
$config['validate'] = false; // Whether to validate the email address.
$config['priority'] = '3'; // Email Priority. 1 = highest. 5 = lowest. 3 = normal.
$config['crlf'] = '\r\n'; // Newline character. (Use "\r\n" to comply with RFC 822).
$config['newline'] = '\r\n'; // Newline character. (Use "\r\n" to comply with RFC 822).

/* end of file */