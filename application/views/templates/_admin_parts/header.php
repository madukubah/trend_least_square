<nav class="navbar">
	<div class="container-fluid">
		<div class="navbar-header">
			<a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
			<a href="javascript:void(0);" class="bars"></a>
			<a class="navbar-brand" href="<?php echo base_url()?>">COREIGNITER</a>
		</div>
		<div class="collapse navbar-collapse" id="navbar-collapse">
			<ul class="nav navbar-nav navbar-right">
				<!-- Notifications -->
				<li class="dropdown">
					<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">
						<i class="material-icons">notifications</i>
						<span class="label-count">1</span>
					</a>
					<ul class="dropdown-menu">
						<li class="header">NOTIFICATIONS</li>
						<li class="body">
							<ul class="menu">
								<li>
									<a href="javascript:void(0);">
										<div class="icon-circle bg-light-green">
											<i class="material-icons">person_add</i>
										</div>
										<div class="menu-info">
											<h4>Notifikasi Disini</h4>
											<p>
												<i class="material-icons">access_time</i> 14 mins ago
											</p>
										</div>
									</a>
								</li>
								
								
							</ul>
						</li>
						<li class="footer">
							<a href="javascript:void(0);">View All Notifications</a>
						</li>
					</ul>
				</li>
				<!-- #END# Notifications -->
				
				<li class="pull-right"><a href="<?php echo site_url('auth/logout')?>" class="" ><i class="material-icons">input</i></a></li>
			</ul>
		</div>
	</div>
	</nav>
	<!-- #Top Bar -->