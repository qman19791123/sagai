<xml>
    <?php
    $activityCout = $this->content();
    if (!empty($activityCout)):
        ?>
        <title><![CDATA[ <?php empty($activityCout['title']) || print $activityCout['title']; ?>]]></title>
        <styleContent><![CDATA[ <?php empty($activityCout['content']) || print $activityCout['content']; ?>]]></styleContent>
        <?php if (!empty($activityCout['data'])): ?>
            <data>
                <title><?php echo $activityCout['data'][0]['activityTitle'] ?></title>
                <?php foreach ($activityCout['data'] as $activityCoutData): ?>
                    <content>
                        <?php foreach ($activityCoutData as $k => $v): ?>
                            <?php printf("<%s><![CDATA[%s]]></%s>\n", $k, $v, $k) ?>
                        <?php endforeach; ?>



                        <?php if ($activityCoutData['activityInput'] == 'radio' || $activityCoutData['activityInput'] == 'checkbox' || $activityCoutData['activityInput'] == 'select'): ?>
                            <activitystateList>
                                <?php
                                $i = 0;
                                $team = mb_split(',', $activityCoutData['activitystate']);
                                foreach ($team as $v):
                                    if (!empty($v)):
                                        $i++;
                                        ?>
                                        <team id="<?php echo $i ?>"><![CDATA[<?php echo $v ?>]]></team>
                                    <?php endif; ?>
                                <?php endforeach ?>
                            </activitystateList>
                        <?php endif; ?>


                    </content>
                <?php endforeach; ?>
            </data>
        <?php endif; ?>
    <?php endif; ?>
</xml>