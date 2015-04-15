<?php
namespace your\namespace\Model;

use your\namespace;

abstract class AppModel
{
    protected $fields       = null;
    protected $conn         = null;

    public function __construct()
    {
        $this->conn = PhotoWidget\App\Database::getInstance();
        $this->populateFields();
    }

    protected function populateFields()
    {
        $sql = "SHOW columns FROM " . static::$table;
        
        $result = $this->conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $this->fields[$row["Field"]] = null;
        }

    }

    public function find($search)
    {
        $sql = "SELECT * FROM " . static::$table . " WHERE id = {$this->conn->safeQuote($search)} LIMIT 1";
        $result = $this->conn->query($sql);

        if ($row = $result->fetch_assoc()) {
            foreach ($row as $key => $value) {
                $this->fields[$key] = $value;
            }
        }

    }

    public function findByField($fieldName, $search)
    {
        $sql = "SELECT * FROM `" . static::$table . "` WHERE `" . $fieldName . "` = {$this->conn->safeQuote($search)} LIMIT 1";

        $result = $this->conn->query($sql);

        if ($row = $result->fetch_assoc()) {
            foreach ($row as $key => $value) {
                $this->$key = $value;
            }
        }

    }

    public function findByMultiple(array $searchArray, $type = "AND")
    {
        $sql = "SELECT * FROM `" . static::$table . "` WHERE ";

        $where = array();

        foreach ($searchArray as $key => $value) {
            $where[] = "`" . $key . "` = {$this->conn->safeQuote($value)}";
        }

        $sql .= implode(" {$type} ", $where) . " LIMIT 1";

        $result = $this->conn->query($sql);

        if ($row = $result->fetch_assoc()) {
            foreach ($row as $key => $value) {
                $this->$key = $value;
            }
        }

    }

    public static function search($search)
    {
        $conn = PhotoWidget\App\Database::getInstance();
        $sql = "SELECT * FROM " . static::$table . " WHERE {$search}";

        $results = array();
        $result = $conn->query($sql);

        while ($row = $result->fetch_assoc()) {
            $model = new static();
            foreach ($row as $key => $value) {
                $model->$key = $value;
            }
            $results[$model->id] = $model;
        }

        return $results;
    }

    public function save()
    {
        $id = $this->id;
        if (!empty($id)) {
            return $this->update();
        } else {
            return $this->insert();
        }

        return false;
    }

    protected function update()
    {
        $conn = $this->conn;
        $sql = "UPDATE " . static::$table . " SET ";
        $q = array();
        foreach ($this->fields as $field => $value) {
            $q[] = "{$field} = {$conn->safeQuote($value)}";
        }

        $sql .= implode(", ", $q);
        $sql .= " WHERE id = {$this->id}";

        return $conn->query($sql);
    }

    protected function insert()
    {
        $conn = $this->conn;
        $sql = "INSERT INTO " . static::$table . " SET ";
        $q = array();
        foreach ($this->fields as $field => $value) {
            $q[] = "{$field} = {$conn->safeQuote($value)}";
        }

        $sql .= implode(", ", $q);

        $result = $conn->query($sql);

        $this->id = $conn->insert_id;
        return $result;

    }

    public function delete()
    {
        $conn = $this->conn;
        $sql = "DELETE FROM " . static::$table . " WHERE id = {$this->id}";
        return $conn->query($sql);
    }

    public function count($where)
    {
        $conn = $this->conn;
        $sql = "SELECT COUNT(id) FROM " . static::$table . " WHERE {$where}";
        $result = $conn->query($sql);
        return $result->num_rows;

    }

    public function __set($field, $value)
    {
        if (in_array($field, array_keys($this->fields))) {
            $this->fields[$field] = $value;

            return $this;
        }

        throw new \Exception("Field {$field} not found");
    }

    public function __get($call)
    {
        if (in_array($call, array_keys($this->fields))) {
            return $this->fields[$call];
        }

        throw new \Exception("Field {$call} not found");
    }

    public function toArray()
    {
        return $this->fields;
    }
}
