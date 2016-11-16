<?php $indexCout = $this->content(); ?>
<xml>
    <title>
        <![CDATA[<?php echo $indexCout['title']; ?>]]>
    </title>

    <styleContent>
        <![CDATA[<?php echo $indexCout['content']; ?>]]>
    </styleContent>

    <classify>
        <?php
        $MaxMenu = ($this->content->classify->MaxMenu());
        foreach ($MaxMenu as $MaxMenuLink):
            ?>
            <content>
                <?php foreach ($MaxMenuLink as $k => $v): ?>
                    <?php printf("<%s><![CDATA[%s]]></%s>\n", $k, $v, $k) ?>
                <?php endforeach; ?>
            </content>
        <?php endforeach; ?>
    </classify>

    <news>
        <newList-Xinwenzhongxin>
            <?php
            $newListXinwenZhongXin = ($this->content->news->newList(3));
            foreach ($newListXinwenZhongXin as $newListXinwenZhongXinContent):
                ?> 
                <content>
                    <?php foreach ($newListXinwenZhongXinContent as $k => $v): ?>
                        <?php printf("<%s><![CDATA[%s]]></%s>\n", $k, $v, $k) ?>
                    <?php endforeach; ?>
                </content>
            <?php endforeach; ?>
        </newList-Xinwenzhongxin>
    </news>
</xml>  



