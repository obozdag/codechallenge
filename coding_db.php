<?php 

class DB
{
	public function connect_db($db_name = null)
	{
		$db_server = "localhost";
		$db_user   = "coding";
		$db_pass   = "coding";

		if ($db_name)
		{
			$conn = new mysqli($db_server, $db_user, $db_pass, $db_name);
		}
		else
		{
			$conn = new mysqli($db_server, $db_user, $db_pass);
		}


		if ($conn->connect_error)
		{
			die("Connection failed: ".$conn->connect_error);
		}

		$this->conn = $conn;
		
		return $conn;
	}

	public function close_db()
	{
		$this->conn->close();
	}

	public function create_db()
	{
		$conn = $this->connect_db();
		$sql  = "CREATE DATABASE coding";
		
		if ($conn->query($sql) === true)
		{
			$message = "Database created successfully.";
		}
		else
		{
			$message = "Error creating database: ".$conn->error;
		}

		$this->close_db();

		return $message;
	}

	public function create_table()
	{
		$conn = $this->connect_db('coding');
		$sql  = "CREATE TABLE IF NOT EXISTS events (
			participation_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			employee_name VARCHAR(30) NOT NULL,
			employee_mail VARCHAR(30) NOT NULL,
			event_id INT(6) NOT NULL,
			event_name VARCHAR(255) NOT NULL,
			participation_fee DECIMAL(10,2) NOT NULL,
			event_date DATETIME
		)";

		if ($conn->query($sql) === true)
		{
			$message = "Table created successfully.";
		}
		else
		{
			$message = "Error creating table: ".$conn->error;
		}

		$this->close_db();

		return $message;
	}

	public function insert_rows()
	{
		$events = file_get_contents('coding_challenge/events.json');
		$events = json_decode($events);
		$conn   = $this->connect_db('coding');
		
		$sql = "INSERT INTO events (
			employee_name, 
			employee_mail, 
			event_id, 
			event_name, 
			participation_fee, 
			event_date
			) VALUES ";

		foreach ($events as $event)
		{
			$sql .= "(".
			"'$event->employee_name', ".
			"'$event->employee_mail', ".
			"'$event->event_id', ".
			"'$event->event_name', ".
			"'$event->participation_fee', ".
			"'$event->event_date'), ";
		}

		$sql = rtrim($sql, ', ');

		if ($conn->query($sql) === true)
		{
			$message = "ROWS are inserted successfully.";
		}
		else
		{
			$message = "Error inserting rows: ".$conn->error;
		}

		$this->close_db();

		return $message;
	}

	public function columns()
	{
		$conn   = $this->connect_db('coding');
		$sql    = "DESCRIBE events";
		$result = $conn->query($sql);
		$this->close_db();

		foreach ($result->fetch_all() as $column) {
			$columns[] = $column[0];
		}

		return $columns;
	}

	public function getall()
	{
		$conn   = $this->connect_db('coding');
		$sql    = "SELECT * FROM events";
		$result = $conn->query($sql);
		$this->close_db();

		if ($result->num_rows > 0)
		{
			return $result;
		}
		else
		{
			return null;
		}
	}

	public function where($field, $value)
	{
		$conn   = $this->connect_db('coding');
		$stmt = $conn->prepare("SELECT * FROM events WHERE $field = ?");
		$stmt->bind_param('s', $value);
		$stmt->execute();

		$result = $stmt->get_result();
		$this->close_db();

		if ($result->num_rows > 0)
		{
			return $result;
		}
		else
		{
			return null;
		}
	}

	public function select_field($field)
	{
		$conn   = $this->connect_db('coding');
		$conn->real_escape_string($field);
		$sql    = "SELECT ".$field." FROM events GROUP BY ".$field;
		$result = $conn->query($sql);
		$this->close_db();
		
		return $result;
	}

}


// $coding = new DB();
// $coding->connect_db();
// echo $coding->create_db();
// echo $coding->create_table();
// echo $coding->insert_rows();
// var_dump($coding->getall()->fetch_all());
// var_dump($coding->where('participation_id', 1)->fetch_all());
// var_dump($coding->where("employee_name', 'Mia Wyss')->fetch_all());