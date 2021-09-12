<?php 

class Render
{
	public function __construct($db)
	{
		$this->db = $db;
	}

	public function header()
	{
		$columns = $this->db->columns();

		$header = "<tr>";

		foreach ($columns as $column) {
			$header .= "<th>".$column."</th>";
		}

		$header .= "</tr>\n";

		return $header;
	}

	public function footer($participation_fee)
	{
		$footer = "<tr><td colspan='5'>Total Participation Fee</td><td>$participation_fee</td><td></td></tr>\n";

		return $footer;
	}

	public function rows()
	{
		$table = "";
		$participation_fee = 0;

		while($row = $this->result->fetch_row())
		{
			$table .= "<tr>";

			foreach ($row as $key => $value)
			{
				$table .= "<td>".$value."</td>";
				if ($key == 5) $participation_fee += $value;
			}

			$table .= "</tr>\n";
		}

		$this->participation_fee = $participation_fee;

		return $table;
	}

	public function table($result=null)
	{	
		if ($result) $this->result = $result;
		$table  = "<table>\n";
		$table .= $this->header();
		$table .= $this->rows();
		$table .= $this->footer($this->participation_fee);
		$table .= "</table>\n";

		return $table;
	}

	public function select($field)
	{
		$eol     = "\n";
		$result  = $this->db->select_field($field);
		$select  = '<select name="'.$field.'" onchange="select_field(this.name, this.value)">'.$eol;
		$select .= '<option value="">Select '.$field.'</option>'.$eol;

		foreach ($result->fetch_all() as $row)
		{
			$value = $row[0];
			$select .= "<option value='$value'>".$value."</option>".$eol;
		}

		$select .= "</select>".$eol;

		return $select;
	}
}
