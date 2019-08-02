<?php

return [
    'paths'        => [
        'migrations' => 'database' . DIRECTORY_SEPARATOR . 'migrations',
        'seeds'      => 'database' . DIRECTORY_SEPARATOR . 'seeds',
    ],
    'environments' => [
        'default_migration_table' => 'migrations', // 不会自动添加前缀
        'default_database'        => 'default', // default 采用 tp 默认数据库配置
        // extra environment
    ],
    'version_order' => 'creation', // creation or execution
];
