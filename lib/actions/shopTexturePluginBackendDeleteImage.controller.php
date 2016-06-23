<?php

class shopTexturePluginBackendDeleteImageController extends waJsonController {

    public function execute() {
        $feature_id = waRequest::post('feature_id');
        $feature_value = waRequest::post('feature_value');

        $app_settings_model = new waAppSettingsModel();
        $settings = $app_settings_model->get(shopTexturePlugin::$plugin_id, 'feature');

        $feature = json_decode($settings, true);
        $image_path = wa()->getDataPath('plugins/texture/images/', true, 'shop');
        $name = $feature[$feature_id]['img'][$feature_value];

        if ($name && file_exists($image_path . $name)) {
            if (@!unlink($image_path . $name)) {
                $this->response['message'] = 'Ошибка удаления ' . $image_path . $name;
            } else {
                $this->response['message'] = 'Изображение удалено';
            }
        }
        $this->response['feature_id'] = $feature_id;
        $this->response['feature_value'] = $feature_value;

        unset($feature[$feature_id]['img'][$feature_value]);

        $app_settings_model->set(shopTexturePlugin::$plugin_id, 'feature', json_encode($feature));
    }

}
