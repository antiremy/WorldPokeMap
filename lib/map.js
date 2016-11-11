var commonMarkers = []
var uncommonMarkers = []
var rareMarkers = []
var veryRareMarkers = []
var ultraRareMarkers = []
var commonID = []
var uncommonID = []
var rareID = []
var veryRareID = []
var ultraRareID = []
var notifiedPokemon = []
var commonToggle
var uncommonToggle
var rareToggle
var veryRareToggle
var ultraRareToggle
var hidePokemon = []
var notifyIV
var pokemonNames = ["Bulbasaur","Ivysaur","Venusaur","Charmander","Charmeleon","Charizard","Squirtle","Wartortle","Blastoise","Caterpie","Metapod","Butterfree","Weedle","Kakuna","Beedrill","Pidgey","Pidgeotto","Pidgeot","Rattata","Raticate","Spearow","Fearow","Ekans","Arbok","Pikachu","Raichu","Sandshrew","Sandslash","Nidoran♀","Nidorina","Nidoqueen","Nidoran♂","Nidorino","Nidoking","Clefairy","Clefable","Vulpix","Ninetales","Jigglypuff","Wigglytuff","Zubat","Golbat","Oddish","Gloom","Vileplume","Paras","Parasect","Venonat","Venomoth","Diglett","Dugtrio","Meowth","Persian","Psyduck","Golduck","Mankey","Primeape","Growlithe","Arcanine","Poliwag","Poliwhirl","Poliwrath","Abra","Kadabra","Alakazam","Machop","Machoke","Machamp","Bellsprout","Weepinbell","Victreebel","Tentacool","Tentacruel","Geodude","Graveler","Golem","Ponyta","Rapidash","Slowpoke","Slowbro","Magnemite","Magneton","Farfetch'd","Doduo","Dodrio","Seel","Dewgong","Grimer","Muk","Shellder","Cloyster","Gastly","Haunter","Gengar","Onix","Drowzee","Hypno","Krabby","Kingler","Voltorb","Electrode","Exeggcute","Exeggutor","Cubone","Marowak","Hitmonlee","Hitmonchan","Lickitung","Koffing","Weezing","Rhyhorn","Rhydon","Chansey","Tangela","Kangaskhan","Horsea","Seadra","Goldeen","Seaking","Staryu","Starmie","Mr. Mime","Scyther","Jynx","Electabuzz","Magmar","Pinsir","Tauros","Magikarp","Gyarados","Lapras","Ditto","Eevee","Vaporeon","Jolteon","Flareon","Porygon","Omanyte","Omastar","Kabuto","Kabutops","Aerodactyl","Snorlax","Articuno","Zapdos","Moltres","Dratini","Dragonair","Dragonite","Mewtwo","Mew"]

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


if (store.get('notifyIV') == undefined) {
  store.set('notifyIV', "")
}
else {
  notifyIV = store.get('notifyIV')
  $("#notifyIV").val(notifyIV)
}
if (store.get('hidePokemon') == undefined) {
  store.set('hidePokemon', hidePokemon)
}
else {
  hidePokemon = store.get('hidePokemon')
}

if (store.get('common') == undefined) {
  store.set('common', false)
  commonToggle = false
}
else {
  commonToggle = Boolean(store.get('common'))
  if (commonToggle) {
    $('#commonToggle').bootstrapToggle('on')
  }
}
if (store.get('uncommon') == undefined) {
  store.set('uncommon', false)
  uncommonToggle = false
}
else {
  uncommonToggle = Boolean(store.get('uncommon'))
  if (uncommonToggle) {
    $('#uncommonToggle').bootstrapToggle('on')
  }
}
if (store.get('rare') == undefined) {
  $('#rareToggle').bootstrapToggle('on')
  store.set('rare', true)
  rareToggle = true
}
else {
  rareToggle = Boolean(store.get('rare'))
  if (rareToggle) {
    $('#rareToggle').bootstrapToggle('on')
  }
}
if (store.get('veryrare') == undefined) {
  $('#veryRareToggle').bootstrapToggle('on')
  store.set('veryrare', true)
  veryRareToggle = true
}
else {
  veryRareToggle = Boolean(store.get('veryrare'))
  if (veryRareToggle) {
    $('#veryRareToggle').bootstrapToggle('on')
  }
}
if (store.get('ultrare') == undefined) {
  $('#ultraRareToggle').bootstrapToggle('on')
  store.set('ultrarare', true)
  ultraRareToggle = true
}
else {
  ultraRareToggle = Boolean(store.get('ultrarare'))
  if (ultraRareToggle) {
    $('#ultraRareToggle').bootstrapToggle('on')
  }
}

