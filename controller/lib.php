<?php

class Database {

    private $host;
    private $username;
    private $password;
    private $database;
    private $connection;

    public function __construct($host, $username, $password, $database) {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;

        $this->connect();
    }

    private function connect() {
        $this->connection = new mysqli($this->host, $this->username, $this->password, $this->database);

        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }

    public function insert($table, $data) {
        $columns = implode(', ', array_keys($data));
        $values = "'" . implode("', '", $data) . "'";

        $query = "INSERT INTO $table ($columns) VALUES ($values)";

        return $this->connection->query($query);
    }

    public function select($table, $condition = "") {
        $query = "SELECT * FROM $table";

        if ($condition) {
           echo  $query .= " WHERE $condition";
        }

        $result = $this->connection->query($query);

        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return false;
        }
    }

    public function update($table, $data, $condition) {
        $set = "";

        foreach ($data as $key => $value) {
            $set .= "$key = '$value', ";
        }

        $set = rtrim($set, ', ');

        $query = "UPDATE $table SET $set WHERE $condition";

        return $this->connection->query($query);
    }

    public function delete($table, $condition) {
        $query = "DELETE FROM $table WHERE $condition";

        return $this->connection->query($query);
    }

    public function customQuery($query) {
        return $this->connection->query($query)->fetch_all(MYSQLI_ASSOC);
    }

    public function getName($table, $condition) {
        $query = "SELECT name FROM $table WHERE $condition";

        $result = $this->connection->query($query);

        if ($result) {
            return $result->fetch_assoc()['name'];
        } else {
            return false;
        }
    }

    public function getOneColumn($table, $col, $condition) {
        $query = "SELECT $col FROM $table WHERE $condition";

        $result = $this->connection->query($query);

        if ($result) {
            return $result->fetch_assoc();
        } else {
            return false;
        }
    }
public function getLastRow($table, $condition, $orderByColumn) {
    $query = "SELECT * FROM $table WHERE $condition ORDER BY $orderByColumn DESC LIMIT 1";

    $result = $this->connection->query($query);

    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return false;
    }
}

    public function getColumnsByCondition($columns, $table, $condition) {
        $columnsStr = implode(', ', $columns);
        $query = "SELECT $columnsStr FROM $table WHERE $condition";

        $result = $this->connection->query($query);

        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return false;
        }
    }

    public function getAllRows($table) {
        $query = "SELECT * FROM $table";

        $result = $this->connection->query($query);

        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return false;
        }
    }

    public function getRowById($table, $id) {
        $query = "SELECT * FROM $table WHERE id = $id";

        $result = $this->connection->query($query);

        if ($result) {
            return $result->fetch_assoc();
        } else {
            return false;
        }
    }

    public function updateRowById($table, $data, $id) {
        $set = "";

        foreach ($data as $key => $value) {
            $set .= "$key = '$value', ";
        }

        $set = rtrim($set, ', ');

        $query = "UPDATE $table SET $set WHERE id = $id";

        return $this->connection->query($query);
    }

    public function insertMultipleRecords($table, $dataList) {
//    $this->pre($dataList);
    $columns = implode(', ', array_keys($dataList[0]));

    $values = [];
    foreach ($dataList as $data) {
        // Flatten nested arrays before imploding
        $flattenedData = array_map(function($value) {
            return is_array($value) ? $value[0] : $value;
        }, $data);
        $values[] = "('" . implode("', '", $flattenedData) . "')";
    }

    $valuesStr = implode(', ', $values);

     $query = "INSERT INTO $table ($columns) VALUES $valuesStr";

    return $this->connection->query($query);
}


    public function deleteRowById($table, $id) {
        $query = "DELETE FROM $table WHERE id = $id";

        return $this->connection->query($query);
    }

    public function executeRawQuery($query) {
        return $this->connection->query($query);
    }

    public function countRowsByCondition($table, $condition) {
        $query = "SELECT COUNT(*) as count FROM $table WHERE $condition";

        $result = $this->connection->query($query);

        if ($result) {
            $count = $result->fetch_assoc()['count'];
            return intval($count);
        } else {
            return false;
        }
    }

    public function recordExists($table, $condition) {
        // Use proper concatenation for the condition value
        $query = "SELECT id FROM $table WHERE $condition";

        $result = $this->connection->query($query);

        if ($result) {
            $exists = $result->fetch_assoc()['id'];
            return boolval($exists);
        } else {
            return false;
        }
    }

    public function getLastInsertedId() {
        return $this->connection->insert_id;
    }

    public function getMinValue($table, $column) {
        $query = "SELECT MIN($column) as min_value FROM $table";

        $result = $this->connection->query($query);

        if ($result) {
            return $result->fetch_assoc()['min_value'];
        } else {
            return false;
        }
    }

    public function getMaxValue($table, $column) {
        $query = "SELECT MAX($column) as max_value FROM $table";

        $result = $this->connection->query($query);

        if ($result) {
            return $result->fetch_assoc()['max_value'];
        } else {
            return false;
        }
    }

    public function getCountValue($table, $column, $condition = "") {
        $whereClause = ($condition != "") ? " WHERE $condition" : "";
        $query = "SELECT COUNT($column) as count FROM $table $whereClause";

        $result = $this->connection->query($query);

        if ($result !== false && $result->num_rows > 0) {
            return $result->fetch_assoc()['count'];
        } else {
            return false;
        }
    }

    public function updateRecordsByCondition($table, $data, $condition) {
        $set = "";

        foreach ($data as $key => $value) {
            $set .= "$key = '$value', ";
        }

        $set = rtrim($set, ', ');

        $query = "UPDATE $table SET $set WHERE $condition";

        return $this->connection->query($query);
    }

    public function setSessionVariables($data) {
        // Start the session if not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Set the session variables
        foreach ($data as $key => $value) {
            $_SESSION[$key] = $value;
        }
    }

    public function getSessionVariable($key, $default = null) {
        // Start the session if not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Return the session variable if set, otherwise return the default value
        return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
    }

    public function destroySession() {
        // Start the session if not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Unset all session variables
        $_SESSION = array();

        // Destroy the session
        session_destroy();
    }

    public function close() {
        $this->connection->close();
    }

    public function pre($data) {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }
    
   
   
    public function transactionStart()
    {
        $this->connection->begin_transaction(); 
    }   

    public function rollback()
    {
        $this->connection->rollback(); 
    }

    public function commit()
    {
        $this->connection->commit();
    }

}

// Example usage:
$db = new Database('localhost', 'root', '', 'mybilling');

