<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<meta charset="UTF-8">
	<title></title>
</head>
<body>

<script>

	var data = <?= json_encode($user_profile) ?>;

	window.opener.getLogin(data);
	window.close();

</script>


</body>
</html>