<?php
if (empty($_SESSION['admin'])) {
    die('<script>top.location.href="index.php"</script>');
}
?>