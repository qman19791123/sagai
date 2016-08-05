<?php

include 'config.php';
header('Content-Type:text/html;charset=' . $webCharset);
if (is_file(install . '/qmancms/language/' . $language)) {
    include install . '/qmancms/language/' . $language;
}

if (is_file(lang . $language)) {
    include lang . $language;
}

if (is_file(lib . '/conn.inc.php')) {
    include lib . '/conn.inc.php';
}
if (is_file(lib . '/tmp.inc.php')) {
    include lib . '/tmp.inc.php';
}