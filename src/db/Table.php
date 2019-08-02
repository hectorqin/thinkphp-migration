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

namespace think\migration\db;

use Phinx\Db\Table\Index;

class Table extends \Phinx\Db\Table
{
    /**
     * 设置id
     * @param $id
     * @return static
     */
    public function setId($id)
    {
        $this->options['id'] = $id;
        return $this;
    }

    /**
     * 设置主键
     * @param $key
     * @return static
     */
    public function setPrimaryKey($key)
    {
        $this->options['primary_key'] = $key;
        return $this;
    }

    /**
     * 设置引擎
     * @param $engine
     * @return static
     */
    public function setEngine($engine)
    {
        $this->options['engine'] = $engine;
        return $this;
    }

    /**
     * 设置表注释
     * @param $comment
     * @return static
     */
    public function setComment($comment)
    {
        $this->options['comment'] = $comment;
        return $this;
    }

    /**
     * 设置排序比对方法
     * @param $collation
     * @return static
     */
    public function setCollation($collation)
    {
        $this->options['collation'] = $collation;
        return $this;
    }

    /**
     * 设置软删除字段
     *
     * @param string $name
     * @return static
     */
    public function addSoftDelete($name = 'delete_time')
    {
        $this->addColumn(Column::timestamp($name)->setNullable());
        return $this;
    }

    /**
     * 设置多态字段
     *
     * @param string $name
     * @param string $indexName
     * @return static
     */
    public function addMorphs($name, $indexName = null)
    {
        $this->addColumn(Column::unsignedInteger("{$name}_id"));
        $this->addColumn(Column::string("{$name}_type"));
        $this->addIndex(["{$name}_id", "{$name}_type"], ['name' => $indexName]);
        return $this;
    }

    /**
     * 设置可空多态字段
     *
     * @param string $name
     * @param string $indexName
     * @return static
     */
    public function addNullableMorphs($name, $indexName = null)
    {
        $this->addColumn(Column::unsignedInteger("{$name}_id")->setNullable());
        $this->addColumn(Column::string("{$name}_type")->setNullable());
        $this->addIndex(["{$name}_id", "{$name}_type"], ['name' => $indexName]);
        return $this;
    }

    /**
     * @param string $createdAtColumnName
     * @param string $updatedAtColumnName
     * @param bool   $withTimezone Whether to set the timezone option on the added columns
     * @return \Phinx\Db\Table|Table
     */
    public function addTimestamps($createdAtColumnName = 'create_time', $updatedAtColumnName = 'update_time', $withTimezone = false)
    {
        return parent::addTimestamps($createdAtColumnName, $updatedAtColumnName, $withTimezone = false);
    }

    /**
     * @param \Phinx\Db\Table\Column|string $columnName
     * @param null                          $type
     * @param array                         $options
     * @return \Phinx\Db\Table|Table
     */
    public function addColumn($columnName, $type = null, $options = [])
    {
        if ($columnName instanceof Column && $columnName->getUnique()) {
            $index = new Index();
            $index->setColumns([$columnName->getName()]);
            $index->setType(Index::UNIQUE);
            $this->addIndex($index);
        }
        return parent::addColumn($columnName, $type, $options);
    }

    /**
     * @param string $columnName
     * @param null   $newColumnType
     * @param array  $options
     * @return \Phinx\Db\Table|Table
     */
    public function changeColumn($columnName, $newColumnType = null, array $options = [])
    {
        if ($columnName instanceof \Phinx\Db\Table\Column) {
            return parent::changeColumn($columnName->getName(), $columnName, $options);
        }
        return parent::changeColumn($columnName, $newColumnType, $options);
    }
}
