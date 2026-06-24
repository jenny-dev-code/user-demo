<?php

require '../includes/mail.php';

$result = sendResetMail(
    'johnaudrey62@gmail.com',
    'https://google.com'
);

var_dump($result);