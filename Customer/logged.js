var data;
var getProducts = new XMLHttpRequest(); //προιόντα, username και καταστήματα γιατί οχι!
msg = {
  function: "fetch"
}
getProducts.open("POST", "functionality.php", true);
getProducts.setRequestHeader("Content-type", "application/json");
getProducts.onreadystatechange = function() {
  if (this.readyState == 4 && this.status == 200) {
    var tab = document.getElementById("prod");
    data = JSON.parse(this.responseText);
    document.getElementById("user").innerHTML = "Καλώς ήρθατε " + data[0].user;
    var tbody = document.createElement('tbody');
    tab.appendChild(tbody);
    for (var i = 0; i < data[0].products.length; i++) {
      var tr = document.createElement('tr');
      tr.id = i;
      tbody.appendChild(tr);
      var td1 = document.createElement('td');
      td1.innerHTML = data[0].products[i].id;
      var td2 = document.createElement('td');
      td2.innerHTML = data[0].products[i].price + "€";
      var td3 = document.createElement('td');
      var img1 = document.createElement('img');
      img1.src = "images/plus.jpg";
      img1.height = "15";
      img1.width = "15";
      td3.appendChild(img1);
      var td4 = document.createElement('td');
      td4.innerHTML = "0";
      td4.id = "sum" + i;
      var td5 = document.createElement('td');
      var img2 = document.createElement('img');
      img2.src = "images/minus.jpg";
      img2.height = "15";
      img2.width = "15";
      td5.appendChild(img2);
      td3.id = "td3" + i;
      td5.id = "td5" + i;
      td3.addEventListener('click', function() {
        addCart(this.id);
      });
      td5.addEventListener('click', function() {
        remCart(this.id);
      });
      td4.style.display = 'none';
      tr.appendChild(td1);
      tr.appendChild(td2);
      tr.appendChild(td5);
      tr.appendChild(td4);
      tr.appendChild(td3);
    }
  }
};
getProducts.send(JSON.stringify(msg));

