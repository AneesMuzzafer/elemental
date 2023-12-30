<?php

namespace Core\Model;

use Core\Database\Database;
use Core\Main\App;
use PDOException;
use ReflectionClass;

class Model
{

    protected string $tableName;
    protected $data;

    public function __construct(private Database $db)
    {
        $this->tableName = $this->getTableNameFromClass();
    }

    public function getTableNameFromClass()
    {
        $reflect = new ReflectionClass($this);
        $className = $reflect->getShortName();

        $snakeCase = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $className));
        $pluralizedSnakeCase = $this->pluralize($snakeCase);

        return $pluralizedSnakeCase;
    }

    public function setData(array $data, $id)
    {
        $this->data = array_merge($data, ['id' => $id]);

        foreach ($data as $column => $value) {
            $this->data[$column] = $value;
            // $this->{$column} = $value;
        }
    }


    public static function create(array $data)
    {
        $model = new static(App::getInstance()->make(Database::class));

        $columns = implode(', ', array_keys($data));
        $values = implode(', ', array_map(fn ($val, $index) => ':' . $index, $data, array_keys($data)));

        $sql = "INSERT INTO $model->tableName ($columns) VALUES ($values);";
        $statement = $model->db->prepare($sql);

        foreach ($data as $column => $value) {
            $statement->bindValue(':' . $column, $value);
        }

        $statement->execute();

        $lastInsertId = $model->db->lastInsertId();

        $model->setData($data, $lastInsertId);

        return $model;
    }

    public function update()
    {
    }

    public function delete()
    {
    }

    public function save()
    {
    }

    public function get()
    {
    }

    public function where()
    {
    }

    public function __get($name)
    {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        }

        return null;
    }

    private function pluralize($singular)
    {
        $lastChar = substr($singular, -1);
        $secondLastChar = substr($singular, -2, 1);

        if ($lastChar === 'y' && !in_array($secondLastChar, ['a', 'e', 'i', 'o', 'u'])) {
            return substr($singular, 0, -1) . 'ies';
        } elseif ($lastChar === 's' || $lastChar === 'x' || $lastChar === 'z') {
            return $singular . 'es';
        } else {
            return $singular . 's';
        }
    }
}
