<?php

namespace Core\Model;

use Core\Database\Database;
use Core\Main\App;
use PDOException;
use ReflectionClass;

class Model
{
    private Database $db;
    protected string $tableName;
    protected $data;

    public function __construct()
    {
        $this->tableName = $this->getTableNameFromClass();
        $this->db = App::getInstance()->make(Database::class);
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
        }
    }


    public static function create(array $data)
    {
        $model = new static();
        foreach ($data as $column => $value) {
            $model->data[$column] = $value;
        }

        return $model->save();
    }

    public function save()
    {
        $columns = implode(', ', array_keys($this->data));
        $values = implode(', ', array_map(fn ($val, $index) => ':' . $index, $this->data, array_keys($this->data)));

        $sql = "INSERT INTO $this->tableName ($columns) VALUES ($values);";
        $statement = $this->db->prepare($sql);

        foreach ($this->data as $column => $value) {
            $statement->bindValue(':' . $column, $value);
        }

        $statement->execute();

        $lastInsertId = $this->db->lastInsertId();

        $this->setData($this->data, $lastInsertId);

        return $this;
    }

    public function update()
    {
    }

    public function delete()
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

    public function __set(string $name, mixed $value)
    {
        $this->data[$name] = $value;
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
