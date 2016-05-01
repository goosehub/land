<!-- Top Right Block -->
<div id="top_right_block">

  <?php if ($log_check) { ?>
  <!-- Army Dropdown -->
    <button id="active_army_dropdown" class="btn btn-default" type="button" id="active_army_dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
        <span id="active_army_display"><?php echo number_format($account['active_army']); ?></span>
      <span class="caret"></span>
    </button>
    <ul class="active_army_dropdown dropdown-menu" aria-labelledby="active_army_dropdown">

    </ul>

    <?php } ?>

    <!-- Leaderboards dropdown -->
    <div class="btn-group">
      <button class="info_button btn btn-primary dropdown-toggle" type="button" id="leaderboard_dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
          Leaderboards
        <span class="caret"></span>
      </button>
      <ul class="dropdown-menu" aria-labelledby="leaderboard_dropdown">
        <li class="text-center"><strong class="text-primary">Leaderboards</strong></li>
        <li><a class="leaderboard_land_owned_button leaderboard_link text-right">Land</a></li>
      </ul>
    </div>

    <?php if ($log_check) { ?>

    <!-- User Dropdown -->
    <div class="btn-group">
        <button class="user_button btn btn-success dropdown-toggle" type="button" id="user_dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            <?php echo $user['username']; ?>
          <span class="caret"></span>
        </button>
        <ul class="dropdown-menu" aria-labelledby="user_dropdown">
          <li class="text-center"><strong class="text-success">Joined <?php echo date('M jS Y', strtotime($user['created']) ); ?></strong></li>
          <li role="separator" class="divider"></li>
          <li><a class="logout_button btn btn-danger" href="<?=base_url()?>user/logout">Log Out</a></li>
          <li role="separator" class="divider"></li>
          <li>
              <?php echo form_open('user/update_color'); ?>
              <div class="row"><div class="col-md-3">
                  <label for="_input_color">Color</label>
              </div><div class="col-md-9">
                  <input type="hidden" name="world_key_input" value="<?php echo $world['id']; ?>">
                  <input class="jscolor color_input form-control" id="account_input_color" name="color" 
                  value="<?php echo $account['color']; ?>" onchange="this.form.submit()">
              </div></div>
              </form>
          </li>
        </ul>
    </div>
    <?php } else { ?>
    <button class="login_button btn btn-primary">Login</button>
    <button class="register_button btn btn-action">Join</button>
    <?php } ?>

  <!-- Main Menu dropdown -->
  <div class="btn-group">
    <button class="info_button btn btn-default dropdown-toggle" type="button" id="site_dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
        LandGrab
      <span class="caret"></span>
    </button>
    <ul class="landgrab_menu dropdown-menu" aria-labelledby="site_dropdown">
      <li class="text-center"><strong class="text-danger">Version 2.0.0</strong></li>
      <li role="separator" class="divider"></li>
      <li><a class="how_to_play_button btn btn-warning">How To Play</a></li>
      <li><a class="about_button btn btn-info">About LandGrab</a></li>
      <li><a class="report_bugs_button btn btn-danger">Report Bugs</a></li>
    </ul>
  </div>

</div>