function sendOrd() {
  var cart = document.getElementById("cart");
  var carttrs = cart.getElementsByTagName("tr");
  var sum = 0;
  var goods = [];
  for (var i = 1; i < carttrs.length - 1; i++) {
    var ctds = carttrs[i].getElementsByTagName('td');
    goods[i - 1] = {
      "product": ctds[0].innerHTML,
      "count": ctds[1].innerHTML
    };
  }
  var bell = document.getElementById("bell");
  var user = document.getElementById("user");
  var addr = document.getElementById("addr");
  if (carttrs.length <= 2) {
    document.getElementById('wcart').style.display = 'inline';
    document.getElementById('waddr').style.display = 'none';
    document.getElementById('wbell').style.display = 'none';
    return false;
  } else if (bell.value == '') {
    document.getElementById('wbell').style.display = 'inline';
    document.getElementById('wcart').style.display = 'none';
    document.getElementById('waddr').style.display = 'none';
    return false;
  } else if (addr.innerHTML == '') {
    document.getElementById('waddr').style.display = 'inline';
    document.getElementById('wbell').style.display = 'none';
    document.getElementById('wcart').style.display = 'none';
    return false;
  } else {
    document.getElementById('wbell').style.display = 'none';
    document.getElementById('waddr').style.display = 'none';
    document.getElementById('wcart').style.display = 'none';
    var tsum = document.getElementById("foot").getElementsByTagName("td");
    var sum = tsum[2].innerHTML.slice(0, tsum[2].innerHTML.length - 1);
    var order = {
      user: (user.innerHTML).slice(13, user.innerHTML.length),
      bell: bell.value,
      address: addr.innerHTML,
      goods: goods,
      stores: null,
      function: "order",
      sum: sum
    };
    var dest = [];
    var datas = [];
    for (var i = 0; i < data[0].stores.length; i++) {
      datas[i] = data[0].stores[i];
    }
    for (var i = 0; i < data[0].stores.length; i++) {
      dest[i] = new google.maps.LatLng(data[0].stores[i].lat, data[0].stores[i].lng);
    }
    var service = new google.maps.DistanceMatrixService();
    //alert("Lat : " + document.getElementById('lat').innerHTML + " Lng : " + document.getElementById('lng').innerHTML);
    service.getDistanceMatrix({
      origins: [new google.maps.LatLng(document.getElementById('lat').innerHTML, document.getElementById('lng').innerHTML)],
      destinations: dest,
      travelMode: 'DRIVING',
    }, res);

    function res(response, status) {
      if (status == 'OK') {
        //alert(JSON.stringify(response));
        var distance = [];
        var origins = response.originAddresses;
        for (var i = 0; i < origins.length; i++) {
          var results = response.rows[i].elements;
          for (var j = 0; j < results.length; j++) {
            //alert("Katasthma : " + datas[j].name + " Apostash : " + results[j].distance.value);
            distance[j] = {
              distval: results[j].distance.value,
              name: datas[j].name
            };

          }
        }
        distance.sort(function(obj1, obj2) {
          return obj1.distval - obj2.distval;
        });
        //alert("Apostash " + distance[0].name + " 0 " + distance[0].distval + " Apostash " + distance[1].name + " 1 " + distance[1].distval);
        order.stores = [];
        for (var i = 0; i < distance.length; i++) {
          order.stores[i] = {
            name: distance[i].name,
            distval: distance[i].distval
          };
        }
        var sendOrder = new XMLHttpRequest(); //στείλε παραγγελίες και φέρε ντελιβεράδες
        sendOrder.open("POST", "functionality.php", true);
        sendOrder.setRequestHeader("Content-type", "application/json");
        sendOrder.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            //alert(this.responseText);
            data = JSON.parse(this.responseText);
            if (data[0].failed == '1') {
              alert(data[0].message);
              window.location = '/';
            } else {
              var resl = {
                id: data[0].paragelia,
                user: null,
                function: "select_delivery",
                goods: goods,
                finaldest: null,
                lat: document.getElementById('lat').innerHTML,
                long: document.getElementById('lng').innerHTML
              };
              var del = [];
              var destdel = [];
              for (var i = 0; i < data[0].deliveries.length; i++) {
                del[i] = data[0].deliveries[i];
              }
              for (var i = 0; i < data[0].deliveries.length; i++) {
                destdel[i] = new google.maps.LatLng(del[i].lat, del[i].lng);
              }
              var service2 = new google.maps.DistanceMatrixService();
              service.getDistanceMatrix({
                origins: [new google.maps.LatLng(data[0].store[0].lat, data[0].store[0].lng)],
                destinations: destdel,
                travelMode: 'DRIVING',
              }, res2);

              function res2(response, status) {
                if (status == 'OK') {
                  var distance = [];
                  var origins = response.originAddresses;
                  for (var i = 0; i < origins.length; i++) {
                    var results = response.rows[i].elements;
                    for (var j = 0; j < results.length; j++) {
                      distance[j] = {
                        distval: results[j].distance.value,
                        username: del[j].username
                      };
                    }
                  }
                  distance.sort(function(obj1, obj2) {
                    return obj1.distval - obj2.distval;
                  });
                  resl.user = distance[0].username;
                  var distkat_proo;
                  //το distance[0].distval ειναι η αποσταση delivery απο κατάστημα
                  for (var i = 0; i < order.stores.length; i++) {
                    if (order.stores[i].name == data[0].store[0].name) {
                      distkat_proo = order.stores[i].distval;
                    }
                  }
                  resl.finaldest = distkat_proo + distance[0].distval;
                  //alert(resl.finaldest);
                  var sendDel = new XMLHttpRequest(); //στείλε ντελιβεράδες
                  sendDel.open("POST", "functionality.php", true);
                  sendDel.setRequestHeader("Content-type", "application/json");
                  sendDel.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                      alert(this.response);
                      window.location = '/';
                    }
                  };
                  sendDel.send(JSON.stringify(resl));
                }
              }
            }
          }
        };
        sendOrder.send(JSON.stringify(order));
      } else {
        alert("Κατι πήγε στραβά ξανα προσπαθήστε αργότερα");
        window.location = '/';
      }
    }
  }
}

