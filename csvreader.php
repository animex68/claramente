<?php

class CSVReader extends Model {
	public $articles = [];
	public $values = [];
	public $num = 0;
	public $column = 0;

	function read ($dir) {
		$handle = fopen($dir, "r");
		$row = 0;
		$data = [];
		$articles = [];
		$list_id = NULL;

		while (($file = fgetcsv($handle, 1000, ";")) !== FALSE) {
			$num = count($file);
			for ($i = 0; $i < $num; $i++) {
				$data[$row][] = $file[$i];

				// List
				if ($i == 0 && $row > 0) {
					if ($this->db->query("INSERT INTO List (name, added) VALUES ('{$this->db->real_escape_string($file[$i])}', Now())")) {
						$list_id = $this->db->insert_id;
					} else  {
						$this->print("Ошибка добавления List значение {$file[$i]}: {$this->db->error}");
						$list_id = NULL;
					}
				}
				// ListArticle, ListArticleValue, ListValue
				else if (($list_id != NULL || $row == 0) && strlen($file[$i]) > 0 && $i > 0) {
					// ListArticle
					if ($row == 0) {
						$articles[$i] = $this->getArticle($file[$i]);
					}
					// ListArticleValue
					else {
						$value_id = $this->getValue($file[$i]);
						// ListValue
						if (!$this->db->query("INSERT INTO ListValue (list_id, article_id, article_value_id) VALUES ({$list_id}, {$articles[$i]}, {$value_id})")) $this->print("Ошибка добавления ListValue для {$list_id}, {$articles[$i]}, {$value_id}");
					}
				}

				// list_id для вывода таблицы
				if ($i == $num - 1) {
					if ($list_id != NULL) $data[$row][] = $list_id;
					else $data[$row][] = "ID";
				}
			}

			$row++;
		}

		fclose($handle);
		$this->num = count($data);
		$this->column = count($data[0]);

		return $data;
	}

	function getArticle ($name) {
		if (isset($this->articles[$name])) return $this->articles[$name];
		else {
			$article_id = $this->db->query("SELECT article_id FROM ListArticle WHERE name = '{$this->db->real_escape_string($name)}'")->fetch_assoc();
			if ($article_id) {
				$this->articles[$name] = $article_id["article_id"];
				return $article_id["article_id"];
			}
			else {
				if ($this->db->query("INSERT INTO ListArticle (name) VALUES ('{$this->db->real_escape_string($name)}')")) {
					$article_id = $this->db->insert_id;
					$this->articles[$name] = $article_id;
					return $article_id;
				}
				else return false;
			}
		}
	}

	function getValue ($name) {
		if (isset($this->values[$name])) return $this->values[$name];
		else {
			$value_id = $this->db->query("SELECT article_value_id FROM ListArticleValue WHERE name = '{$this->db->real_escape_string($name)}'")->fetch_assoc();
			if ($value_id) {
				$this->values[$name] = $value_id["article_value_id"];
				return $value_id["article_value_id"];
			}
			else {
				if ($this->db->query("INSERT INTO ListArticleValue (name) VALUES ('{$this->db->real_escape_string($name)}')")) {
					$value_id = $this->db->insert_id;
					$this->values[$name] = $value_id;
					return $value_id;
				}
				else return false;
			}
		}
	}

	function __construct () {
		parent::__construct();
	}
}