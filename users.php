<?php require __DIR__ . '/includes/unit.php' ?>
<?php	require __DIR__ . "/includes/header.php" ?>

<body>
	<div id="colorlib-page">
		<aside id="colorlib-aside" role="complementary" class="js-fullheight">
			<nav id="colorlib-main-menu" role="navigation">
			<?= $menu->ren() ?>
			</nav>
		</aside> <!-- END COLORLIB-ASIDE -->
		<div id="colorlib-main">
			<section class="contact-section px-md-4 pt-5">
				<div class="container">
					<div class="row block-9">
						<div class="col-lg-12">
							<div class="row">
								<div class="col-md-12 mb-1">
									<h3 class="h3">Пользователи</h3>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 mb-1">
									<table class="table table-striped">
										<thead>
											<tr>
												<th scope="col">#</th>
												<th scope="col">Name</th>
												<th scope="col">Surname</th>
												<th scope="col">Login</th>

												<th scope="col">E-mail</th>
												<th scope="col">Temp block</th>
												<th scope="col">Permanent block</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<th scope="row">1</th>
												<td>Mark</td>
												<td>Otto</td>
												<td>dfg</td>
												<td>@mdo</td>
												<td>
													<a href="temp-block.php" class="btn btn-outline-warning px-4">⏳
														Block</a>
												</td>
												<td>
													<a href="#" class="btn btn-outline-danger px-4">📌 Block</a>
												</td>
											</tr>
											<tr>
												<th scope="row">2</th>
												<td>Mark</td>
												<td>Otto</td>
												<td>dfg</td>
												<td>@mdo</td>
												<td>
													<a href="temp-block.php" class="btn btn-outline-warning px-4">⏳
														Block</a>
												</td>
												<td>
													<a href="#" class="btn btn-outline-danger px-4">📌 Block</a>
												</td>
											</tr>
											<tr>
												<th scope="row">3</th>
												<td>Mark</td>
												<td>Otto</td>
												<td>dfg</td>
												<td>@mdo</td>
												<td>
													<a href="temp-block.php" class="btn btn-outline-warning px-4">⏳
														Block</a>
												</td>
												<td>
													<a href="#" class="btn btn-outline-danger px-4">📌 Block</a>
												</td>
											</tr>

										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div><!-- END COLORLIB-MAIN -->
	</div><!-- END COLORLIB-PAGE -->

	<!-- loader -->
	<?php	require __DIR__ . "/includes/preloader.php" ?>

		<?php	require __DIR__ . "/includes/script.php" ?>

</body>

</html>