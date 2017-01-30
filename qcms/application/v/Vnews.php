<xml>
    <?php
    $newsCout = $this->content();
    if (!empty($newsCout)):
        ?>
        <?php print $this->arrayToXml($newsCout); ?>
    <?php endif; ?>
</xml>
