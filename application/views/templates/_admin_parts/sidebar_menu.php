
<!-- #Top Bar -->
<section>
        <!-- Left Sidebar -->
        <aside id="leftsidebar" class="sidebar">
            <!-- User Info -->
            <div class="user-info" style="background: url(<?php echo base_url('assets/')?>img/user-img-background.jpg) no-repeat no-repeat;">
                <div class="image">
                    <?php if( $this->session->userdata( 'user_image' ) ) :?>
                            <img src="<?php echo base_url('uploads/users_photo/').$this->session->userdata( 'user_image' ) ?>" width="48" height="48" alt="User" />
                    <?php else: ?>
                            <img src="<?php echo base_url('assets/')?>img/user.png" width="48" height="48" alt="User" />
                    <?php endif; ?>
                    
                </div>
                <div class="info-container">
                    <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo ucwords($this->session->userdata('user_profile_name')) ?></div>
                    <div class="email"><?php echo $this->session->userdata('email') ?></div>
                    <div class="btn-group user-helper-dropdown">
                        <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                        <ul class="dropdown-menu pull-right">
                            <li><a href="<?php echo site_url('user/profile') ?>"><i class="material-icons">person</i>Akun</a></li>
                            <!--<li role="separator" class="divider"></li>-->
                        </ul>
                    </div>
                </div>
            </div>
            <!-- #User Info -->
            <!-- Menu -->
            <div class="menu">
                <ul class="list">
                    <li class="header">DAFTAR MENU</li>
                    <?php 
                    function print_menus( $datas )
                    {
                        foreach( $datas as $data )
                        {
                            if( ( !$data->status )  ) continue;

                            if( !empty( $data->branch )  )
                            {
                                ?>
                                    <li id="<?php echo $data->list_id ?>" >
                                        <a href="javascript:void(0);" class="menu-toggle">
                                        <i class="material-icons"><?php echo $data->icon ?></i>
                                            <span><?php echo $data->name?></span>
                                            
                                        </a>
                                    <ul class="ml-menu">
                                        <?php
                                            print_menus( $data->branch );
                                        ?>
                                    </ul>
                                    </li>
                                <?php
                            }else{
                                ?>
                                    <li id="<?php echo $data->list_id ?>"  >
                                        <a  href="<?php echo site_url( $data->link ) ?>" >
                                            <i class="material-icons"><?php echo $data->icon ?></i>
                                                <span><?php echo $data->name?></span>
                                            <div id="<?php echo 'notif_'.$data->list_id ?>" >
                                            </div>
                                        </a>
                                    </li>
                                <?php
                            }
                        }
                    }

                    print_menus( $_menus );
                    ?>
                
                </ul>
            </div>
            <!-- #Menu -->
            <!-- Footer -->
            <div class="legal">
                <div class="copyright">
                    &copy; 2019 <a href="javascript:void(0);">Coreigniter - <?php echo APP_AUTHOR?></a>.
                </div>
                <div class="version">
                    <b>Version: </b> 0.1
                </div>
            </div>
            <!-- #Footer -->
        </aside>
        <!-- #END# Left Sidebar -->

    </section>

    <script type="text/javascript">
    function menuActive( id ){
        id = id.trim() ;
        console.log(id );
        console.log( document.getElementById( id.trim() ) );
        // var a =document.getElementById("menu").children[num-1].className="active";
        var a = document.getElementById( id.trim() );
        console.log( a.parentNode.parentNode );
        a.parentNode.parentNode.classList.add("active");
        a.parentNode.style.display = "block";
        console.log( a.parentNode.parentNode );
        document.getElementById( id ).classList.add("active");

    }
</script>
