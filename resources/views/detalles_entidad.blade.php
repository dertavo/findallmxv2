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
       height: 400px; 
       width:100%;
    }
     .imagenesproducto{
      height:300px;
      width:300px;
    }
   </style>

@endsection

@section('contenido')
<div  class="loader"></div>
 <div class="container-fluid">   
<h3 id="detalles">Detalles</h3>

@if(session('founded'))
    <div class="alert alert-success">
        {{ session('founded') }} <span>&#x1F600;</span>
    </div>
@endif
<div id="div-imgs"></div>


<div class="card">
  <div class="card-header" id="card_header">
    
  </div>
  <div class="card-body">
    <h5 class="card-title" id="card_title"></h5>
    <p class="card-text"  id="card_text"></p>

  </div>
</div>
<h4>Puntos de ubicación</h4>
 <div id="map"></div>
  


  <div class="col-md-12 d-flex justify-content-center mb-4 mt-4">


     <button hidden id="sf"  class="btn btn-primary">Contacto</button>
     
   
    
     <button hidden id="belowme" class="btn btn-primary">Esta entidad te pertenece</button>
 
   
  </div>
</div>
@endsection

@section('scripts')

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
loader = document.querySelector('.loader');

var url_user = {{$user}}

var no;

if(url_user == getC('user')){
  document.getElementById('belowme').removeAttribute("hidden");
  no= true;

}else{
  document.getElementById('sf').removeAttribute("hidden");
  no=false
}

var url_string = window.location.href;
var aux = url_string.split('/')
var id = aux[aux.length - 1]
elid= id;



if(!no){
  document.getElementById('sf').addEventListener('click',function(){
  Swal.fire({
  title: 'Te pedimos que corrobores la información antes de contactar al dueño de la publicación',
  showDenyButton: true,
  showCancelButton: false,
  confirmButtonText: 'Sí, estoy seguro',
  denyButtonText: 'Corroborar',
}).then((result) => {
  console.log(elid)
  /* Read more about isConfirmed, isDenied below */
  if (result.isConfirmed) {
   var url = '{{ route("contacto_entidad", ":slug") }}';
   url = url.replace(':slug', elid);
   window.location.href=url;
  } else if (result.isDenied) {
  
  }
})
})
}

var lat = 0;
var lng = 0;

// Llamar desde el inicio
getLocation();

function getLocation() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
      showPosition,
      handleLocationError // se ejecuta si falla
    );
  } else {
    alert("Geolocalización no soportada por este navegador.");
    setDefaultLocation();
  }
}

function showPosition(position) {
  lat = position.coords.latitude;
  lng = position.coords.longitude;

  drawData(); // tu función para usar la ubicación
}

function handleLocationError(error) {
  console.warn("No se pudo obtener la ubicación:", error.message);
  setDefaultLocation();
}

function setDefaultLocation() {
  // Ciudad de México como fallback
  lat = 19.4326;
  lng = -99.1332;
  drawData(); // aún así mostrar contenido
}


async function drawData(){

  data = await loadData();
  info= data['entitys']

  imgs = data['imgs']

  user =data['user']

  nombre = info['nombre'];
  descripcion = info['descripcion'];
  divimg = document.getElementById('div-imgs')

  card_header=document.getElementById('card_header');
  card_title=document.getElementById('card_title');
  card_text=document.getElementById('card_text');

  email = user['email']
  url_user = server_name + '/contact-profile/'+ user['id']

  reco = info['recompensa'] == null ? "" : info['recompensa']
  
  contact = 'Contacto: <a href="'+url_user+'">'+ email +'</a> '+
  '<br> <p>Recompensa: ' + reco

  card_header.innerHTML=contact
  card_title.innerHTML=nombre  
  card_text.innerHTML=descripcion;

  imgdata = "";
  console.log(imgs)

   imgs.forEach(imagen => {
    imgsrc = imagen['archivo'];
    src = server_name+'/storage/entidades/'+imgsrc+'';
    //src = server_img + imgsrc;
    
    id = "img"+imagen['id'];
    //console.log(src)
    imgdata = '<img id="'+id+'" onclick="toggleFullScreen('+id+')" src="'+src+'" class="img-thumbnail imagenesproducto">'  
    divimg.innerHTML += imgdata;
 
     
     
    });
 
  drawMap(info['ubicaciones']);

}

async function drawMap(info){

token="pk.eyJ1Ijoia3Jvbm9zYWludCIsImEiOiJjbDE0OHUzbmIwOGR4M2pvajRyeGhibnBpIn0.h1bYefoVPuf4-hLqp3i9Xg";


var map = L.map('map').setView([lat,lng], 16);
L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1Ijoia3Jvbm9zYWludCIsImEiOiJjbDE0OHUzbmIwOGR4M2pvajRyeGhibnBpIn0.h1bYefoVPuf4-hLqp3i9Xg', {
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
    maxZoom: 18,
    id: 'mapbox/streets-v11',
    tileSize: 512,
    zoomOffset: -1,
    accessToken: 'your.mapbox.access.token'
}).addTo(map);


     info.forEach(ubicacion => {
        lt=ubicacion['latitud'];
        lng=ubicacion['longitud'];
        nombre = ubicacion['nombre'];
        descripcion=ubicacion['descripcion'];
        var marker = L.marker([lt,lng]).addTo(map);
     
     });

}

async function loadData(){
  aux_ruta = server_name+'/api/detalles_entidadNormal/'+id
const rawResponse = await fetch(aux_ruta, {
    method: 'GET',
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json'
    },
    
  });
loader.style.visibility = 'hidden'
let response = await rawResponse.json();
return response;

}

 function toggleFullScreen(id) {
          
          id.requestFullscreen()
  }

</script>
    
@endsection