function addCart(id) {
  if (document.getElementById("foot")) {
    document.getElementById("foot").parentElement.removeChild(document.getElementById("foot"));
  }
  var tr = document.getElementById(id).parentElement;
  var tds = tr.getElementsByTagName('td');
  tds[3].innerHTML++;
  var cart = document.getElementById("cart");
  var exist = document.getElementById("c" + document.getElementById(id).parentElement.id);
  if (exist == null) {
    var tr = document.createElement("tr");
    tr.id = "c" + document.getElementById(id).parentElement.id;
    cart.appendChild(tr);
    var td1 = document.createElement("td");
    var td2 = document.createElement("td");
    var td3 = document.createElement("td");
    td1.innerHTML = tds[0].innerHTML;
    td2.innerHTML = tds[3].innerHTML;
    td3.innerHTML = (tds[1].innerHTML.slice(0, tds[1].innerHTML.length - 1) * tds[3].innerHTML).toFixed(2) + "€";
    tr.appendChild(td1);
    tr.appendChild(td2);
    tr.appendChild(td3);
  } else {
    var etds = exist.getElementsByTagName('td');
    etds[1].innerHTML = tds[3].innerHTML;
    etds[2].innerHTML = (tds[1].innerHTML.slice(0, tds[1].innerHTML.length - 1) * tds[3].innerHTML).toFixed(2) + "€";
  }
  addFoot();
}

function remCart(id) {
  if (document.getElementById("foot")) {
    var p = document.getElementById("foot").parentElement;
    p.removeChild(document.getElementById("foot"));
  }
  var cart = document.getElementById("cart");
  var tr = document.getElementById(id).parentElement; //αυτο εδώ είναι ο πίνακας με τα προιόντα
  var tds = tr.getElementsByTagName('td');
  if (tds[3].innerHTML > 0) {
    tds[3].innerHTML--;
  }
  if (tds[3].innerHTML == 0) {
    var exist = document.getElementById("c" + document.getElementById(id).parentElement.id);
    if (exist != null) {
      var cart = document.getElementById("cart");
      cart.removeChild(exist);
    }
  } else {
    var exist = document.getElementById("c" + document.getElementById(id).parentElement.id);
    var etds = exist.getElementsByTagName('td');
    etds[1].innerHTML = tds[3].innerHTML;
    etds[2].innerHTML = (tds[1].innerHTML.slice(0, tds[1].innerHTML.length - 1) * tds[3].innerHTML).toFixed(2) + "€";
  }
  addFoot();
}

function addFoot() {
  var cart = document.getElementById("cart");
  var carttrs = cart.getElementsByTagName("tr");
  var i;
  var sum = 0;
  for (i = 1; i < carttrs.length; i++) {
    var ctds = carttrs[i].getElementsByTagName('td');
    sum = sum + parseFloat(ctds[2].innerHTML.slice(0, ctds[2].innerHTML.length - 1));
  }
  sum = sum.toFixed(2);
  var ft = document.createElement("tr");
  ft.id = "foot";
  cart.appendChild(ft);
  var ftd1 = document.createElement('td');
  var ftd2 = document.createElement('td');
  var ftd3 = document.createElement('td');
  ftd1.innerHTML = "Σύνολο";
  ftd3.id = "fsum";
  ftd3.innerHTML = sum + "€";
  ft.appendChild(ftd1);
  ft.appendChild(ftd2);
  ft.appendChild(ftd3);
}

