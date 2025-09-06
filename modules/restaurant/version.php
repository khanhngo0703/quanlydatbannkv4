<?php

// if (!defined('NV_SYSTEM')) die('Stop!!!');

$module_version = [
    'name' => 'Restaurant',
    'modfuncs' => 'main,menu,table,reservation,order, login, register',
    'change_alias' => 'main',
    'submenu' => 'main,menu,table,reservation,order, login, register',
    'is_sysmod' => 0,
    'virtual' => 0,
    'version' => '4.5.06',
    'date' => 'Mon, 26 Aug 2025 00:00:00 GMT',
    'author' => 'Khanh Ngo',
    'uploads_dir' => [$module_file],
    'files_dir' => [],
    'is_delete' => 1,
    'note' => 'Module quản lý bàn ăn và đặt món ăn',
    'allow_func' => ['main,menu,table,reservation,order,login,register']
];
