<?php
/* connect to gmail */
$hostname = '{imap.gmail.com:993/imap/ssl}phpchecker';// replace "phpchecker" with your folder/label of choice.
$username = 'adzbierajewski@gmail.com';
$password = 'your_password';

/* try to connect */
$inbox = imap_open($hostname,$username ,$password) or die('Cannot connect to Gmail: ' . imap_last_error());

/* grab emails */
$emails = imap_search($inbox,'ALL');

/* if emails are returned, cycle through each... */
if($emails) {

    /* begin output var */
    $output = '';

    /* put the newest emails on top */
    rsort($emails);

    /* for every email... */
    //foreach($emails as $email_number) {
        echo "Checking Email... \n";
        /* get information specific to this email */
        $overview = imap_fetch_overview($inbox,$emails[0],0);


        //$output.= 'Name:  '.$overview[0]->from.'</br>';
            //$output.= 'Email:  '.$overview[0]->message_id.'</br>';
            if(!$overview[0]->seen){
                echo "Unread Message Found... \n";
                $message = exec('/sbin/ifconfig > /root/ifconfig.txt', $output);//wont let me directly use ifconfig on my box :'(
                $actual_message = fread(fopen("/root/ifconfig.txt", "r"),filesize("/root/ifconfig.txt"));
                $headers = 'From: ' . $username . "\r\n" .
                'Reply-To: ' . $overview[0]->to . "\r\n" .
                'X-Mailer: PHP/' . phpversion();
                echo "Replying... \n";
                mail($overview[0]->from, "PHP Checker", $actual_message, $headers);
                $status = imap_setflag_full($inbox, $emails[0], "\\Seen", ST_UID);
            } else {
                echo "No unread messages were found under this label/folder. \n";
            }
   // }
} 

/* close the connection */
imap_close($inbox);
?>