function initMap() {
  var map = new google.maps.Map(document.getElementById('map'), { //φτιαχνει χαρτη και του δεινει αρχικες συντεταγμενες
    center: {
      lat: 38.246229,
      lng: 21.735175
    },
    zoom: 15
  });
  var card = document.getElementById('pac-card'); //το όλο div του χαρτη
  var input = document.getElementById('pac-input'); //το textfield
  var geocoder = new google.maps.Geocoder; //για μεταφραση απο latLng σε διεθυνση
  //map.controls[google.maps.ControlPosition.TOP_RIGHT].push(card); //που θα είναι η αναζήτηση
  var autocomplete = new google.maps.places.Autocomplete(input); //η μεταβλητη για το autocomplete παιρνει είσοδο απο το inout field ειναι υπεθυνη
  autocomplete.bindTo('bounds', map); //biased autocomplete
  var infowindow = new google.maps.InfoWindow(); //to container απο αυτο που βγαζει πάνω απο τον marker
  var infowindowContent = document.getElementById('infowindow-content'); //αυτο που βγάζει πάνω απο τον marker
  infowindow.setContent(infowindowContent); //βαζει μεσα στο window το content
  var marker = new google.maps.Marker({ //φτιαχνει τον marker
    map: map, //πανω στον χαρτη που εχουμε φτιαξει
    draggable: true //να μπορω να τον κουναω
  });

  autocomplete.addListener('place_changed', function() { //οταν αλλάξει το μέρος ψαχνει να το βρεί
    infowindow.close();
    marker.setVisible(false);
    var place = autocomplete.getPlace();
    if (!place.geometry) { // αν δεν το βρει δεν κάνει τιποτα
      // User entered the name of a Place that was not suggested and
      // pressed the Enter key, or the Place Details request failed.
      return;
    }
    // If the place has a geometry, then present it on a map.
    if (place.geometry.viewport) {
      map.fitBounds(place.geometry.viewport);
    } else {
      map.setCenter(place.geometry.location);
      map.setZoom(17); // Why 17? Because it looks good.
    }
    marker.setPosition(place.geometry.location);
    marker.setVisible(true);
    var address = '';
    if (place.address_components) {
      address = place.formatted_address;
    }
    var latlngStr = (place.geometry.location + '').split(',', 2);
    latlngStr[0] = latlngStr[0].slice(1, latlngStr[0].length);
    latlngStr[1] = latlngStr[1].slice(0, latlngStr[1].length - 1);
    var latlng = {
      lat: parseFloat(latlngStr[0]),
      lng: parseFloat(latlngStr[1])
    };
    latlng.lat = latlng.lat.toFixed(6);
    latlng.lat = parseFloat(latlng.lat);
    latlng.lng = latlng.lng.toFixed(6);
    latlng.lng = parseFloat(latlng.lng);
    document.getElementById('lat').innerHTML = latlng.lat;
    document.getElementById('lng').innerHTML = latlng.lng;
    infowindowContent.children['place-icon'].src = place.icon;
    infowindowContent.children['place-name'].textContent = place.name;
    infowindowContent.children['place-address'].textContent = address;
    document.getElementById('addr').innerHTML = address;
    infowindow.open(map, marker);
  });
  marker.addListener('dragend', function(e) { //ο χρηστης μπορει να τραβηξει τον marker και να κανει finetune στην τοποθεσια του
    marker.setPosition(e.latLng);
    geocodeLatLng(geocoder, map, infowindow, e.latLng + '');
  });
  map.addListener('click', function(e) { //ο χρηστης μπορει να επιλέξει μέρος
    marker.setPosition(e.latLng);
    geocodeLatLng(geocoder, map, infowindow, e.latLng + '');
  })

  function geocodeLatLng(geocoder, map, infowindow, latLngt) { //μεταφραζει latlng σε διεθυνση
    infowindow.close();
    var latlngStr = latLngt.split(',', 2);
    latlngStr[0] = latlngStr[0].slice(1, latlngStr[0].length);
    latlngStr[1] = latlngStr[1].slice(0, latlngStr[1].length - 1);
    var latlng = {
      lat: parseFloat(latlngStr[0]),
      lng: parseFloat(latlngStr[1])
    };
    latlng.lat = latlng.lat.toFixed(6);
    latlng.lat = parseFloat(latlng.lat);
    latlng.lng = latlng.lng.toFixed(6);
    latlng.lng = parseFloat(latlng.lng);
    document.getElementById('lat').innerHTML = latlng.lat;
    document.getElementById('lng').innerHTML = latlng.lng;
    geocoder.geocode({
      'location': latlng
    }, function(results, status) {
      if (status === 'OK') {
        if (results[0]) {
          infowindow.setContent(results[0].formatted_address);
          input.value = results[0].formatted_address;
          document.getElementById('addr').innerHTML = results[0].formatted_address;
          document.getElementById('waddr').style.display = 'none';
          infowindow.open(map, marker);
        }
      }
    });
  }
}
