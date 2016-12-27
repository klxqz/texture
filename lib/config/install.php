<?php
$plugin_id = array('shop', 'texture');
$app_settings_model = new waAppSettingsModel();
$app_settings_model->set($plugin_id, 'status', '1');
$app_settings_model->set($plugin_id, 'default_output', '1');
$app_settings_model->set($plugin_id, 'fancybox', '1');
