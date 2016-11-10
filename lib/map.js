$('#location').on('click', function() {
  if ($('#location').parent('li').hasClass('active')) {
    $('#location').parent('li').removeClass('active')
  }
  else {
    getLocation()
    $('#location').parent('li').addClass('active')
  }


})
$('.leaflet-gac-control').change(function() {
  console.log('clicked!')
})
var pokeNames
$.getJSON('pokemon.json', function(data) {
  pokeNames = data
})

var commonToggle
var uncommonToggle
var rareToggle
var veryRareToggle
var ultraRareToggle

if (Cookies.get('common') == undefined) {
  Cookies.set('common', false)
  commonToggle = false
}
else {
  commonToggle = Cookies.get('common')
}
if (Cookies.get('uncommon') == undefined) {
  Cookies.set('uncommon', false)
  uncommonToggle = false
}
else {
  uncommonToggle = Cookies.get('uncommon')
}
if (Cookies.get('rare') == undefined) {
  $('#rareToggle').bootstrapToggle('on')
    Cookies.set('rare', true)
    rareToggle = true
}
else {
  rareToggle = Cookies.get('rare')
}
if (Cookies.get('veryrare') == undefined) {
  $('#veryRareToggle').bootstrapToggle('on')
  Cookies.set('veryrare', true)
  veryRareToggle = true
}
else {
  veryRareToggle = Cookies.get('veryrare')
}
if (Cookies.get('ultrare') == undefined) {
  $('#ultraRareToggle').bootstrapToggle('on')
  Cookies.set('ultrarare', true)
  ultraRareToggle = true
}
else {
  // ultraRareToggle = Cookies.get('ultrarare')
}

$('#commonToggle').change(function() {
  commonToggle = !commonToggle
  Cookies.set('common', commonToggle)
  common.clearLayers();
  search()
})
$('#uncommonToggle').change(function() {
  uncommonToggle = !uncommonToggle
  Cookies.set('uncommon', uncommonToggle)
  uncommon.clearLayers();
  search()
})
$('#rareToggle').change(function() {
  rareToggle = !rareToggle
  Cookies.set('rare', rareToggle)
  rare.clearLayers();
  search()
})
$('#veryRareToggle').change(function() {
  veryRareToggle = !veryRareToggle
  Cookies.set('veryrare', veryRareToggle)
  veryRare.clearLayers()
  search()
})
$('#ultraRareToggle').change(function() {
  ultraRareToggle = !ultraRareToggle
  Cookies.set('ultrarare', commonToggle)
  ultraRare.clearLayers();
  search()
})
//Notification.requestPermission()
map = L.map('map',{minZoom:3});
var mapboxTiles = L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map)
// var mapboxTiles = L.tileLayer('https://api.mapbox.com/styles/v1/mapbox/streets-v10/tiles/256/{z}/{x}/{y}?access_token=pk.eyJ1IjoiYW50aXJlbXkiLCJhIjoiY2lvZDEybHRjMDRnNXZna3FwbnU0YnRtayJ9.9HABMQncB67Fa8P7ibwTBA', {
// token: 'pk.eyJ1IjoiYW50aXJlbXkiLCJhIjoiY2lvZDEybHRjMDRnNXZna3FwbnU0YnRtayJ9.9HABMQncB67Fa8P7ibwTBA'
// }).addTo(map);
map.on('load', function(e){search()});
map.setView([36.50963615733049, -97.00927734375001], 5);
map.on('zoomend dragend',function(e){search();});
map.zoomControl.setPosition('bottomright');
new L.Control.GPlaceAutocomplete({
  position: "topright",
  callback: function(location) {
    map.setZoom(14)
    var latlng = L.latLng([location['geometry']['location'].lat(),location['geometry']['location'].lng()])
    map.panTo(latlng)
    search()
  }
})
.addTo(map);

window.setInterval(search, 2000)
var sidebar = L.control.sidebar('sidebar').addTo(map);
var common = new L.LayerGroup()
var uncommon = new L.LayerGroup()
var rare = new L.LayerGroup()
var veryRare = new L.LayerGroup()
var ultraRare = new L.LayerGroup()
var clusters = L.markerClusterGroup({
chunkedLoading: true,
spiderfyOnMaxZoom: false,
showCoverageOnHover: true,
iconCreateFunction: function(cluster) {
    //Grouping the cluster returned by the server, if
    var markers = cluster.getAllChildMarkers();
    var markerCount = 0;
    markers.forEach(function(m){markerCount = markerCount + m.count;});

    return new L.DivIcon({ html: '<div class=" clustergroup0 leaflet-marker-icon marker-cluster marker-cluster-medium leaflet-zoom-animated leaflet-clickable" tabindex="0" style="margin-left: -20px; margin-top: -20px; width: 40px; height: 40px; z-index: 233;"><div><span>'+markerCount+'</span></div></div>' });
    }
});

