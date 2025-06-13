@extends('layout.app')



@section('header')

<link type="text/css" rel="stylesheet" href="{{asset('js/uploader/image-uploader.min.css')}}">
 

<style>
  #btnAgain{
    display: none;
  }
    #map {
        height: 600px;
        width: 100%;
    }
    .imagenesproducto{
      height:300px;
      width:300px;
    }
    #overlay {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5); /* fondo oscuro con transparencia */
  z-index: 999; /* asegurarse de que esté encima de todos los demás elementos */
}
#loader_2 {
  display: none;
  border: 10px solid #f3f3f3; /* Light grey */
  border-top: 10px solid #3498db; /* Blue */
  border-radius: 50%;
  width: 50px;
  height: 50px;
  animation: spin 2s linear infinite;
  position: absolute;
 
  top: 80%;
  -webkit-transform: translate(-50%, -50%);
  transform: translate(-50%, -50%);
  z-index: 1000;
}
@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>

@endsection

@section('contenido')

<div class="container-fluid mt-4">
    <div class="col-md-8 offset-md-2 border p-3">
        <form id="form-r" enctype='multipart/form-data'>
        @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nombre, animal o cosa</label>
                <input 
                placeholder="Nombre de la persona u objecto extravíado"
                required name="name" type="text" class="form-control" id="name" aria-describedby="emailHelp">
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Descripción from</label>
                <textarea 
                placeholder="Una breve descripción de la entidad extraviada"
                maxlength="200" name="description" id="description" class="form-control"></textarea>
                <span id="contador-caracteres"></span>
            </div>

             <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Fecha de extravío</label>
                <input
               
                required name="date" id="date" type="date" class="form-control">
            </div>

              <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Recompensa (opcional)</label>
                <input 
                placeholder="En caso de ofrecer una"
                name="reward" id="reward" type="text" class="form-control">
            </div>
    </div>

    <div class="form-group">
        <div class="form-check-inline">
          <label>Imagenes</label>

            <div class='file-input'>
                <input multiple="multiple" class='btn btn-light' type="file" name="imagenes[]"  id="img-entidad" accept="image/jpeg,image/png">
            </div>
            
            <div class="scrollmenuv mt-3" id="galeria">
                <div class="gallery" id="ga2"></div>
            </div>
        </div>
    </div>

    <input hidden id="isEdit" name="isEdit" />
    <input hidden id="idEnt" name="idEnt">

   

      <div class="input-group mb-4">
        <h5>Selecciona al menos 1 punto en el mapa (máx. 4):</h5>
        <button  id="btnAgain" class ="btn btn-warning ms-2">
          Comenzar de nuevo
        </button>
    </div>

 
    <div id="map"></div>

    <hr>


     <div class="col-md-12 d-flex justify-content-center mb-4 mt-4">
      <div id="loader_2" class="justify-content-center"></div>
    <button id="sf" class="btn btn-primary">Registro</button>
    </div>
    </form>


</div>

@php
  
   if($id!=null){
      echo "<script>var theid=$id;</script>";
    //echo "<script> var ruta ='registro_entidad'; var metodo='POST'</script>";
   }else{
    //echo "<script>var theid=$id; var ruta ='editar_entidad/$id'; var metodo='PUT';</script>";
   }
  
@endphp


@section('scripts')



<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

 <script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDDQ--_3tszoNpmyzgxhCe88Y9DWoJbRaE&v=weekly&callback=initMap"
      defer
    ></script>
<script>
var map =null;
var markers =[]
var btnAgain = document.getElementById('btnAgain')

function setMapOnAll(map) {
  for (let i = 0; i < markers.length; i++) {
    markers[i].setMap(map);
  }
}

btnAgain.addEventListener('click',function(e){
  e.preventDefault()
  btnAgain.style.display = 'none'

  setMapOnAll(null);
  markers =[]
  puntos =[]
})

const loader_2 = document.getElementById("loader_2");

const textarea = document.querySelector("#description");
const contadorCaracteres = document.querySelector("#contador-caracteres");

textarea.addEventListener("input", function() {
  const longitudActual = textarea.value.length;
  const longitudMaxima = textarea.getAttribute("maxlength");
  const caracteresRestantes = longitudMaxima - longitudActual;

  contadorCaracteres.innerHTML = caracteresRestantes + " caracteres restantes";
});

