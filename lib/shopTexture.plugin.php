<?php

class shopTexturePlugin extends shopPlugin {

    public static $templates = array(
        'texture' => array(
            'name' => 'Главный шаблон',
            'tpl_path' => 'plugins/texture/templates/',
            'tpl_name' => 'FrontendTexture',
            'tpl_ext' => 'html',
            'public' => false,
        ),
        'texture_css' => array(
            'name' => 'texture.css',
            'tpl_path' => 'plugins/texture/css/',
            'tpl_name' => 'texture',
            'tpl_ext' => 'css',
            'public' => true
        ),
        'texture_js' => array(
            'name' => 'texture.js',
            'tpl_path' => 'plugins/texture/js/',
            'tpl_name' => 'texture',
            'tpl_ext' => 'js',
            'public' => true
        ),
    );

    public function saveSettings($settings = array()) {
        parent::saveSettings($settings);

        if ($templates = waRequest::post('templates')) {
            foreach ($templates as $template_id => $template) {
                $s_template = self::$templates[$template_id];
                if (!empty($template['reset_tpl']) || waRequest::post('reset_tpl_all')) {
                    $tpl_full_path = $s_template['tpl_path'] . $s_template['tpl_name'] . '.' . $s_template['tpl_ext'];
                    $template_path = wa()->getDataPath($tpl_full_path, $s_template['public'], 'shop', true);
                    @unlink($template_path);
                } else {
                    $tpl_full_path = $s_template['tpl_path'] . $s_template['tpl_name'] . '.' . $s_template['tpl_ext'];
                    $template_path = wa()->getDataPath($tpl_full_path, $s_template['public'], 'shop', true);
                    if (!file_exists($template_path)) {
                        $tpl_full_path = $s_template['tpl_path'] . $s_template['tpl_name'] . '.' . $s_template['tpl_ext'];
                        $template_path = wa()->getAppPath($tpl_full_path, 'shop');
                    }
                    $content = file_get_contents($template_path);
                    if (!empty($template['template']) && strcmp(str_replace("\r", "", $template['template']), str_replace("\r", "", $content)) != 0) {
                        $tpl_full_path = $s_template['tpl_path'] . $s_template['tpl_name'] . '.' . $s_template['tpl_ext'];
                        $template_path = wa()->getDataPath($tpl_full_path, $s_template['public'], 'shop', true);
                        $f = fopen($template_path, 'w');
                        if (!$f) {
                            throw new waException('Не удаётся сохранить шаблон. Проверьте права на запись ' . $template_path);
                        }
                        fwrite($f, $template['template']);
                        fclose($f);
                    }
                }
            }
        }
    }

    public static function display($product) {
        $plugin = wa()->getPlugin('texture');
        if (!$plugin->getSettings('status')) {
            return false;
        }
        if (!$product['features_selectable']) {
            return false;
        }

        $texture_model = new shopTexturePluginModel();
        $textures = array();

        foreach ($product['features_selectable'] as $feature_id => $feature) {
            $feature_texrures = $texture_model->getByField(array('feature_id' => $feature_id, 'value_id' => array_keys($feature['values'])), true);
            if ($feature_texrures) {
                foreach ($feature_texrures as $feature_texrure) {
                    $textures[$feature_id][$feature_texrure['value_id']] = $feature_texrure;
                }
            }
        }

        if (!$textures) {
            return false;
        }

        $view = wa()->getView();
        $view->assign(array(
            'textures' => $textures,
            'image_url' => wa()->getDataUrl('plugins/texture/', true, 'shop'),
            'fancybox' => $plugin->getSettings('fancybox'),
            'texture_css_url' => shopTextureHelper::getTemplateUrl('texture_css'),
            'texture_js_url' => shopTextureHelper::getTemplateUrl('texture_js')
        ));

        $texture_template = shopTextureHelper::getTemplates('texture', false);
        $html = $view->fetch($texture_template['template_path']);

        return $html;
    }

    public function frontendProduct($product) {
        if (!$this->getSettings('default_output')) {
            return false;
        }
        return array($this->getSettings('default_output') => self::display($product));
    }

}