map.addLayer(clusters)
map.addLayer(common)
map.addLayer(uncommon)
map.addLayer(rare)
map.addLayer(veryRare)
map.addLayer(ultraRare)
/* This will add all the clusters as returned by the elastic server.*/
function getLocation() {
  if ("geolocation" in navigator) {
      navigator.geolocation.getCurrentPosition(showPosition);
  } else {
      console.log("Geolocation is not supported by this browser.");
  }
}
function showPosition(position) {
    map.setZoom(14)
    map.panTo([position.coords.latitude,position.coords.longitude]);
}
function makeClusters(aggs){
    var markerList = [];
    aggs.forEach(function(agg, index){
        var center = geohash.decode (agg.key);//elastic return a geohas so need to change it into lat/lon
        var myIcon = L.divIcon({ html: '<div class="clustergroup0 leaflet-marker-icon marker-cluster marker-cluster-medium leaflet-zoom-animated leaflet-clickable" tabindex="0" style="margin-left: -20px; margin-top: -20px; width: 40px; height: 40px; z-index: 233;"><div><span>'+agg.doc_count+'</span></div></div>' });
        var marker = L.marker(new L.LatLng(center.latitude, center.longitude),{icon:myIcon});
        marker.count = agg.doc_count;
        //marker.sentiment = agg.sentiment_avg.value;
        markerList.push(marker);
    });
    //console.log(markerList)
    clusters.addLayers(markerList);
}

function makePokemon(pokemon){
  var markerList = [];
  pokemon.forEach(function(poke,index) {
    var info = poke['_source']
    //console.log(info)
    var location = info['location']
    var pokeID = info['pokemon_id']
    var dt = info['disappearTime']
    var name = pokeNames[pokeID]['name']
    var icon = L.icon ({
      iconUrl: 'icons/' + pokeID +'.png',
      iconSize: [40,40]
    })
    var marker = L.marker([location['lat'],location['lon']],{icon: icon}).bindPopup('<strong>'+name+'</strong> - '+pokeNames[pokeID]['rarity']+'<br>Disappears at ' + dt)
    marker.on('mouseover', function (e) {
      this.openPopup();
    });
    marker.on('mouseout', function (e) {
      this.closePopup();
    });
    if (pokeNames[pokeID]['rarity'] == 'Common') {
      marker.addTo(common)
    }
    else if (pokeNames[pokeID]['rarity'] == 'Uncommon') {
      marker.addTo(uncommon)
    }
    else if (pokeNames[pokeID]['rarity'] == 'Rare') {
      marker.addTo(rare)
    }
    else if (pokeNames[pokeID]['rarity'] == 'Very Rare') {
      marker.addTo(veryRare)
    }
    else if (pokeNames[pokeID]['rarity'] == 'ultraRare') {
      marker.addTo(ultraRare)
    }
  })
}

function search() {
  var b = map.getBounds();
  var b1 = {
      "nwlat": b.getNorthWest().lat % 90,
      "nwlon": b.getNorthWest().lng % 180,
      "selat": b.getSouthEast().lat % 90,
      "selon": b.getSouthEast().lng % 180
  }
  //Get the zoom level
  var zoom = 3;
  if(map.getZoom() >= 5 && map.getZoom() <= 8){
      zoom =4;
  }
  else if(map.getZoom() >= 9 && map.getZoom() <= 11){
      zoom =5;
  }
  else if(map.getZoom() >= 12 && map.getZoom() <= 14){
      zoom =6;
  }
  else if(map.getZoom() >= 15 && map.getZoom() <= 17){
      zoom =7;
  }
  else if(map.getZoom() >= 18){
      zoom =8;
  }
  var dataString = "northWestLat=" +  b1.nwlat +
  "&northWestLng=" + b1.nwlon +
  "&southEastLat=" + b1.selat +
  "&southEastLng=" + b1.selon +
  "&zoom=" + zoom
  if (map.getZoom() < 14) {
    $.ajax({
      type : "POST",
      url : "clusterGrab.php",
      data: dataString,
      cache: false,
      success: function(resp) {
        //console.log(resp)
        if (resp.startsWith("Error:")) {
          console.log(resp)
        }
        else {
          clusters.clearLayers();
          common.clearLayers();
          uncommon.clearLayers();
          rare.clearLayers();
          veryRare.clearLayers();
          ultraRare.clearLayers();
          var result = JSON.parse(resp)
          makeClusters(result.aggregations.zoom.buckets)
        }
      }
    })
  }
  else {
    if (commonToggle == true) {
      $.ajax({
        type : "POST",
        url : "pokeGrabCommon.php",
        data: dataString,
        cache: false,
        success: function(resp) {
          clusters.clearLayers();
          common.clearLayers();
          var result = JSON.parse(resp)
          makePokemon(result['hits']['hits'])
        }
      })
    }
    if (uncommonToggle == true) {
      $.ajax({
        type : "POST",
        url : "pokeGrabUncommon.php",
        data: dataString,
        cache: false,
        success: function(resp) {
          clusters.clearLayers();
          uncommon.clearLayers();
          var result = JSON.parse(resp)
          makePokemon(result['hits']['hits'])
        }
      })
    }
    if (rareToggle == true) {
      $.ajax({
        type : "POST",
        url : "pokeGrabRare.php",
        data: dataString,
        cache: false,
        success: function(resp) {
          clusters.clearLayers();
          rare.clearLayers();
          var result = JSON.parse(resp)
          makePokemon(result['hits']['hits'])
        }
      })
    }
    if (veryRareToggle == true) {
      $.ajax({
        type : "POST",
        url : "pokeGrabVeryRare.php",
        data: dataString,
        cache: false,
        success: function(resp) {
          clusters.clearLayers();
          veryRare.clearLayers();
          var result = JSON.parse(resp)
          makePokemon(result['hits']['hits'])
        }
      })
    }
    if (ultraRareToggle == true) {
      $.ajax({
        type : "POST",
        url : "pokeGrabUltraRare.php",
        data: dataString,
        cache: false,
        success: function(resp) {
          clusters.clearLayers();
          ultraRare.clearLayers();
          var result = JSON.parse(resp)
          makePokemon(result['hits']['hits'])
        }
      })
    }
  }



}
