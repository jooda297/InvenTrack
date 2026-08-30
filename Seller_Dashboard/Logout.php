<?php
session_start();

include "../Connect.php";

unset($_SESSION['S_Log']);

echo "<script language='JavaScript'>
			alert ('You Logout Successfully !');
      </script>";

echo '<script language="JavaScript">
        document.location="../Login.php";
    </script>';
