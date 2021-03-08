<!DOCTYPE html>
<html>
<head>
	<title>Загрузка CSV</title>
</head>
<body>
	<form enctype="multipart/form-data" method="POST">
	<input type="hidden" name="MAX_FILE_SIZE" />
	Загрузить файл: <input name="file" type="file" />
	<input type="submit" value="Загрузить файл" />
</form>
	<? if (isset($data)) :?>
	<table>
		<thead>
			<tr>
				<? for ($i = 0; $i < $csvreader->column; $i++) echo "<th>{$data[0][$i]}</th>"; ?>
			</tr>
		</thead>
		<tbody>
			<? for ($i = 1; $i < $csvreader->num; $i++): ?>
			<tr>
				<? for ($c = 0; $c < $csvreader->column; $c++) echo "<td>{$data[$i][$c]}</td>"; ?>
			</tr>
			<? endfor; ?>
		</tbody>
	</table>
	<? endif; ?>
</body>
</html>