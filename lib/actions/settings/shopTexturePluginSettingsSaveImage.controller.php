<?php

class shopTexturePluginSettingsSaveImageController extends waJsonController {

    public function execute() {
        try {
            $files = waRequest::file('img');
            $shop_texture = waRequest::post('shop_texture');
            if (empty($shop_texture['feature_id'])) {
                throw new Exception('Ошибка передачи feature_id');
            }
            $feature_id = $shop_texture['feature_id'];
            if (!$files->uploaded()) {
                throw new Exception('Изображения не были загружены');
            }
            $response = array();
            $image_path = wa()->getDataPath('plugins/texture/', true, 'shop');
            foreach ($files as $value_id => $file) {
                $path_info = pathinfo($file->name);
                $name = sprintf('%d_%d.%s', $feature_id, $value_id, $path_info['extension']);
                $min_name = 'min_' . $name;
                $file->waImage()->save($image_path . $name);
                $file->waImage()->resize(150, 150)->save($image_path . $min_name);
                $texture_model = new shopTexturePluginModel();
                $key = array('feature_id' => $feature_id, 'value_id' => $value_id);
                if ($texture_model->getByField($key)) {
                    $texture_model->updateByField($key, array('img' => $name));
                } else {
                    $texture_model->insert(array_merge($key, array('img' => $name)));
                }
                $response[$value_id] = array(
                    'image_url' => wa()->getDataUrl('plugins/texture/' . $min_name, true, 'shop')
                );
            }
            $this->response = $response;
        } catch (Exception $ex) {
            $this->setError($e->getMessage());
        }
    }

}
