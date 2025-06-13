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
  const DEFAULT_LAT = 19.4326; // Ciudad de México
  const DEFAULT_LNG = -99.1332;

  var lat = DEFAULT_LAT;
  var lng = DEFAULT_LNG;
  var map;
  var loadedMap = false;
  var u_lat, u_lng, popContent;
  var server_name = window.location.origin;

  document.addEventListener("DOMContentLoaded", () => {
    if (localStorage.getItem('ubicacionAceptada') !== 'true') {
      Swal.fire({
        title: 'Permisos de ubicación',
        text: '¿Deseas permitir el acceso a tu ubicación?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Aceptar',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.isConfirmed) {
          localStorage.setItem('ubicacionAceptada', 'true');
          solicitarUbicacion();
        } else {
          // Usar ubicación por defecto si se niega
          cargaMapa();
        }
      });
    } else {
      solicitarUbicacion();
    }
  });

  function solicitarUbicacion() {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(
        (position) => {
          lat = position.coords.latitude;
          lng = position.coords.longitude;
          cargaMapa();
        },
        (error) => {
          console.warn('No se pudo obtener la ubicación: ' + error.message);
          cargaMapa(); // usar la ubicación por defecto
        }
      );
    } else {
      console.warn("El navegador no soporta geolocalización.");
      cargaMapa(); // usar la ubicación por defecto
    }
  }

  function cargaMapa() {
    let token = "pk.eyJ1Ijoia3Jvbm9zYWludCIsImEiOiJjbDE0OHUzbmIwOGR4M2pvajRyeGhibnBpIn0.h1bYefoVPuf4-hLqp3i9Xg";

    map = L.map('map').setView([lat, lng], 16);
    L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=' + token, {
      attribution: 'Map data © <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
      maxZoom: 18,
      id: 'mapbox/streets-v11',
      tileSize: 512,
      zoomOffset: -1,
      accessToken: token
    }).addTo(map);

    loadData(lat, lng);
  }

  async function loadData(lat, lng) {
    let ubicaciones = await getUbicaciones(lat, lng);
    ubicaciones.forEach(ubicacion => {
      u_lat = ubicacion['latitud'];
      u_lng = ubicacion['longitud'];
      let nombre = ubicacion['nombre'];
      let recompensa = ubicacion['recompensa'] || "";
      let descripcion = ubicacion['descripcion'];
      let publicPath = (window.location.host == "localhost") ? "/public" : "";
      let url = server_name + publicPath + '/detalles_entidad/' + ubicacion['entidad_id'];

      popContent = `<div style="max-width: 250px; white-space: normal">
        <h3>${nombre}</h3>
        <p style="word-wrap: break-word;">${descripcion}</p>
        <a href="${url}">Ver detalles</a>
        <p><b>$${recompensa}</b></p>
      </div>`;

      drawMap();
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
    return await rawResponse.json();
  }

  function drawMap() {
    L.marker([u_lat, u_lng]).bindPopup(popContent).addTo(map);
  }
</script>

@endsection
