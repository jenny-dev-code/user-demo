<?php

require '../includes/mail.php';

$result = sendResetMail(
    'amitjadav5912@gmail.com',
    'https://google.com'
);

var_dump($result);