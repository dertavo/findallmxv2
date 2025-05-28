@extends('layout.app')

@section('header')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
crossorigin=""></script>

<style>
  #map { 
    margin-top: 15px;
    height: 550px; 
    width: 100%;
  }
  .popup-description {
    text-align: justify; /* Alinear el texto a la justificación */
    text-justify: inter-word; /* Justificar el texto entre las palabras */
    max-width: 200px; /* Limitar el ancho máximo del texto */
    overflow: hidden; /* Esconder el texto que sobrepasa el ancho máximo */
    white-space: nowrap; /* Evitar que el texto se ajuste automáticamente */
    text-overflow: ellipsis; /* Mostrar puntos suspensivos al final del texto sobrepasado */
  }
</style>
@endsection

@section('contenido')

<div>
  <h5>Descubrir:</h5>
  <div id="map"></div>
</div>

@endsection

@section('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
{{-- <script src="{{ asset('js/getToken.js') }}"></script> --}}

<script>
  // Verificamos si el usuario ya ha aceptado los permisos de ubicación
  if (localStorage.getItem('ubicacionAceptada') !== 'true') {
    // Creamos la alerta con SweetAlert2
    Swal.fire({
      title: 'Permisos de ubicación',
      text: 'Para usar el sitio web de manera efectiva y eficiente, necesitamos que nos permitas acceder a tu ubicación.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Aceptar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        localStorage.setItem('ubicacionAceptada', 'true');
        // Solicitar permisos de geolocalización al navegador
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(
            (position) => {
              console.log('Latitud:', position.coords.latitude);
              console.log('Longitud:', position.coords.longitude);
              // Aquí puedes usar la ubicación como desees
            },
            (error) => {
              console.error('Error al obtener la ubicación:', error.message);
              Swal.fire('Error', 'No pudimos obtener tu ubicación.', 'error');
            }
          );
        } else {
          Swal.fire('No compatible', 'Tu navegador no soporta geolocalización.', 'error');
        }
      }
    });
  }

  getLocation();

  var lat = 0;
  var lng = 0;
  var map;
  var loadedMap = false;
  var u_lat, u_lng, popContent;
  var server_name = window.location.origin; // Definir la base URL del servidor

  async function loadData(lat, lng) {
    let ubicaciones = await getUbicaciones(lat, lng);
    // console.log(ubicaciones);
    ubicaciones.forEach(ubicacion => {
      u_lat = ubicacion['latitud'];
      u_lng = ubicacion['longitud'];
      let nombre = ubicacion['nombre'];
      let recompensa = ubicacion['recompensa'] == null ? "" : ubicacion['recompensa'];
      let descripcion = ubicacion['descripcion'];
      let publicPath = "";

      if (window.location.host == "localhost") {
        publicPath = "/public";
      }

      let url = server_name + publicPath + '/detalles_entidad/' + ubicacion['entidad_id'];
      // console.log(url)

      popContent = '<div style="max-width: 250px; white-space: normal">' +
        '<h3>' + nombre + '</h3>' +
        '<p style="word-wrap: break-word;">' + descripcion + '</p>' +
        '<a href="' + url + '">Ver detalles</a>' +
        '<p><b>$' + recompensa + '</b></p>' +
        '</div>';

      var distance = getDistance([lat, lng], [u_lat, u_lng]);
      // console.log("DISTANCEEE:"+distance)
      //if(distance <=1000){
      drawMap();
      loadedMap = true;
      //}
    });
  }

  async function getUbicaciones(lat, lng) {
    let ruta = server_name + '/api/getUbicaciones/' + lat + '/' + lng;

    const rawResponse = await fetch(ruta, {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json'
      },
    });

    let response = await rawResponse.json();

    return response;
  }

  function getLocation() {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(showPosition);
    } else {
      alert("Geolocation is not supported by this browser.");
    }
  }

  function cargaMapa() {
    let token = "pk.eyJ1Ijoia3Jvbm9zYWludCIsImEiOiJjbDE0OHUzbmIwOGR4M2pvajRyeGhibnBpIn0.h1bYefoVPuf4-hLqp3i9Xg";

    map = L.map('map').setView([lat, lng], 16);
    L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=' + token, {
      attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
      maxZoom: 18,
      id: 'mapbox/streets-v11',
      tileSize: 512,
      zoomOffset: -1,
      accessToken: token
    }).addTo(map);

    loadData(lat, lng);
  }

  function showPosition(position) {
    lat = position.coords.latitude;
    lng = position.coords.longitude;

    cargaMapa();
  }

  function getDistance(origin, destination) {
    // return distance in meters
    var lon1 = toRadian(origin[1]),
      lat1 = toRadian(origin[0]),
      lon2 = toRadian(destination[1]),
      lat2 = toRadian(destination[0]);

    var deltaLat = lat2 - lat1;
    var deltaLon = lon2 - lon1;

    var a = Math.pow(Math.sin(deltaLat / 2), 2) + Math.cos(lat1) * Math.cos(lat2) * Math.pow(Math.sin(deltaLon / 2), 2);
    var c = 2 * Math.asin(Math.sqrt(a));
    var EARTH_RADIUS = 6371;
    return c * EARTH_RADIUS * 1000;
  }

  function toRadian(degree) {
    return degree * Math.PI / 180;
  }

  function drawMap() {
    var marker = L.marker([u_lat, u_lng]).bindPopup(popContent).addTo(map);
  }
</script>
@endsection
