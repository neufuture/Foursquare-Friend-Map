<?php
$count		= 10;			// how many of most recent checkins are displayed
$iconSize	= 25;			// width and height in pixels of images on map
$auth_token = "";			// this key you get from foursquare https://foursquare.com/oauth/register
$lat		= 40.7064;		// latitude where you want to center map
$lon		= -73.9191;		// longitude where you want to center map
$zoom		= 12;			// Zoom factor of map. Larger number is more zoomed in.
$width		= 970;			// width of map in pixels
$height		= 510;			// height of map in pixels

$json = file_get_contents("https://api.foursquare.com/v2/checkins/recent?limit=" .  $count . "&oauth_token=" . $auth_token);
$fullJson = json_decode($json, true);
$friends = $fullJson[response][recent];
?>
  function initialize() {
    var myOptions = {
      zoom: <?php echo $zoom ?>,
      center: new google.maps.LatLng(<?php echo $lat . "," . $lon; ?>),
      mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
	var info = new google.maps.InfoWindow();
 
    <?php foreach($friends as $value): if($value[venue][location][lat]):?>
    <?php
    // PHP reference for time http://us.php.net/manual/en/datetime.createfromformat.php
       	if(date("ymd", $value[createdAt]) == date("ymd", time())) $day = "today";
    	else $day = "on " . date('l', $value[createdAt]);
    	$time = date('ga', $value[createdAt]);
    ?>
    
    // Set the venue lat/lon
	var l<?php echo $value[user][id];?> = new google.maps.LatLng(<?php echo $value[venue][location][lat];?>, <?php echo $value[venue][location][lng];?>)

	// Create the friend marker on the map
    var m<?php echo $value[user][id];?> = new google.maps.Marker({
        position: l<?php echo $value[user][id];?>,
        map: map,
        icon: new google.maps.MarkerImage('<?php echo $value[user][photo];?>',
      new google.maps.Size(<?php echo $iconSize . "," . $iconSize; ?>),
      new google.maps.Point(0,0),
      new google.maps.Point(0, 0),
      new google.maps.Size(<?php echo $iconSize . "," . $iconSize; ?>)),
      title: "<?php echo $value[user][firstName] . " " . $value[user][lastName];?>"
      
    });
    
    // Create the info window data
    var c<?php echo $value[user][id];?> = "<div class='info'><img src='<?php echo $value[user][photo];?>' alt='' /><?php echo $value[user][firstName]; ?> checked in at <?php echo $value[venue][name];?> <?php echo $day;?> around <?php echo $time; ?></div>";
    
    google.maps.event.addListener(m<?php echo $value[user][id];?>, 'click', function() {
   		info.setContent(c<?php echo $value[user][id];?>);
   		info.getPosition(l<?php echo $value[user][id];?>);
		info.open(map,m<?php echo $value[user][id];?>);
		
	});
    
    <?php endif; endforeach; ?>
    
  }