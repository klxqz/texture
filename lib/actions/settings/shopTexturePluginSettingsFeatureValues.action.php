<?php

class shopTexturePluginSettingsFeatureValuesAction extends waViewAction {

    public function execute() {
        $feature_id = waRequest::get('feature_id');
        $settings = wa()->getPlugin('texture')->getSettings();
        $settings['feature_id'] = $feature_id;
        wa()->getPlugin('texture')->saveSettings($settings);
        $feature_model = new shopFeatureModel();
        $feature = $feature_model->getById($feature_id);
        $texture_model = new shopTexturePluginModel();
        $_textures = $texture_model->getByField('feature_id', $feature_id, true);
        $textures = array();
        foreach ($_textures as $_texture) {
            $_texture['min_img'] = 'min_' . $_texture['img'];
            $textures[$_texture['value_id']] = $_texture;
        }
        $this->view->assign(array(
            'feature' => $feature,
            'values' => shopFeatureModel::getFeatureValues($feature),
            'image_url' => wa()->getDataUrl('plugins/texture/', true, 'shop'),
            'textures' => $textures,
        ));
    }

}
