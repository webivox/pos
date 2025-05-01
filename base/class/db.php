<?php

class db
{
    private $host = _DBHOST;
    private $username = _DBUSER;
    private $password = _DBPASS;
    private $dbname = _DBNAME;
    protected $conn;

    // Constructor automatically connects to the database
    public function __construct()
    {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->dbname);

        // Check connection
        if ($this->conn->connect_error) {
            die("Database connection failed: " . $this->conn->connect_error);
        }

        $this->conn->set_charset("utf8mb4"); // Set character encoding
    }

    // Prepared statement query execution (Safe Query)
    public function query($sql, $params = [])
    {
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            die("MySQL Error: " . $this->conn->error);
        }

        // If there are parameters, bind them
        if (!empty($params)) {
            $types = str_repeat("s", count($params)); // Default all as string (modify if needed)
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        return $stmt;
    }

    // Fetch a single row
    public function fetch($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);
        return $stmt->get_result()->fetch_assoc();
    }

    // Fetch multiple rows
    public function fetchAll($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Insert, Update, Delete operations
    public function execute($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);
        return $stmt->affected_rows;
    }

    // Get last inserted ID
    public function last_id()
    {
        return $this->conn->insert_id;
    }

    // Secure escaping function
    public function escape($field)
    {
        return $this->conn->real_escape_string(trim($field));
    }

    // Secure escaping function
    public function request($field)
	{
		if(isset($_REQUEST[$field]))
		{
			$var=trim(htmlentities($_REQUEST[$field], ENT_QUOTES, 'utf-8'));
			return $this->conn->real_escape_string(trim($var));
		}
		else
		{
			return '';
		}
	}

    // Close connection
    public function close()
    {
        $this->conn->close();
    }
}

// Usage
$db = new db();

/*
// Insert Data
$db->execute("INSERT INTO users (name, email) VALUES (?, ?)", ["John Doe", "john@example.com"]);

// Fetch Single Row
$user = $db->fetch("SELECT * FROM users WHERE id = ?", [1]);
print_r($user);

// Fetch Multiple Rows
$users = $db->fetchAll("SELECT * FROM users");
print_r($users);
*/






?>
