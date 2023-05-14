<?php

session_start();

setcookie('token');

header('Location: index.html');
exit();