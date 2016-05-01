<!-- jQuery -->
<script src="<?=base_url()?>resources/jquery/jquery-1.11.1.min.js"></script>
<!-- Bootstrap -->
<script src="<?=base_url()?>resources/bootstrap/js/bootstrap.min.js"></script>
<!-- Map Script -->
<script>

// 
// Constants
// 

// Set World
var world_key = <?php echo $world['id']; ?>;
var land_size = <?php echo $world['land_size'] ?>;

// Set user variables
<?php if ($log_check) { ?>
    var log_check = true;
    var user_id = <?php echo $user_id + ''; ?>;
    var account_id = <?php echo $account['id'] + ''; ?>;
    var username = "<?php echo $user['username']; ?>";
    var account_color = '<?php echo $account["color"]; ?>';
    var active_army = <?php echo $account['active_army'] + ''; ?>;
<?php } else { ?>
    var log_check = false;
<?php } ?>

// Set maps variables
var update_interval = 120;
if (document.location.hostname == "localhost") {
  var update_interval = 10;
}
var infoWindow = false;
var boxes = [];

// Start initMap callback called from google maps script
function initMap() 
{
  // 
  // Map options
  // 

  var map = new google.maps.Map(document.getElementById('map'), {
      // Zoom on land if set as parameter
      <?php if ( isset($_GET['land']) ) { 
        $land_coords_split = explode(',', $_GET['land']); ?>

        // Logic to center isn't  understand, but results in correct behavior in all 4 corners
        center: {lat: <?php echo $land_coords_split[0] + ($world['land_size'] / 2); ?>, lng: <?php echo $land_coords_split[1] - ($world['land_size'] / 2); ?>},

        // Zoom should be adjusted based on box size
        zoom: 7,
      <?php } else { ?>

      // Map center is slightly north centric
      center: {lat: 20, lng: 0},
      // Zoom shows whole world but no repetition
      zoom: 3,
      <?php } ?>
      // Prevent seeing more than needed
      minZoom: 3,
      // Prevent excesssive zoom
      maxZoom: 10,
      // Map type
      mapTypeId: google.maps.MapTypeId.TERRAIN
      // mapTypeId: google.maps.MapTypeId.HYBRID
      // mapTypeId: google.maps.MapTypeId.SATELLITE
  });

	// 
	// Minor Functions
	// 

  // For rounding land coords
  function round_down(n) {
    if (n > 0) {
          return Math.ceil(n/land_size) * land_size;
    }
      else if ( n < 0) {return Math.ceil(n/land_size) * land_size;
      }
      else {
          return 0;
      }
  }

  // Uppercase words
  function ucwords (str) {
      return (str + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
          return $1.toUpperCase();
      });
  }

  // For number formatting
  function number_format(nStr) {
      if (!nStr) {
        return 0;
      }
      nStr += '';
      x = nStr.split('.');
      x1 = x[0];
      x2 = x.length > 1 ? '.' + x[1] : '';
      var rgx = /(\d+)(\d{3})/;
      while (rgx.test(x1)) {
              x1 = x1.replace(rgx, '$1' + ',' + '$2');
      }
      return x1 + x2;
  }

  // Get single land ajax
  function get_single_land(coord_slug, world_key, callback) {
    $.ajax({
      url: "<?=base_url()?>get_single_land",
      type: "GET",
      data: { 
                coord_slug: coord_slug,
                world_key: world_key 
            },
      cache: false,
      success: function(data)
      {
        // console.log(data)
        callback(data);
        return true;
      }
    });
  }

  // Declare square called by performance sensitive loop
  function declare_square(land_key, land_lat, land_lng, stroke_weight, stroke_color, fill_color, fill_opacity) {
      shape = [
          {lat: land_lat, lng: land_lng},
          {lat: land_lat + land_size, lng: land_lng},
          {lat: land_lat + land_size, lng: land_lng - land_size},
          {lat: land_lat, lng: land_lng - land_size}
      ];
      box = new google.maps.Polygon({
        map: map,
        paths: shape,
        strokeWeight: stroke_weight,
        strokeColor: stroke_color,
        fillColor: fill_color,
        fillOpacity: fill_opacity,
      });
      box.setMap(map);
      box.addListener('click', set_window);
      boxes[land_key] = box;
  }

	// Set land window
	function set_window(event) {
  	// Set Parameters
    // Not sure why subtracting land_size on lat makes this work, but results in correct behavior
		var lat = round_down(event.latLng.lat()) - land_size;
		var lng = round_down(event.latLng.lng());
		var coord_slug = lat + ',' + lng;
    // console.log(event.latLng.lat() + ',' + event.latLng.lng());

    // 
		// Create land infoWindow
    // 

		land = get_single_land(coord_slug, world_key, function(land){
      // Get land
      // console.log(land);
  		land_data = JSON.parse(land);
      // Handle error
      if (land_data['error']) {
        alert(land_data['error']);
        return false;
      }
      // Create string
      var window_string = '<div class="land_window">';

      // Unclaimed land
			if (land_data['claimed'] === '0') {
        // Land name
				window_string += '<strong class="land_name">Unclaimed</strong><br>';
        // Coord
        window_string += 'Coord: <strong class="pull-right"><a href="<?=base_url()?>world/' + world_key + '?land=' + coord_slug + '">' + coord_slug + '</a></strong><br>';

      // Claimed land
			} else  {
        // Calculate income
        income_prefix = '';
        income_class = 'green_money';
        if (income < 0) {
          income_prefix = '-';
          income_class = 'red_money';
          income = Math.abs(income);
        }

        // Land name
        if (land_data['land_name'] != '') {
          window_string += '<strong class="land_name">' + land_data['land_name'] + '</strong><br>';
        }
        window_string += '<div class="land_info">';
        // Content
        if (land_data['content'] != '') {
          window_string += '<div class="land_content_div">' + land_data['content'] + '</div><br>';
        }
        // Owner
        window_string += 'Owner: <strong class="pull-right">' + land_data['username'] + '</strong><br>';
        // Coord
        window_string += 'Coord: <strong class="pull-right"><a href="<?=base_url()?>world/' + world_key + '?land=' + coord_slug + '">' + coord_slug + '</a></strong><br>';
        // Income
        window_string += 'Income: <strong class="' + income_class + ' pull-right">'  + income_prefix + '$' + number_format(income) + '/Minute</strong><br>';
        window_string += 'Price: <strong class="pull-right">$' + number_format(land_data['price']) + '</strong><br>';
        // Declare if City
        // if (land_data['city'] == 1) {
        //   window_string += 'City <span class="glyphicon glyphicon-home" aria-hidden="true"></span>: '
        //   + '<strong class="green_money pull-right">$1000/Minute</strong><br>';
        // }
        window_string += '</div>';
			}

      // Unregistered users
      if (! log_check) {
        if (land_data['claimed'] === '0') {
          window_string += '<a class="register_to_play btn btn-default" href="<?=base_url()?>world/' + world_key 
          + '?register">Join to Claim!</a><br>';
        } else {
          window_string += '<a class="register_to_play btn btn-default" href="<?=base_url()?>world/' + world_key 
          + '?register">Join to Buy! (' + number_format(land_data['price']) + ')</a><br>';
        }
      }

      // Interaction buttons
			if (log_check) {
        // Claim
				if (land_data['claimed'] === '0') {
					window_string += land_trade_form('claim', 'btn-action', land_data);
        // Update
				} else if (land_data['account_key'] == account_id) {
					window_string += land_trade_form('update', 'btn-info', land_data);
        // Buy
				} else {
          // Enough cash to buy
					if (land_data['price'] <= cash) {
            window_string += land_trade_form('buy', 'btn-success', land_data);
          // Not enough cash
					} else {
						window_string += '<button class="btn btn-default" disabled="disabled">Not enough cash (' + number_format(land_data['price']) + ')</button>';
					}
				}
      }
      // debug coord_slug
      // window_string += 'Coord Key: ' + land_data['coord_slug'] + ' | ' + coord_slug +
            // '<br>Clicked location: <br>' + event.latLng.lat() + ',' + event.latLng.lng() + '<br>';
      // End div
      window_string += '</div>';

      // 
      // Set InfoWindow Interaction
      // 

      // Close window if one is open
      if (infoWindow) {
          infoWindow.close();
      }
      // Set new infoWindow
      infoWindow = new google.maps.InfoWindow;
      infoWindow.setContent(window_string);
      infoWindow.setPosition(event.latLng);
      infoWindow.open(map);

      // 
      // infoWindow script
      // 

      google.maps.event.addListener(infoWindow,'domready',function(){
        // When expanding form, hide expand button and Focus on land name, with timeout to prevent collapse conflict
        $('.expand_land_form').click(function(){
          $('.expand_land_form').hide();
          $('.land_info').hide();
          setTimeout(function(){
            $('#input_land_name').focus();
          }, 200);
        });

        // 
        // Submit land form ajax
        // 
        $('#submit_land_form').click(function() {

          // Serialize form into post data
          var input_form_type = $('#input_form_type').val();
          var post_data = $('#land_form').serialize();

          // Replace window with processing window
          $('#land_form').html('<br><div class="alert alert-wide alert-green"><strong>Success</strong></div>');

          // Submit form
          $.ajax({
            url: "<?=base_url()?>land_form",
            type: "POST",
            data: post_data,
            cache: false,
            success: function(data)
            {
              // Return data
              response = JSON.parse(data);

              if (response['error']) {
                $('#land_form').html('<br><div class="alert alert-wide alert-danger"><strong>' + response['error'] + '</strong></div>');
                return false;
              }

              // If success, close
              if (response['status'] === 'success') {
                infoWindow.close();

                if (input_form_type != 'update') {
                  // Update player variables and displays
                  cash = cash - land_data['price'];
                  $('#cash_display').html(number_format(cash));
                  player_land_count = player_land_count + 1;
                  $('#player_land_count_display').html( number_format(player_land_count) );
                  $('#player_land_mi_display').html( number_format(player_land_count * (land_size * 70) ) );
                  $('#player_land_km_display').html( number_format(player_land_count * (land_size * 112) ) );

                  // Update box to reflect user ownership
                  boxes[land_data['id']].setOptions({
                    strokeWeight: 5, 
                    strokeColor: '#428BCA',
                    fillColor: account_color,
                    fillOpacity: 0.4
                  });
                  return true;
                }

              // If error, display error message
              } else {
                console.log(response);
                $('#land_form').html('<br><div class="alert alert-wide alert-danger"><strong>' + response['message'] + '</strong></div>');
                return false;
              }
            }
          });
        }); // End land form ajax

      }); // End infoWindow script domready listener

    }); // End get_single_land callback

	} // End set_window

	// For claiming, updating, and buying land forms
	function land_trade_form(form_type, button_class, d) {
		result = '<div class="form_outer_cont"><form id="land_form' + '" action="<?=base_url()?>land_form" method="post">'
    + '<button class="expand_land_form expand_trade btn ' + button_class + '" type="button" '
		+ 'data-toggle="collapse" data-target="#land_form_dropdown" aria-expanded="false" aria-controls="land_form_dropdown">'
		  + '' + ucwords(form_type) + ' This Land';
      if (form_type != 'update') {
        result += ' ($' + number_format(d['price']) + ')';
      }
		  result += ' <span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span></button><br><br>'
		    + '<div id="land_form_dropdown" class="collapse">'
          + '<div class="form-group">'
            + '<input type="hidden" id="input_form_type" name="form_type_input" value="' + form_type + '">'
            + '<input type="hidden" id="input_world_key" name="world_key_input" value="' + world_key + '">'
            + '<input type="hidden" id="input_id" name="id_input" value="' + d['id'] + '">'
            + '<input type="hidden" id="input_coord_slug" name="coord_slug_input" value="' + d['coord_slug'] + '">'
            + '<input type="hidden" id="token" name="token" value="' + d['token'] + '">'
            + '<div class="row"><div class="col-md-3">'
            + '<label for="input_land_name">Land Name</label>'
            + '</div><div class="col-md-8">'
            + '<input type="text" class="form-control" id="input_land_name" name="land_name" placeholder="Land Name" value="' + d['land_name'] + '">'
            + '</div></div>'
            + '<div class="row"><div class="col-md-3">'
            + '<label for="input_price">Land Price</label>'
            + '</div><div class="col-md-8">'
            + '<input type="text" class="auto_money form-control" id="input_price" name="price" value="$' + number_format(d['price']) + '">'
            + '</div></div>'
            + '<div class="row"><div class="col-md-3">'
            + '<label for="input_content">Description</label>'
            + '</div><div class="col-md-8">'
            + '<textarea class="form-control" id="input_content" name="content" placeholder="Description">' + d['content'] + '</textarea>'
            + '</div></div>'
          + '</div>';
          if (form_type === 'update' && cash > 1000) {
            result += '<div class="row"><div class="col-md-3"></div><div class="col-md-8">'
            + '<div id="auction_submit" class="btn btn-danger form-control">'
            + '<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> Auction ($1,000 Fee)</div>'
            + '</div></div>';
          }
          if (form_type === 'update' && cash > 100000 && d['city'] == 0) {
            // result += '<div class="row"><div class="col-md-3"></div><div class="col-md-8">'
            // + '<div id="upgrade_city_submit" class="btn btn-action form-control">'
            // + '<span class="glyphicon glyphicon-home" aria-hidden="true"></span> City ($100,000 Fee)</div>'
            // + '</div></div>'
          } else if (form_type === 'update' && cash <= 100000 && d['city'] == 0) {
            // result += '<div class="row"><div class="col-md-3"></div><div class="col-md-8">'
            // + '<div id="upgrade_city_submit" class="disabled btn btn-default form-control">'
            // + '<span class="glyphicon glyphicon-home" aria-hidden="true"></span> Make a City/$100,000</div>'
            // + '</div></div>'
          }
          result += '<br><button type="button" id="submit_land_form" class="btn btn-primary form-control">' + ucwords(form_type) + '</button>';
		result += '</div></form></div>';
		return result;
	}

	// 
	// Land loop
	// 

	<?php // This foreach loop runs between 400 to 15,000 times, so it's as dry as possible here, no comments
    foreach ($lands as $land) { 
        $stroke_weight = 0.2; 
        $stroke_color = '#222222';
        $fill_color = "#FFFFFF";
        $fill_opacity = '0';
        if ($log_check && $land['account_key'] === $account['id']) { 
            $stroke_weight = 5; 
            $stroke_color = '#428BCA';
        }
        if ($land['claimed']) {
          $fill_color = $land['color'];
          $fill_opacity = '0.4';
        }
        ?>
        declare_square(<?php echo 
            $land['id'] . ',' .
            $land['lat'] . ',' .
            $land['lng'] . ',' .
            $stroke_weight . ',' .
            '"' . $stroke_color . '"' . ',' .
            '"' . $fill_color . '"' . ',' .
            $fill_opacity; ?>);
	<?php } ?>

	// 
	// Map Styling
	// 

	// Styling of map
	var styles = [
	  {
  		featureType: "poi.business",
  		elementType: "labels",
  		stylers: [
  		  { visibility: "off" }
  		]
	  }
	];

  // Apply map styling
	var styled_map = new google.maps.StyledMapType(styles,
	  {name: "Styled Map"});
	map.mapTypes.set('map_style', styled_map);
	map.setMapTypeId('map_style');

  // 
  // Update data
  // 

  // Get update
  setInterval(function(){
    get_update_data(world_key);
  }, update_interval * 1000);

  // Get single land ajax
  function get_update_data(world_key) {
    $.ajax({
      url: "<?=base_url()?>world/" + world_key,
      type: "GET",
      data: { 
                json: "true"
            },
      cache: false,
      success: function(data)
      {
        // console.log(data);
        data = JSON.parse(data);

        // Check for refresh signal from server 
        if (data['refresh']) {
          alert('The game is being updated, and we need to refresh your screen. This page will refresh after you press ok');
          window.location.reload();
        }

        update_lands(data['lands']);
        update_leaderboards(data['leaderboards']);

        console.log('update');

      }
    });
  }

  function update_lands(lands) {
    // Loop through lands
    // This loop may run as many as 15,000 times, so be performant
    number_of_lands = lands.length;
    for (i = 0; i < number_of_lands; i++) {

      // Set variables
      land = lands[i];
      stroke_weight = 0.2; 
      stroke_color = '#222222';
      fill_color = "#0000ff";
      fill_opacity = 0;
      if (log_check && land['account_key'] == account_id) {
        stroke_weight = 5;
        stroke_color = '#428BCA';
      }
      if (land['claimed'] == 1) {
        fill_color = land['color'];
        fill_opacity = 0.4;
      }

      // Apply variables to box
      boxes[land['id']].setOptions({
        strokeWeight: stroke_weight, 
        strokeColor: stroke_color,
        fillColor: fill_color,
        fillOpacity: fill_opacity
      });

    }

    return true;
  }

  function update_leaderboards(leaderboards) {
    // Set leaderboards
    leaderboard_land_owned = leaderboards['leaderboard_land_owned'];
    // Empty current leaderboards
    $('#leaderboard_land_owned_table').find('tr:gt(0)').remove();

    // 
    // Add updated rows to leaderboards
    // 

    // leaderboard_land_owned
    $.each(leaderboard_land_owned, function(index, leader) {
      var table_string = '<tr><td>' + leader['rank'] + '</td>'
            + '<td><span class="glyphicon glyphicon-user" aria-hidden="true" style="color: ' + leader['color'] + '"></span>'
            + '' + leader['user']['username'] + ' </td>'
            + '<td>' + leader['COUNT(*)'] + '</td>'
            + '<td>' + leader['land_mi'] + ' Mi&sup2; | ' + leader['land_km'] + ' KM&sup2;</td></tr>';

      // Add string to table
      $('#leaderboard_land_owned_table tr:last').after(table_string);
    });

    return true;
  }

  // 
  // Remove overlay
  // 

  // Remove loading overlay based on tiles loaded status
  google.maps.event.addListenerOnce(map, 'tilesloaded', function(){
      $('#overlay').fadeOut();
  });
  // Remove loading overlay based on idle status
  // google.maps.event.addListenerOnce(map, 'idle', function(){
  // });
}

</script>