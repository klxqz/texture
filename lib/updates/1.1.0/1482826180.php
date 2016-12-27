<?php

$model = new waModel();

try {
    $sql = 'SELECT * FROM `shop_texture` WHERE 0';
    $model->query($sql);
} catch (waDbException $ex) {
    $sql = "CREATE TABLE IF NOT EXISTS `shop_texture` (
                `feature_id` int(11) NOT NULL,
                `value_id` int(11) NOT NULL,
                `img` varchar(255) NOT NULL,
                KEY `feature_id` (`feature_id`,`value_id`)
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
    $model->query($sql);
}

try {
    $files = array(
        'plugins/texture/lib/actions/shopTexturePluginSettings.action.php',
        'plugins/texture/lib/actions/shopTexturePluginBackendSaveImage.controller.php',
        'plugins/texture/lib/actions/shopTexturePluginBackendDeleteImage.controller.php',
        'plugins/texture/js/script.js',
    );

    foreach ($files as $file) {
        waFiles::delete(wa()->getAppPath($file, 'shop'), true);
    }
} catch (Exception $e) {
    
}