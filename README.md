# thinkphp-migration

thinkphp5.1 数据库迁移工具

## 框架要求

ThinkPHP5.1+

## 安装

~~~ bash
composer require hectorqin/thinkphp-migration
~~~

## 配置

修改项目根目录下config/migration.php中对应的参数

## 使用

~~~ bash
# 帮助
 migrate
  migrate:breakpoint  Manage breakpoints
  migrate:create      Create a new migration
  migrate:rollback    Rollback the last or to a specific migration
  migrate:run         Migrate the database
  migrate:status      Show migration status

 seed
  seed:create         Create a new database seeder
  seed:run            Run database seeders
~~~

## License

Apache-2.0
