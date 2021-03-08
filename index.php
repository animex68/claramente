<?php
require_once(__DIR__ . "/conf.php");
require_once(__DIR__ . "/csvreader.php");
$csvreader = new CSVReader;

if (isset($_FILES["file"])) {
	if (is_uploaded_file($_FILES["file"]["tmp_name"])) {
		$data = $csvreader->read($_FILES["file"]["tmp_name"]);
	} else echo "Ошибка чтения файла. Код ошибки {$_FILES["file"]["error"]}";
}

require_once(__DIR__ . "/form.php");