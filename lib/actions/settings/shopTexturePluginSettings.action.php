<?php

class shopTexturePluginSettingsAction extends waViewAction {

    public function execute() {
        echo time();
        $feature_model = new shopFeatureModel();
        $features = $feature_model->getFeatures(true, null, 'id', true);
        $this->view->assign(array(
            'plugin' => wa()->getPlugin('texture'),
            'features' => $features,
            'templates' => shopTextureHelper::getTemplates()
        ));
    }

}
