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
        <form id="form-edit" enctype='multipart/form-data'>
        @method('PUT')
        @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nombre, animal o cosa</label>
                <input 
                placeholder="Nombre de la persona u objecto extravíado"
                required name="name" type="text" class="form-control" id="name" aria-describedby="emailHelp">
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Descripción</label>
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
                <input multiple="multiple" class='btn btn-light' type="file" name="files[]"  id="img-entidad" accept="image/jpeg,image/png">
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
    <button id="sf" class="btn btn-primary">Actualizar entidad</button>
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



    
  <script src="{{asset('js/form_entidad.js')}}"></script>




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

        var formElement = document.getElementById("form-edit");
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

       console.log(JSON.stringify(formData))



       const rawResponse = await fetch(rutaex, {
            method: 'POST',
            headers:{
              'Authorization': `Bearer ${token}`,
            },
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

 

</script>

@endsection

@endsection
