<div class="modal-header px-4">
	<h4 class="modal-title"><?= $title ?></h4>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		<span aria-hidden="true">&times;</span>
	</button>
</div>

<div class="modal-body p-3">
	<div class="container-fluid p-0">
		<div class="row">
			<div class="col">
				<table class="table table-detail table-striped mb-4">
					<tbody>
						<tr>
							<td scope="row" class="title">ID User</td>
							<td><?=$user['id_user']?></td>
						</tr>	
						<tr>
							<td scope="row" class="title">Nama</td>
							<td><?=$user['nama']?></td>
						</tr>	
						<tr>
							<td scope="row" class="title">Username</td>
							<td><?=$user['username']?></td>
						</tr>	
						<tr>
							<td scope="row" class="title">Password</td>
							<td><?=$user['password']?></td>
						</tr>	
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<script>
	$('[data-toggle="tooltip"]').mouseover(function() {
		$(this).tooltip();
	});
</script>