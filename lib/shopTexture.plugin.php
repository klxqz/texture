<?php

class shopTexturePlugin extends shopPlugin {

    public static $plugin_id = array('shop', 'texture');

    public static function display($product) {
        $app_settings_model = new waAppSettingsModel();
        $settings = $app_settings_model->get(self::$plugin_id);
        if (!$settings['status']) {
            return false;
        }
        $view = wa()->getView();
        $settings['feature'] = json_decode($settings['feature'], true);
        $view->assign('settings', $settings);

        if (!empty($product['features_selectable'])) {
            $texture_features_selectable = $product['features_selectable'];
            foreach ($settings['feature'] as $key => $value) {
                if ($value['status'] == 0) {
                    unset($texture_features_selectable[$key]);
                }
            }
            $view->assign('texture_features_selectable', $texture_features_selectable);
        }

        $image_url = wa()->getDataUrl('plugins/texture/images/', true, 'shop');
        $view->assign('image_url', $image_url);
        $view->assign('default_sku_features', $product['sku_features']);
        $view->assign('fancybox', $settings['fancybox']);

        $template_path = wa()->getAppPath('plugins/texture/templates/FrontendTexture.html', 'shop');
        $html = $view->fetch($template_path);

        return $html;
    }

    public function frontendProduct($product) {
        $app_settings_model = new waAppSettingsModel();

        if ($app_settings_model->get(self::$plugin_id, 'default_output')) {
            return array('cart' => self::display($product));
        }
    }

}
