<?php
if(empty($_SESSION['admin']))
{
    Header("Location: index.php");
    exit;
}
?>