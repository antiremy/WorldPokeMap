<?php require "login/loginheader.php"; ?>

<!DOCTYPE html>
<html>
<head>
  <script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
ga('create', 'UA-60539517-2', 'auto');
ga('send', 'pageview');
</script>
    <title>WorldPokéMap Pro</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <link rel="apple-touch-icon" sizes="57x57" href="appIcons/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="appIcons/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="appIcons/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="appIcons/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="appIcons/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="appIcons/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="appIcons/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="appIcons/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="appIcons/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="appIcons/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="appIcons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="appIcons/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="appIcons/favicon-16x16.png">
    <link rel="manifest" href="appIcons/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="appIcons/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <link rel="stylesheet" href="css/leaflet.css" />
    <link rel="stylesheet" href="css/marker-cluster-default.css" />
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/bootstrap-theme.min.css" />
	  <link rel="stylesheet" href="css/main.css" />
    <link rel="stylesheet" href="css/leaflet-sidebar.min.css" />
    <link rel="stylesheet" href="css/leaflet-gplaces-autocomplete.css" />
    <link rel="stylesheet" href="css/select2.min.css" />


    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <!--[if lte IE 8]>
        <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.7.1/leaflet.ie.css" />
    <![endif]-->
    <style>
        html, body, #map{ height: 100%; padding: 0; margin: 0;}
		   .sidebar {
            width: 300px;
        }

		#window {
    color: black !important;
    display: block;
    position: absolute;
    background: rgba(255,255,255,0.93);
	  border: 1px solid #a0a0a0;
    border-radius: 10px;
    width: 280px;
    height: 280px;
    top: 50%;
    margin-top: -120px;
    left: 50%;
    margin-left: -150px;
    z-index: 2000;
    padding: 10px;
    text-align: center;
    overflow: auto;
    }
    .sidebar.collapsed {
        height: 200px;
    	  margin-top: 50px;
    }
    .sidebar-tabs>ul>li>a {
        margin-left: -4px !important;
    	}
    .sidebar-left .sidebar-tabs {
        height: 350px;
    	}
  	.sidebar-left .sidebar-content {
  	   height: 350px;
  	}
    .sidebar-left {
      height:200px;
    }
    .sidebar {
      height: 350px;
    }
    @media only screen and (max-device-width: 480px) {
      #pac-input {
        margin-top:-10px
      }
    }
    @media only screen and (min-device-width: 480px) {
      .sidebar {
        margin-top: 50px !important;
      }
      #pac-input {
        margin-top:5px;
      }
      .leaflet-control-zoom {
        margin-bottom: 50px !important;
      }
      #header > center {
        display:none;
      }
    }
    p {
        margin: 0 0 .5em 0 !important;
    }
    #header > h3 {
    color: #191919 !important;}
    .select2-dropdown {
      z-index: 9999;
    }
    .select2 {
      width: 200px !important
    }

    </style>
    <script src="lib/jquery-3.1.1.min.js"></script>
    <script src="lib/moment.js"></script>
    <script src="lib/select2.full.min.js"></script>
    <script src="lib/leaflet.js"></script>
    <script src="lib/ngeohash.js"></script>
    <script src="lib/leaflet.markercluster.js"></script>
    <script src="lib/bootstrap.min.js"></script>
    <script src="lib/leaflet-sidebar.min.js"></script>
    <script src="lib/leaflet-gplaces-autocomplete.js"></script>
    <script src="lib/store.min.js"></script>
    <script src="lib/push.min.js"></script>
    <script src="lib/leaflet.smoothmarkerbouncing.js"></script>
    <!-- <script src="lib/bootstrap3-typeahead.min.js"></script>
    <script src="lib/bootstrap-tokenfield.min.js"></script> -->
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    <script>
    function initSearch() {
      var input = (document.getElementById('pac-input'));
      var autocomplete = new google.maps.places.Autocomplete(input);
      autocomplete.addListener('place_changed', function() {
        var place = autocomplete.getPlace();
        var latlng = L.latLng([place['geometry']['location'].lat(),place['geometry']['location'].lng()])
        map.setView(latlng,14)
        searchMarker.setLatLng(latlng)
        if (!map.hasLayer(searchMarker)) {
          searchMarker.addTo(map)
        }
        search()
      })
    }
    </script>

</head>
<body>
<header id="header">
       <!-- <div class="logo">PokéJoin </div> -->

<center><img src="images/RJLSantiremyV2.png" style="
    width: 60px;
    position: static;
	    margin-top: 10px;