function processFile(reader){
    console.log("buenatio")
} 


    user_id = getC('user')

   
     async function delImg(source,filename){

        const result = window.confirm('¿Estás seguro de eliminar este elemento?', 'Eliminar elemento');
        if (!result) {

        } else {

        data = {
            "userid" : user_id,
            "imgid" : source,
            "filename":filename,
        } 
        token = getC('mytoken')
        const rawResponse = await fetch(server_name+'/api/delImg', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`,
            },
            body: JSON.stringify(data),
            credentials:'include',
        });
        const content = await rawResponse.json();
        // console.log(content)
        window.location.reload()

        }

}


   var ruta ="";
   var themetodo="";

    var detailcountImg=0;

   var update = false;

    var load_u=[]
    var name = document.getElementById('name');
    var description = document.getElementById('description'); 
    var reward = document.getElementById('reward');
    var date = document.getElementById('date'); 
    var divimg = document.getElementById('ga2')
    var btn_s= document.getElementById('sf')
  
  if (typeof theid != 'undefined') { 

    //getting the user id for updating.
    //alert("updating");

    ruta = 'editar_entidad/$id';
   
     ruta = 'registro_entidad';

    document.getElementById('idEnt').value=theid;

    inputEdit = document.getElementById('isEdit');
    inputEdit.value=1

      btn_s.innerHTML="Actualizar"
        update = true;
       drawData();
  }else{

     ruta = 'registro_entidad';
   // alert("new data")
      getLocation();
  }

   function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }
      function showPosition(position) {
        mlat= position.coords.latitude;
        mlng= position.coords.longitude;
        initMap();
    }

        // Initialize and add the map
      
function initMap() {
  // The location of Uluru
  const uluru = { lat: mlat, lng: mlng };
  puntos = load_u;
  
  // The map, centered at Uluru
    map = new google.maps.Map(document.getElementById("map"), {
    zoom: 17,
    center: uluru,
  });

  //const img_marker = "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.6.0/images/marker-icon.png";
  const img_marker = "http://leafletjs.com/examples/custom-icons/leaf-green.png"
  //el marcador con tu ubicación actual
  const marker = new google.maps.Marker({
    position: uluru,
    map: map,
  });

  //el evento en el mapa para dibujar nuevos marcadores
    map.addListener("click", (e) => {

     
      if(markers.length <4){
      const marker = new google.maps.Marker({
      position: e.latLng,
      map: map,
      icon: img_marker,
      });
      p = e.latLng.toJSON();
      puntos.push(p);

      markers.push(marker)

     
      btnAgain.style.display = 'block'
    }else{
      alert("Ha llegado al límite de puntos disponibles")
    }
  });
  

  //esto es solo para cuando se actualiza?
// console.log(puntos)
    if(update){

      aux_p =[]
 for(var u of puntos){

        const uluru = { lat: parseFloat(u['latitud']), lng: parseFloat(u['longitud'])};
        // console.log(uluru)
        const marker_l = new google.maps.Marker({
        position: uluru,
        map: map,
        icon: img_marker,
        });
     aux_p.push(marker_l)
      
  }
  markers = aux_p;
  // console.log(puntos)
  puntos = []

}

 
}
async function drawData(){
    data = await loadData();
   
    info = data['entitys']
    imgs = data['imgs']
    
    name.value = info['name']
    description.value = info['description']
    reward.value = info['reward']
    load_u=info['locations']
    markers= load_u
    date.value = info['date']
    imgs.forEach(imagen => {
    detailcountImg++
    imgsrc = imagen['archivo'];
    imgid  = imagen['id']
    src = server_name+'/public/storage/entidades/'+imgsrc+'';
    src = server_img + imgsrc
    

    imgdata = ''+
                ''+
            '<img  id='+imgid+' src="'+src+'" class="head-a imagenesproducto img-thumbnail">'+
            '<a onclick="delImg('+imgid+',\''+imgsrc+'\')" id="di" class="btn btn-danger btn-circle btn-x  bottom-0 right-0">'+
                '<i class="fa fa-times"></i>'+
            '</a>'+
            ''

      
    divimg.innerHTML += imgdata;   
   

  });

    getLocation()
   
}

async function loadData(){
  token = getC('mytoken')
      const rawResponse = await fetch(server_name+'/api/detalles_entidad/'+theid, {
    method: 'GET',
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      'Authorization': `Bearer ${token}`,
    },
    credentials:'include',
    
  });
let response = await rawResponse.json();

    if(response['code'] == 500){
             //alert(JSON.stringify(response['response']))
            window.location.replace(server_name+'/public/');

        }
       

return response;

}
let selectedFiles = [];
const fileList = document.getElementById('galeria');
const fileInput = document.getElementById('img-entidad');
  // Multiple images preview in browser
 function imagesPreview(input, placeToInsertImagePreview) {
  // Create an array to store the files

const imgElements = divimg.querySelectorAll('img')
if(imgElements.length>=4){
  alert("El máximo de imagenes a subir es de 4")
  return
}

auxfile = input.files
const myfile = auxfile[0]



if (!myfile.type.startsWith('image/')) {
  alert('Seleccione un archivo de imagen válido.');
  return;
}

const maxSizeInBytes = 1024 * 1024; // 1024 kilobytes en bytes

if (myfile.size > maxSizeInBytes) { // Compara el tamaño del archivo con el límite de tamaño en bytes
    alert("El archivo seleccionado es demasiado grande"); // Muestra una alerta si el archivo es demasiado grande
    return;
  }


  const files = fileInput.files;
  if (input.files) {
  
    for (let i = 0; i < files.length; i++) {
      selectedFiles.push(files[i]);
    }

    
    //console.log(filesAmount);
    for (let i = 0; i < selectedFiles.length; i++) {
      
      var reader = new FileReader();

      reader.onload = function(event) {
        $('#galeria').show();

        html =
        
          '<img id=imgid' +
          i +
          ' img class="head-a imagenesproducto img-thumbnail">' +
          '<a onclick="delImgG(' +
          i +
          ')" id="di'+i+'"  class=" btn btn-danger btn-circle btn-x">' +
          '<i data-index="${i}" class="removeButton fa fa-times"></i>' +
          '</a>';

        $($.parseHTML(html))
          .attr('src', event.target.result)
          .appendTo(placeToInsertImagePreview);
      };



      reader.readAsDataURL(input.files[i]);
    }
    di = document.getElementById('di');
  }

 
}
function deleteImage(index) {
 
    // Eliminar el archivo de la lista
selectedFiles.splice(index, 1);
//delete selectedFiles[index]

// console.log("deleting : "+index)

}
  document.getElementById('img-entidad').addEventListener('change',function(){
        imagesPreview(this, 'div.gallery');
  });


  fileList.addEventListener('click', event => {
    
    if (event.target.className === 'removeButton fa fa-times') {
    
    // Obtener el índice del archivo a eliminar
      const index = event.target.dataset.index;
      // Eliminar el archivo de la lista
      selectedFiles.splice(index, 1);
     
    }
  });

 function delImgG(index){
  $('#di' + index).remove();
  $('#imgid' + index).remove();
  // Call the deleteImage function
  //deleteImage(index);
       
}


</script>



<script>

    var host = "192.168.0.10";
    var mlat;
    var mlng;

    



sf.addEventListener('click', function(e) {
    e.preventDefault();

    sf.disabled=true;
    respuesta = sendData();
})

    // function drawMap() {
    //     token = "pk.eyJ1Ijoia3Jvbm9zYWludCIsImEiOiJjbDE0OHUzbmIwOGR4M2pvajRyeGhibnBpIn0.h1bYefoVPuf4-hLqp3i9Xg";

    //     var map = L.map('map').setView([lat, lng], 16);
    //     L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1Ijoia3Jvbm9zYWludCIsImEiOiJjbDE0OHUzbmIwOGR4M2pvajRyeGhibnBpIn0.h1bYefoVPuf4-hLqp3i9Xg', {
    //         attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>'
    //         , maxZoom: 18
    //         , id: 'mapbox/streets-v11'
    //         , tileSize: 512
    //         , zoomOffset: -1
    //         , accessToken: 'your.mapbox.access.token'
    //     }).addTo(map);

    //     var popup = L.popup();

    //     sf = document.getElementById('sf')
    //     puntos = [];

    //     function onMapClick(e) {

    //         var marker = L.marker(e.latlng).addTo(map);

    //         puntos.push(e.latlng);
    //     }

    //     map.on('click', onMapClick);


    //     sf.addEventListener('click', function(e) {
    //         e.preventDefault();
    //         console.log(puntos)
    //         //respuesta = sendData();
    //     })

    // }



   var inpname = document.getElementById('name')
   var inpdate = document.getElementById('date')
   var inpdescription = document.getElementById('description')
   function isEmpty(str) {
    return !str.trim().length;
  }
   async  function sendData(){
   
      $err_m=[];
      $err_=false;
      if(isEmpty(inpname.value)){
        $err_m.push("El campo nombre es obligatorio")
        $err_=true;
      }
      if(isEmpty(inpdescription.value)){
        $err_m.push("El campo descripción es obligatorio")
        $err_=true;
      }
      if(isEmpty(inpdate.value)){
        $err_m.push("El campo fecha de extravío es obligatorio")
        $err_=true;
      }


      if($err_){
        alert($err_m)
        sf.disabled=false;
        return 
      }
     

   
      const imgElements = divimg.querySelectorAll('img')

      if(imgElements.length==0){
        alert("Debe seleccionar al menos 1 imagen")
        sf.disabled=false;
        return
      }
      if(imgElements.length>4){
        alert("El máximo de imagenes a subir es de 4")
        sf.disabled=false;
        return
      }

        var formElement = document.getElementById("form-r");
         formData = new FormData(formElement);
         formData.append("user_id",user_id)

         for (let i = 0; i < selectedFiles.length; i++) {
          formData.append('files[]', selectedFiles[i]);
        }
        
      // console.log(selectedFiles)

/*
         if(detailcountImg>0){
            formData.append("imagenes[]",[]);
         }
      //    */
      //   console.log(puntos)
      // console.log("****PUNTOS*****: "+ JSON.stringify(puntos));
      if(puntos !=null){
        for(var indice in puntos){
           
           formData.append("locations[]",JSON.stringify(puntos[indice]));
       }
      }else{
        alert("Debe seleccionar al menos 1 punto en el mapa")
        sf.disabled=false;
        return
      }
       
        var totalfiles = document.getElementById('img-entidad').files.length;
        
        /*for (var index = 0; index < totalfiles; index++) {
            formData.append("imagenes[]", document.getElementById('img-entidad').files[index]);
        }*/

    
        rutaex = '{{route('ex')}}'
        token = getC('mytoken')
       rutaex = server_name+'/api/'+ruta
       loader_2.style.display = "block"
       const rawResponse = await fetch(rutaex, {
            headers:{
              'Authorization': `Bearer ${token}`,
            },
            method: "POST",  
            body: formData,
            credentials:'include',
        });
        const content = await rawResponse.json();
        // console.log(content)
        // console.log(content['response'])

        sf.disabled=false;
   
        loader_2.style.display = "none"
        if(content['code'] == 200){
             alert(JSON.stringify(content['response']))

             let url = '{{ route("entidades") }}';
             window.location.href= url;
            //  window.location.reload();
        }
       
        else{
            alert(JSON.stringify(content['response']))
           
        }


    }

    // async function sendData2(imagenes_id) {

    //     nombre = document.getElementById('nombre').value;
    //     descripcion = document.getElementById('descripcion').value; 
    //     recompensa = document.getElementById('recompensa').value;
    //     fecha_extravio = document.getElementById('fecha_extravio').value; 
    //     data = {
    //         "nombre" : nombre,
    //         "descripcion" : descripcion,
    //         "fecha_extravio":fecha_extravio,
    //         "recompensa":recompensa,
    //         "ubicaciones" : puntos,
    //         "imagenes_id" : imagenes_id
    //     }  

    //     //primero se manda la info y después con la respuesta, las imagenes.
    //     token = getC('mytoken')
    //     const rawResponse = await fetch(server_name+'/api/registro_entidad', {
    //         method: 'POST',
    //         headers: {
    //             'Accept': 'application/json',
    //             'Content-Type': 'application/json',
    //             'Authorization': `Bearer ${token}`,    
    //         },
    //         body: JSON.stringify(data),
    //         credentials:'include',
    //     });
    //     const content = await rawResponse.json();
    
    //         alert(JSON.stringify(content['response']))

    //         if(content['code'] == 200){
    //             entidad  = content['entidad_id'];
    //             sendImage(entidad);


    //         }    

      

    // }

</script>

@endsection

@endsection
