<header id="js-header" class="u-header u-header--static">
  <div class="u-header__section u-header__section--light g-bg-white g-transition-0_3 g-py-10">
    <nav class="js-mega-menu navbar navbar-expand-lg hs-menu-initialized hs-menu-horizontal">
      <div class="container">
        <!-- Responsive Toggle Button -->
        <button class="navbar-toggler navbar-toggler-right btn g-line-height-1 g-brd-none g-pa-0 g-pos-abs g-top-3 g-right-0" type="button" aria-label="Toggle navigation" aria-expanded="false" aria-controls="navBar" data-toggle="collapse" data-target="#navBar">
          <span class="hamburger hamburger--slider">
        <span class="hamburger-box">
          <span class="hamburger-inner"></span>
          </span>
          </span>
        </button>
        <!-- End Responsive Toggle Button -->

        <!-- Logo -->
        <a href="<?php echo base_url();?>" class="navbar-brand">
          <img src="<?php echo base_url().ICON_IMAGE;?>" alt="" width="86px" height="32px">
        </a>
        <a class="navbar-brand" href="<?php echo base_url()?>"><?php echo APP_NAME ?></a>
        <!-- End Logo -->

        <!-- Navigation -->
        <div class="collapse navbar-collapse align-items-center flex-sm-row g-pt-10 g-pt-5--lg g-mr-40--lg" id="navBar">
          <ul class="navbar-nav text-uppercase g-pos-rel g-font-weight-600 ml-auto">
            <?php if (!$this->session->userdata()): ?>
              <li class="nav-item  g-mx-10--lg g-mx-15--xl">
                <a href="<?php echo site_url('dashboard')?>" class="nav-link g-py-7 g-px-0">Dashboard</a>
              </li>
            <?php endif; ?>
            <?php
            $nav = array(

            );
            foreach ($nav as $key => $value) { ?>
              <li class="nav-item  g-mx-10--lg g-mx-15--xl">
                <a href="<?php echo site_url('homepage/?page='.$value)?>" class="nav-link g-py-7 g-px-0"><?php echo $key ?></a>
              </li>
            <?php } ?>
          </ul>
        </div>
        <!-- End Navigation -->
        <?php if ($this->session->identity==null): ?>
          <!-- <div class="d-inline-block g-hidden-xs-down g-pos-rel g-valign-middle g-pl-30 g-pl-0--lg">
            <a class="btn  g-font-size-13 text-uppercase g-py-10 g-px-15" href="<?php echo site_url('auth/register')?>">Register</a>
          </div> -->
          <div class="d-inline-block g-hidden-xs-down g-pos-rel g-valign-middle g-pl-30 g-pl-0--lg">
            <a class="btn g-font-size-13 u-btn-outline-pink  text-uppercase g-py-10 g-px-15" href="<?php echo site_url('auth/login')?>">Login</a>
          </div>
        <?php else: ?>
          <div class="d-inline-block g-hidden-xs-down g-pos-rel g-valign-middle g-pl-30 g-pl-0--lg">
            <a class="btn u-btn-outline-pink g-font-size-13 text-uppercase g-py-10 g-px-15" href="<?php echo site_url('admin')?>">Dashboard</a>
          </div>
        <?php endif; ?>

      </div>
    </nav>
  </div>
</header>
