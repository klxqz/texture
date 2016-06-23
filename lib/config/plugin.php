<?php

return array(
    'name' => 'Текстура к характеристикам',
    'description' => 'Плагин позволяет добавить текструры к характеристикам',
    'vendor' => '985310',
    'version' => '1.0.1',
    'img' => 'img/texture.png',
    'shop_settings' => true,
    'frontend' => true,
    'handlers' => array(
        'frontend_product' => 'frontendProduct',
    ),
);
//EOF
