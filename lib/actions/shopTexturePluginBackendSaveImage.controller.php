<?php

class shopTexturePluginBackendSaveImageController extends waJsonController {

    public function execute() {
        $file = waRequest::file('texture');

        if ($file->uploaded()) {

            $image_path = wa()->getDataPath('plugins/texture/images/', 'shop');
            $image_url = wa()->getDataUrl('plugins/texture/images/', 'shop');
            $path_info = pathinfo($file->name);
            $name = $this->uniqueName($image_path, $path_info['extension']);

            $app_settings_model = new waAppSettingsModel();
            
            $size = 600;
            $resize = 1;
            try {
                if ($resize) {
                    $file->waImage()->resize($size, $size)->save($image_path . $name);
                } else {
                    $file->waImage()->save($image_path . $name);
                }

                $this->response['preview'] = $name;
                $this->response['image_url'] = $image_url;
            } catch (Exception $e) {

                $this->setError($e->getMessage());
            }
        }
    }

    protected function uniqueName($path, $extension) {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        do {
            $name = '';
            for ($i = 0; $i < 10; $i++) {
                $n = rand(0, strlen($alphabet) - 1);
                $name .= $alphabet{$n};
            }
            $name .= '.' . $extension;
        } while (file_exists($path . $name));

        return $name;
    }

}
