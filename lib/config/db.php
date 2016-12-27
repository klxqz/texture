<?php

return array(
    'shop_texture' => array(
        'feature_id' => array('int', 11, 'null' => 0),
        'value_id' => array('int', 11, 'null' => 0),
        'img' => array('varchar', 255, 'null' => 0, 'default' => ''),
        ':keys' => array(
            'feature_id' => array('feature_id', 'value_id'),
        ),
    ),
);
