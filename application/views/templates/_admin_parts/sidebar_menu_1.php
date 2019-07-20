
<!-- #Top Bar -->
<section>
        <!-- Left Sidebar -->
        <aside id="leftsidebar" class="sidebar">
            <!-- User Info -->
            <div class="user-info" style="background: url(<?php echo base_url('assets/')?>img/user-img-background.jpg) no-repeat no-repeat;">
                <div class="image">
                    <img src="<?php echo base_url('assets/')?>img/user.png" width="48" height="48" alt="User" />
                </div>
                <div class="info-container">
                    <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo ucwords($this->session->userdata('user_profile_name')) ?></div>
                    <div class="email"><?php echo $this->session->userdata('email') ?></div>
                    <div class="btn-group user-helper-dropdown">
                        <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                        <ul class="dropdown-menu pull-right">
                            <li><a href="<?php echo site_url('setting/userprofile') ?>"><i class="material-icons">person</i>Profile</a></li>
                            <!--<li role="separator" class="divider"></li>-->
                        </ul>
                    </div>
                </div>
            </div>
            <!-- #User Info -->
            <!-- Menu -->
            <div class="menu">
                <ul class="list">
                   
                </ul>
            </div>
            <!-- #Menu -->
            <!-- Footer -->
            <div class="legal">
                <div class="copyright">
                    &copy; 2019 <a href="javascript:void(0);">Coreigniter - Techno's Studio</a>.
                </div>
                <div class="version">
                    <b>Version: </b> 0.1
                </div>
            </div>
            <!-- #Footer -->
        </aside>
        <!-- #END# Left Sidebar -->

    </section>
