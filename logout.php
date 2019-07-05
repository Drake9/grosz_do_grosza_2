<?php

session_start();
unset($_SESSION['logged_id']);
unset($_SESSION['bad_attempt']);

header('Location: index.php');
