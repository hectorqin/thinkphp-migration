<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: yunwuxin <448901948@qq.com>
// +----------------------------------------------------------------------
namespace think\migration;

use InvalidArgumentException;
use Phinx\Db\Adapter\AdapterFactory;
use think\Db;
use think\facade\Config;

abstract class Command extends \think\console\Command
{
    public function getAdapter()
    {
        if (isset($this->adapter)) {
            return $this->adapter;
        }

        $options = $this->getDbConfig();

        $adapter = AdapterFactory::instance()->getAdapter($options['adapter'], $options);

        if ($adapter->hasOption('table_prefix') || $adapter->hasOption('table_suffix')) {
            $adapter = AdapterFactory::instance()->getWrapper('prefix', $adapter);
        }

        $adapter->setInput(new Input($this->input));
        $adapter->setOutput(new Output($this->output));

        $this->adapter = $adapter;

        return $adapter;
    }

    /**
     * 获取数据库配置
     * @return array
     */
    protected function getDbConfig()
    {
        static $dbConfig = null;
        if (!is_null($dbConfig)) {
            return $dbConfig;
        }
        $migrationConfig = Config::pull('migration');
        $migrationConfig = is_array($migrationConfig) ? $migrationConfig : [
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
        $envConfig = isset($migrationConfig['environments']) ? $migrationConfig['environments'] : [];

        // 判断环境变量
        if ($this->input->hasOption('environment')) {
            $defaultDatabase = $this->input->getOption('environment');
        } else {
            $defaultDatabase = isset($envConfig['default_database']) ? $envConfig['default_database'] : 'default';
        }

        if ($defaultDatabase == 'default') {
            // 解析默认数据库
            $config = Db::connect()->getConfig();

            if (0 == $config['deploy']) {
                $dbConfig = [
                    'adapter'       => $config['type'],
                    'host'          => $config['hostname'],
                    'name'          => $config['database'],
                    'user'          => $config['username'],
                    'pass'          => $config['password'],
                    'port'          => $config['hostport'],
                    'charset'       => $config['charset'],
                    'table_prefix'  => $config['prefix'],
                ];
            } else {
                $dbConfig = [
                    'adapter'       => explode(',', $config['type'])[0],
                    'host'          => explode(',', $config['hostname'])[0],
                    'name'          => explode(',', $config['database'])[0],
                    'user'          => explode(',', $config['username'])[0],
                    'pass'          => explode(',', $config['password'])[0],
                    'port'          => explode(',', $config['hostport'])[0],
                    'charset'       => explode(',', $config['charset'])[0],
                    'table_prefix'  => explode(',', $config['prefix'])[0],
                ];
            }
            $dbConfig = array_merge($envConfig, $dbConfig);
        } else if (!isset($envConfig[$envConfig['default_database']])) {
            throw new \RuntimeException(
                'Could not find the database config: [ ' . $defaultDatabase . ' ]'
            );
        } else {
            $dbConfig = array_merge($envConfig, (array)$envConfig[$envConfig['default_database']]);
        }

        $dbConfig = array_merge($migrationConfig, $dbConfig);

        if (!isset($dbConfig['default_migration_table'])) {
            $dbConfig['default_migration_table'] = (isset($dbConfig['table_prefix']) && $dbConfig['table_prefix'] ? $dbConfig['table_prefix'] : '') . 'migrations';
        }
        return $dbConfig;
    }

    protected function verifyMigrationDirectory($path)
    {
        if (!is_dir($path)) {
            throw new InvalidArgumentException(sprintf('Migration directory "%s" does not exist', $path));
        }

        if (!is_writable($path)) {
            throw new InvalidArgumentException(sprintf('Migration directory "%s" is not writable', $path));
        }
    }
}
