<?php

class shopTexturePluginSettingsDeleteImageController extends waJsonController {

    public function execute() {
        try {
            $feature_id = waRequest::post('feature_id');
            $value_id = waRequest::post('value_id');
            if (!$feature_id || !$value_id) {
                throw new Exception('Ошибка передачи данных');
            }
            $key = array(
                'feature_id' => $feature_id,
                'value_id' => $value_id,
            );
            $texture_model = new shopTexturePluginModel();
            if (!($texture = $texture_model->getByField($key))) {
                throw new Exception('Не удалось найти требуемую запись');
            }
            $image_path = wa()->getDataPath('plugins/texture/', true, 'shop');
            @unlink($image_path . $texture['img']);
            @unlink($image_path . 'min_' . $texture['img']);
            $texture_model->deleteByField($key);
        } catch (Exception $ex) {
            $this->setError($ex->getMessage());
        }
    }

}
