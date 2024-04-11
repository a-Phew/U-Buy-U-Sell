<?php

// when logout button is pressed destroy session and redirect to index page

session_start();
session_destroy();
header('location:../index.html');

?>