<div class="row">
	<div class="col-md-12">
		<div class="box">
			<div class="box-header">
				<h3 class="box-title">List Akun</h3>

			</div><!-- /.box-header -->
			<div class="box-body no-padding">
				<table class="table">
					<tbody>
						<tr>
							<th style="width: 10px">No</th>
							<th>Username</th>
							
						</tr>
						<?php foreach ($user as $key => $s): ?>
							<tr>
								<td><?php echo ++$key; ?></td>
								<td><?php echo $s['username']; ?></td>
								
								<td>
									<a class="btn btn-primary" href="<?php echo site_url().'/master/akun/edit/'.$s['id_user']; ?>">Edit</a> |
									<a class="btn btn-danger" href="<?php echo site_url().'/master/akun/hapus/'.$s['id_user']; ?>">Hapus</a>
								</td>
							</tr>
						<?php endforeach; ?>

					</tbody>
				</table>
			</div><!-- /.box-body -->
		</div><!-- /.box -->
	</div>
</div>

<form action="<?php echo base_url('content/akun'); ?>" method="post">
	<div class="form-group">
		<label for="id_user">Id</label>
		<input class="form-control" name="id_user" placeholder="Id User" />
	</div>
	<div class="form-group">
		<label for="username">Username</label>
		<input class="form-control" name="username" placeholder="Username" />
	</div>
	<div class="form-group">
		<label for="password">Password</label>
		<input class="form-control" name="password" placeholder="Password" />
	</div>
	
	<button type="submit" class="btn btn-success">Submit</button>

</form>