"></center>
</div></div>
<div class="mobilehide1"><h1><a href="https://blog.worldpokemap.com/2016/11/14/add-your-area/" class="1">Add Your Area / City</a></h1></div>
<h3><i class="fa fa-star" aria-hidden="true"></i> <a href="https://worldpokemap.com/stats">Nests & Spawns</a></h3>
<h3><a href="https://forum.pokejoin.com/faq/"><i class="fa fa-question-circle" aria-hidden="true"></i> FAQ</a></h3>
<h3><a href="#">Welcome To Pro</a></h3>
<input id="pac-input" class="controls" type="text" placeholder="Enter a location">

      </header>
  <div id="sidebar" class="sidebar collapsed" style="margin-top:7.5em;">
     <!-- Nav tabs -->
     <div class="sidebar-tabs">
         <ul role="tablist">
             <li><a href="#options" role="tab"><i class="fa fa-gear"></i></a></li>
             <li><a href="javascript:;" id="location" role="tab" target="_blank"><i class="fa fa-location-arrow"></i></a></li>
             <li><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=CH2K3ZYYNFVLS" role="tab"><i class="fa fa-money"></i></a></li>
            <li><a href="https://worldpokemap.com/stats" role="tab"><i class="fa fa-line-chart"></i></a></li>
		 <li><a href="https://twitter.com/worldpokemap" role="tab" target="_blank"><i class="fa fa-twitter"></i></a></li>
			 <li><a href="https://www.facebook.com/PokeJoin/" role="tab" target="_blank"><i class="fa fa-facebook"></i></a></li>

         </ul>
     </div>

     <!-- Tab panes -->
     <div class="sidebar-content">
         <div class="sidebar-pane" id="options">
          <h1 class="sidebar-header">Settings<span class="sidebar-close"><i class="fa fa-caret-left"></i></span></h1>
          <div class="checkbox">
            <label>
              <input type="checkbox" data-toggle="toggle" id="commonToggle">
              Common
            </label>
          </div>
          <div class="checkbox">
            <label>
              <input type="checkbox" data-toggle="toggle" id="uncommonToggle">
              Uncommon
            </label>
          </div>
          <div class="checkbox">
            <label>
              <input type="checkbox" data-toggle="toggle" id="rareToggle">
              Rare
            </label>
          </div>
          <div class="checkbox">
            <label>
              <input type="checkbox" data-toggle="toggle" id="veryRareToggle">
              Very Rare
            </label>
          </div>
          <div class="checkbox">
            <label>
              <input type="checkbox" data-toggle="toggle" id="ultraRareToggle">
              Ultra Rare
            </label>
          </div>
          <div class="slider" style="width:100%;height:30px;">
            <label>
              <input type="range" min="10" max="65" value="40" step="1" id="iconSize">
              Icon Size: <span id="sizeVal">40</span>
            </label>
          </div>
          <br>
          <div class="form-group">
            <label>
              <span><input type="number" id="notifyIV" min="0" max="100" data-bind="value:notifyRarity" />%</span>
              Notify of IV
            </label>
          </div>
          <div class="form-group">
            <label>
              <select type="text" class="form-control" multiple="multiple" id="pokeNotify" value=""></select>
              <br>Notify of Pokemon
            </label>
          </div>
          <div class="form-group">
            <label>
              <select type="text" class="form-control" multiple="multiple" id="pokeHide" value=""></select>
              <br>Hide Pokemon
            </label>
          </div>

         </div>

         <div class="sidebar-pane" id="profile">
             <h1 class="sidebar-header">Profile<span class="sidebar-close"><i class="fa fa-caret-left"></i></span></h1>
             <br>
             <form action="login/index.php" method="post">
                 Username:<br />
                 <input type="text" name="username" value="" />
                 <br /><br />
                 Password:<br />
                 <input type="password" name="password" value="" />
                 <br /><br />
                 <input type="submit" class="btn btn-info" value="Login" />
                 <br><br>or
             </form>
             <br>
             <a href="login/register.php" role="button" class="btn btn-primary">Register</a>
         </div>

     </div>
   </div>
  <div id="window">
        <p style="
    /* color: #212121; */
    font-size: 14px;
    margin: 0px !important;
    font-weight: 400;"><p>Welcome to WorldPokeMap!<p>
        <p style="font-size: 13px;margin-top: -30px;"><b><a href="https://blog.worldpokemap.com/2016/11/14/add-your-area/">Click here for info about donating to get your area scanned.</a>
          <br>Support us by sharing our website or donating.
          <br>Captcha Funds:
          $<span class = "captchaAmount"></span></p></b><br>
        <hr style="margin-top: -20px;margin-bottom: 10px;">
       <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="4WRHQXD55HCJ6">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
        <button class="close" onclick="getElementById('window').style.visibility= 'hidden';" style="
    position: absolute;
    margin-top: -40px;
    margin-left: -15px;
    padding: 2.2px;
    color: #585858 !important;
    background-color: rgba(60, 60, 60, 0);
">
       <i class="fa fa-times-circle fa-2x" style="
    color: red;
"></i>
        </button>
    </div>
  <div id="map" class="sidebar-map"></div>
</div>
<div id="captchaStats">
  <b>Captcha Funds: $<span class = "captchaAmount"></span></b>
</div>
<script src="lib/promap.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDpCeRBeEpZ80e8uQDqHjwh7JlTz6cFQnY&libraries=places&callback=initSearch"></script>

</body>
