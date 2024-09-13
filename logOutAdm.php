<?php
session_start();
unset($_SESSION['userAdm']);
unset($_SESSION['senhaAdm']);
header('Location: loginAdm.php');
exit();