<?php
	include "comun/vars.php";
	include "api/functions.php";
?>
<!DOCTYPE html>
<html lang="es">
	<?php
		include ROOT."comun/head.php";
	?>
	<body>
		<?php
			include ROOT."comun/nav.php";
		?>
		<div class="container">
			<div class="row">
				<div class="col-sm-2 col-xs-12"></div>
				<div class="col-sm-8 col-xs-12">
					<h2 class='title'>CLASIFICACIÓN</h2>
					<table class="table table-hover table-striped table-responsive">
						<thead>
							<th></th>
							<th>Usuario</th>
							<th>Puntos</th>
						</thead>
						<tbody>
							<tr user="1">
								<td>1</td>
								<td><?php ranking('nick', '1'); ?></td>
								<td><?php ranking('fop', '1'); ?></td>
							</tr>
							<tr user="2">
								<td>2</td>
								<td><?php ranking('nick', '2'); ?></td>
								<td><?php ranking('fop', '2'); ?></td>
							</tr>
							<tr user="3">
								<td>3</td>
								<td><?php ranking('nick', '3'); ?></td>
								<td><?php ranking('fop', '3'); ?></td>
							</tr>
							<tr user="4">
								<td>4</td>
								<td><?php ranking('nick', '4'); ?></td>
								<td><?php ranking('fop', '4'); ?></td>
							</tr>
							<tr user="5">
								<td>5</td>
								<td><?php ranking('nick', '5'); ?></td>
								<td><?php ranking('fop', '5'); ?></td>
							</tr>
							<tr user="6">
								<td>6</td>
								<td><?php ranking('nick', '6'); ?></td>
								<td><?php ranking('fop', '6'); ?></td>
							</tr>
							<tr user="7">
								<td>7</td>
								<td><?php ranking('nick', '7'); ?></td>
								<td><?php ranking('fop', '7'); ?></td>
							</tr>
							<tr user="8">
								<td>8</td>
								<td><?php ranking('nick', '8'); ?></td>
								<td><?php ranking('fop', '8'); ?></td>
							</tr>
							<tr user="9">
								<td>9</td>
								<td><?php ranking('nick', '9'); ?></td>
								<td><?php ranking('fop', '9'); ?></td>
							</tr>
							<tr user="10">
								<td>10</td>
								<td><?php ranking('nick', '10'); ?></td>
								<td><?php ranking('fop', '10'); ?></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</body>
	<?php
		include ROOT."comun/libs.php";
	?>
</html>