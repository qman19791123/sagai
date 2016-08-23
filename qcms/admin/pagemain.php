<?php 
include '../config.php';
include lib.'tfunction.inc.php';
include 'isadmin.php';
$tfunction  = new tfunction();
$conn = $tfunction->conn;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $systemName?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo '../'.$tfunction->lessc('admin.less')?>">
    <link rel="stylesheet" type="text/css" href="../css/Font-Awesome/font-awesome.min.css">
</head>
<body>
    <div class="adminContent">
    <dl>
        <dt>后台管理</dt>
        <dd>
            
        </dd>
    </dl>

    </div>
</body>
</html>
