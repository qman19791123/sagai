<?php
include '../config.php';
include lib . 'tfunction.inc.php';
include 'isadmin.php';
include lang . $language;
$tfunction = new tfunction();
?>
<html>
    <head>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $systemName ?></title>
        <link rel="stylesheet" type="text/css" href="<?php echo '../' . $tfunction->lessc('admin.less') ?>"/>
        <link rel="stylesheet" type="text/css" href="../css/Font-Awesome/font-awesome.min.css"/>
        <script type="text/javascript" src="../js/jquery.min.js"></script>

    </head>

    <body>
        <div class="adminContent"></div>
    </body>
</html>