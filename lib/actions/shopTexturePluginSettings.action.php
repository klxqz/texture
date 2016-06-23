<?php

class shopTexturePluginSettingsAction extends waViewAction {

    public function execute() {
        $app_settings_model = new waAppSettingsModel();
        $feature_model = new shopFeatureModel();
        $features = $feature_model->getFeatures(true, null, 'id', true);
        $settings = $app_settings_model->get(shopTexturePlugin::$plugin_id);
        $settings['feature'] = json_decode($settings['feature'], true);
        $image_url = wa()->getDataUrl('plugins/texture/images/', true, 'shop');
        $this->view->assign('image_url', $image_url);
        $this->view->assign('settings', $settings);
        $this->view->assign('features', $features);
    }

}