$('#notifyIV').change(function() {
  notifyIV = $('#notifyIV').val()
  store.set('notifyIV', notifyIV)
})
$('#commonToggle').change(function() {
  commonToggle = !commonToggle
  store.set('common', commonToggle)
  common.clearLayers();
  commonMarkers = []
  commonID = []
  search()
})
$('#uncommonToggle').change(function() {
  uncommonToggle = !uncommonToggle
  store.set('uncommon', uncommonToggle)
  uncommon.clearLayers();
  uncommonMarkers = []
  uncommonID = []
  search()
})
$('#rareToggle').change(function() {
  rareToggle = !rareToggle
  store.set('rare', rareToggle)
  rare.clearLayers();
  rareMarkers = []
  rareID = []
  search()
})
$('#veryRareToggle').change(function() {
  veryRareToggle = !veryRareToggle
  store.set('veryrare', veryRareToggle)
  veryRare.clearLayers()
  veryRareMarkers = []
  veryRareID = []
  search()
})
$('#ultraRareToggle').change(function() {
  ultraRareToggle = !ultraRareToggle
  store.set('ultrarare', commonToggle)
  ultraRare.clearLayers();
  ultraRareMarkers = []
  ultraRareID = []
  search()
})

$('#pokeHide').autocomplete({
  lookup: pokemonNames,
  onSelect: function (suggestion) {

  }
})

Notification.requestPermission()
map = L.map('map',{minZoom:3});
var mapboxTiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map)
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
    var latlng = L.latLng([location['geometry']['location'].lat(),location['geometry']['location'].lng()])
    map.setView(latlng,14)
    search()
  }
})
.addTo(map);

