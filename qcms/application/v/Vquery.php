<xml>
    <?php
    $queryCout = $this->content('query');
    if (!empty($queryCout)):
        ?>
        <?php print $this->arrayToXml($queryCout); ?>
    <?php endif; ?>
</xml>
