<xml>
    <?php
    $indexCout = $this->content();
    if (!empty($indexCout)):
        ?>
        <?php print $this->arrayToXml($indexCout); ?>
    <?php endif; ?>
</xml>