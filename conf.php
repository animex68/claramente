<?php

class Model {
	public $db;
	public $dbfile = "database.sql";
	public $dbhost = "remotemysql.com";
	public $dbname = "8pHj3nOmKN";
	public $dbuser = "8pHj3nOmKN";
	public $dbpass = "kvt9ohfdsu";
	public $tables = ["List", "ListValue", "ListArticle", "ListArticleValue"];

	function __construct () {
		$this->db = new mysqli($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);
		if ($this->db->connect_errno) die("Не удалось подключиться к MySQL: {$this->db->connect_errno} {$this->db->connect_error}");
		foreach ($this->tables as $key => $value) {
			if (!$this->db->query("SHOW TABLES LIKE '{$value}'")->fetch_assoc()) {
				$this->print("Table <strong>{$value}</strong> not found. Trying to create the table");
				$sql = file(__DIR__ . "/{$this->dbfile}");
				foreach ($sql as $k => $line) {
					if (preg_match("/{$value}/", $line)) {
						if ($this->db->query($line)) $this->print("Created");
						else $this->print("Creation <strong>error</strong>: {$this->db->error}");
						break;
					}
				}
			}
		}
	}

	function print ($text) {
		static $print;
		if ($print == true) echo "<hr> {$text}";
		else {
			$print = true;
			echo $text;
		}
	}
}
