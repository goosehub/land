<!-- Top Right Block -->
<div id="top_right_block">

  <!-- Update Dropdown -->
<!--     <button class="update_info_button menu_element btn btn-danger">
        The ___ Update
        <span class="glyphicon glyphicon-asterisk"></span>
    </button> -->

  <div class="views_parent menu_element btn-group">
    <button class="info_button btn btn-default dropdown-toggle" type="button" id="views_dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
        Views
      <span class="caret"></span>
    </button>
    <ul class="landgrab_menu dropdown-menu" aria-labelledby="site_dropdown">
      <li class="text-center"><strong class="text-default" id="terrain_toggle"><a href="#">Terrain and Resources</a></strong></li>
      <li class="text-center"><strong class="text-default" id="borders_toggle"><a href="#">Borders and Cities</a></strong></li>
      <li class="text-center"><strong class="text-default" id="empty_toggle"><a href="#">Empty Map</a></strong></li>
    </ul>
    </ul>
  </div>

  <?php if ($account) { ?>
    <button id="diplomacy_dropdown" class="diplomacy_dropdown menu_element btn btn-default" type="button">
      Diplomacy <small class="text-primary">(3)</small>
      <span class="caret"></span>
    </button>
    <button id="government_dropdown" class="government_dropdown menu_element btn btn-default" type="button">
      Laws
      <span class="caret"></span>
    </button>
    <button id="mobile_government_dropdown" class="government_dropdown menu_element btn btn-action" type="button">
      Laws
    </button>
  <?php } ?>

    <!-- Leaderboards dropdown -->
    <div class="leaderboard_parent menu_element btn-group">
      <button class="info_button btn btn-primary dropdown-toggle" type="button" id="leaderboard_dropdown">
          Leaderboard
        <span class="caret"></span>
      </button>
    </div>

    <!-- worldss dropdown -->
    <div class="worlds_parent menu_element btn-group">
      <button class="info_button btn btn-info dropdown-toggle" type="button" id="worlds_dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
          Worlds
        <span class="caret"></span>
      </button>
      <ul class="landgrab_menu dropdown-menu" aria-labelledby="site_dropdown">
        <?php foreach ($worlds as $world) { ?>
        <li class="text-center"><a href="<?=base_url();?>world/<?php echo $world['id']; ?>"><strong class="text-default"><?php echo ucfirst($world['slug']); ?></strong></a></li>
        <?php } ?>
      </ul>
      </ul>
    </div>

    <?php if ($account) { ?>

    <!-- User Dropdown -->
    <div class="user_parent menu_element btn-group">
        <button class="user_button btn btn-success" type="button" id="user_dropdown">
            <?php echo $account['username']; ?>
          <span class="caret"></span>
        </button>
    </div>
    <?php } else { ?>
    <button class="login_button menu_element btn btn-primary">Login</button>
    <button class="register_button menu_element btn btn-action">Join</button>
    <?php } ?>

  <!-- Main Menu dropdown -->
  <div class="main_menu_parent menu_element btn-group">
    <button class="info_button btn btn-default dropdown-toggle" type="button" id="site_dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
        LandGrab
      <span class="caret"></span>
    </button>
    <ul class="landgrab_menu dropdown-menu" aria-labelledby="site_dropdown">
      <li class="text-center"><strong class="text-danger">Version 5.0.0</strong></li>
      <li role="separator" class="divider"></li>
      <!-- <li><a class="how_to_play_button btn btn-warning">How To Play</a></li> -->
      <li><a class="about_button btn btn-info">About LandGrab</a></li>
      <!-- <li><a class="faq_button btn btn-primary">FAQ</a></li> -->
      <li><a class="btn btn-primary" href="https://www.reddit.com/r/Landgrab/" target="_blank">/r/Landgrab</a></li>
      <!-- <li><a class="btn btn-success" href="http://gleamplay.com/" target="_blank">GleamPlay</a></li> -->
      <li><a class="btn btn-success" href="https://github.com/goosehub/landgrab" target="_blank">GitHub</a></li>
      <li><a class="btn btn-success" href="https://gooseweb.io/" target="_blank">GooseWeb</a></li>
      <li><a class="report_bugs_button btn btn-warning">Report Bugs</a></li>
      <li><a class="update_password_button btn btn-danger">Update Password</a></li>
      <li><a class="logout_button btn btn-danger" href="<?=base_url()?>user/logout">Logout</a></li>
      <li><small>Get your friends playing</small><div class="fb-like" data-href="https://landgrab.xyz/" data-layout="button" data-="recommend" data-show-faces="false" data-share="true"></div></li>
    </ul>
  </div>

</div>