window.setInterval(search, 1000)
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
    map.setView([position.coords.latitude,position.coords.longitude],14);
}
function makeClusters(aggs){
    var markerList = [];
    aggs.forEach(function(agg, index){
        var center = geohash.decode (agg.key);//elastic return a geohas so need to change it into lat/lon
        var myIcon = L.divIcon({ html: '<div class="clustergroup0 leaflet-marker-icon marker-cluster marker-cluster-medium leaflet-zoom-animated leaflet-clickable" tabindex="0" style="margin-left: -20px; margin-top: -20px; width: 40px; height: 40px; z-index: 233;"><div><span>'+agg.doc_count+'</span></div></div>' });
        var marker = L.marker(new L.LatLng(center.latitude, center.longitude),{icon:myIcon});
        marker.count = agg.doc_count;
        marker.on('click', function(x) {
          map.setView([x['latlng']['lat'],x['latlng']['lng']],map.getZoom()+2)
        })
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
    var encID = poke['_id']
    var dt = info['disappearTime']
    var attack = info['iv_attack']
    var defense = info['iv_defense']
    var stamina = info['iv_stamina']
    var iv = Math.round((attack+defense+stamina)*1000/45.0)/10
    var name = pokeNames[pokeID]['name']
    var icon = L.icon ({
      iconUrl: 'icons/' + pokeID +'.png',
      iconSize: [40,40]
    })
    var popup
    if (attack != null) {
      popup = L.popup()
        .setContent('<strong>'+name+'</strong> - ' + pokeNames[pokeID]['rarity'] +
        '<br><span class="label-countdown" disappears-at=\'' + dt + '\'>' + getTimeRemaining(dt) + '</span><br><strong>IV: ' + iv
        + '% (' + attack + '/' + defense + '/' + stamina + ')</strong>')
    }
    else {
      popup = L.popup()
        .setContent('<strong>'+name+'</strong> - ' + pokeNames[pokeID]['rarity'] +
        '<br><span class="label-countdown" disappears-at=\'' + dt + '\'>' + getTimeRemaining(dt) + '</span>')
    }
    var marker = L.marker([location['lat'],location['lon']],{icon: icon})
    .bindPopup(popup)
    marker.on('mouseover', function (e) {
      this.openPopup();
    });
    marker.on('mouseout', function (e) {
      this.closePopup();
    });
    if (new Date(dt) > Date.now()) {
      if (pokeNames[pokeID]['rarity'] == 'Common' && !commonID.includes(encID) && commonToggle) {
        marker.addTo(common)
        commonID.push(encID)
        commonMarkers.push(marker)
      }
      else if (pokeNames[pokeID]['rarity'] == 'Uncommon' && !uncommonID.includes(encID) && uncommonToggle) {
        marker.addTo(uncommon)
        uncommonID.push(encID)
        uncommonMarkers.push(marker)
      }
      else if (pokeNames[pokeID]['rarity'] == 'Rare' && !rareID.includes(encID) && rareToggle) {
        marker.addTo(rare)
        rareID.push(encID)
        rareMarkers.push(marker)
      }
      else if (pokeNames[pokeID]['rarity'] == 'Very Rare' && !veryRareID.includes(encID) && veryRareToggle) {
        marker.addTo(veryRare)
        veryRareID.push(encID)
        veryRareMarkers.push(marker)
      }
      else if (pokeNames[pokeID]['rarity'] == 'ultraRare' && !ultraRareID.includes(encID) && ultraRareToggle) {
        marker.addTo(ultraRare)
        ultraRareID.push(encID)
        ultraRareMarkers.push(marker)
      }
    }
    else {
      if (commonID.includes(encID)) {
        index = commonID.indexOf(encID)
        commonID.splice(index, 1)
        common.removeLayer(commonMarkers[index])
        commonMarkers.splice(index,1)
      }
      else if (uncommonID.includes(encID)) {
        index = uncommonID.indexOf(encID)
        uncommonID.splice(index, 1)
        uncommon.removeLayer(uncommonMarkers[index])
        uncommonMarkers.splice(index,1)
      }
      else if (rareID.includes(encID)) {
        index = rareID.indexOf(encID)
        rareID.splice(index, 1)
        rare.removeLayer(rareMarkers[index])
        rareMarkers.splice(index,1)
      }
      else if (veryRareID.includes(encID)) {
        index = veryRareID.indexOf(encID)
        veryRareID.splice(index, 1)
        veryRare.removeLayer(veryRare[index])
        veryRareMarkers.splice(index,1)
      }
      else if (ultraRareID.includes(encID)) {
        index = ultraRareID.indexOf(encID)
        ultraRareID.splice(index, 1)
        ultraRare.removeLayer(ultraRareMarkers[index])
        ultraRareMarkes.splice(index,1)
      }
    }
    if (notifyIV != "" && iv>notifyIV && !notifiedPokemon.includes(encID)) {
      notifiedPokemon.push(encID)
      Push.create("High IV Pokémon!", {
        body: "Found a " + iv + "% " + name,
        icon: "pixel_icons/" + pokeID + ".png",
        timeout: 4000,
        onClick: function() {
          map.setView([location['lat'],location['lon']], 15)
        }
      })
    }
  })
}

function search() {
  $('.label-countdown').each(function (index, element) {
    $(element).text(getTimeRemaining(element.getAttribute('disappears-at')))
  })
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
          commonMarkers = []
          uncommonMarkers = []
          rareMarkers = []
          veryRareMarkers = []
          ultraRareMarkers = []
          commonID = []
          uncommonID = []
          rareID = []
          veryRareID = []
          ultraRareID = []
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
          // common.clearLayers();
          if (resp.startsWith("Error:")) {
            console.log(resp)
          }
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
          // uncommon.clearLayers();
          if (resp.startsWith("Error:")) {
            console.log(resp)
          }
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
          // rare.clearLayers();
          if (resp.startsWith("Error:")) {
            console.log(resp)
          }
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
          // veryRare.clearLayers();
          if (resp.startsWith("Error:")) {
            console.log(resp)
          }
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
          // ultraRare.clearLayers();
          if (resp.startsWith("Error:")) {
            console.log(resp)
          }
          var result = JSON.parse(resp)
          makePokemon(result['hits']['hits'])
        }
      })
    }
  }
}

function pad(n) {
    return (n < 10) ? ("0" + n) : n;
}

function getTimeRemaining(endtime){
  // console.log(new Date(endtime))
  // console.log(new Date())
  var t = Date.parse(endtime) - Date.parse(new Date());
  var seconds = Math.floor( (t/1000) % 60 );
  var minutes = Math.floor( (t/1000/60) % 60 );
  var hours = Math.floor( (t/(1000*60*60)) % 24 );
  var days = Math.floor( t/(1000*60*60*24) );
  return 'Disappears in ' + pad(minutes) + 'm' + pad(seconds) + 's'
}
