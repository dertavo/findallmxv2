@extends('layout.app')

@section('header')

<style>
 .imagenesproducto{
 
  max-width:100%;
 
}
.btn-circle {
  border-radius: 50%;
  width: 40px;
  height: 40px;
  padding: 8px;
  text-align: center;
  font-size: 15px;
  line-height: 1.428571429;
}
</style>
    
@endsection

@section('contenido')


<p class="h3 mb-4" id="cantidadEntidades">Entidades encontradas


<div class="container-fluid" >
<div class="row" id="placeview">
@if ($message = Session::get('success'))

<div class="alert alert-info alert-dismissible fade show" role="alert">
  <strong>{{$message}}
  </strong>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

@endif
<table class="table">
  <thead>
    <tr>
    <th scope="col">Estatus</th>
      <th scope="col">Entidad encontrada</th>
      <th scope="col">Usuario responsable</th>
      <th scope="col">Mi descripción</th>
      <th scope="col">Mi evidencia</th>
      <th scope="col">Información de contacto</th>
      <th scope="col">Acciones</th>
    </tr>
  </thead>
  <tbody>
    @foreach($entitys as $key => $entity)
    @php
    $hand="";
    if($entity->handshake==1){
        $hand="table-primary";
    } 
    $status ="";  
    if($entity->entity_status == 'aceptado'){
      $status="table-primary";
    }
    if($entity->entity_status == 'rechazado'){
      $status="table-danger";
    }
    if($entity->entity_status == 'revision'){
      $status="table-warning";
    }
    @endphp
        <tr class ="{{$hand}}">
       


        <td class ="{{$status}}">{{$entity->entity_status}}</td>
        <td>
        <a  href="{{route('detalles_entidad',['entidad'=>$entity->id])}}">
        {{$entity->nombre}}
        </a>
        </td>
       
        <td>
        <a href="{{route('contact-profile',['user'=>$entity->destino_user])}}">
        {{$entity->username}}
        </a>
        </td>

        <td>
          <p>{{$entity->mides}} </p>
        </td>

        <td class="col-md-2">
          <span style="cursor: pointer" onclick="openModal('{{$entity->archivo}}')" 
            class="badge rounded-pill bg-info text-dark">
            Ver imagen completa
        </span>
        @php
        $ruta = "https://storage.googleapis.com/findall_bucket/" . $entity->archivo;
        $imgid = "img".$entity->id;
        @endphp
        <img  id="{{$imgid}}" onclick="toggleFullScreen({{$imgid}})" src="{{$ruta}}" class="img-thumbnail" width="50">
      </td>

        @if($entity->entity_status == "aceptado")
        <td >
          <form target="_blank"  method="post" action="{{route('chat')}}">
            @csrf
          <button  class="btn btn-outline-success">
            <input hidden name="destino_user" value="{{$entity->destino_user}}">
            <input hidden name="destino_username" value="{{$entity->username}}">
            <input hidden name="entidad_id" value="{{$entity->id}}">

            <i style="font-size: 20px; color:black" class="fas fa-eye"></i>
            Ver
         </button>
        </form>

        </td>
        @else
        <td>
          <span class="badge rounded-pill bg-warning text-dark">No hay información.</span>
        </td>
        @endif
       
       
        <td>
 
        @if($entity->entity_status == "rechazada" || $entity->entity_status == "revision")
       
        <a id="btnEl" onclick="hola({{$entity->prueba_id}})" class="btn btn-danger">Eliminar</a>
        @endif
      </td>
        </tr>
    @endforeach

  </tbody>
</table>

</div>
</div>


<div class="modal" tabindex="-1" id="myModal">
  <div class="modal-dialog modal-fullscreen-sm-down">
    <div class="modal-content">
      
      <div class="modal-body">
       
        <img   class="img-fluid" id ="img-file" src="">
        <p id="no"><p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">OK</button>

      </div>
    </div>
  </div>
</div>

</div>
</div>

@endsection

@section('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
var myModalEl = document.querySelector('#myModal')
img_file = document.querySelector('#img-file')
var modal = bootstrap.Modal.getOrCreateInstance(myModalEl)

function openModal(file){
  
  var publicPath = "{{ asset('') }}";

  imageUrl = server_img + file
  img_file.src = imageUrl

  modal.show()
  
}

async function delEvidence(evidence){
  token = getC('mytoken')
  ruta = server_name+'/api/delEvidence/'+evidence
  console.log(ruta)
    const rawResponse = await fetch(ruta, {
    method: 'GET',
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      'Authorization': `Bearer ${token}`,
    },
    
  });
 let response = await rawResponse.json();

 if(response['code']==200){
    alert("Evidencia eliminada con éxito")
 }else{
  alert(response['response'])
 }
 location.reload();
}


function toggleFullScreen(id) {
          
  id.requestFullscreen()
  }

// var hola = document.getElementById('btnEl')

function hola(evidence){
  Swal.fire({
  title: '¿Seguro que quieres eliminar la evidencia de la entidad encontrada?',
  showDenyButton: true,
  showCancelButton: false,
  confirmButtonText: 'Sí, estoy seguro',
  denyButtonText: 'Corroborar',
  position: 'center',
}).then((result) => {

  /* Read more about isConfirmed, isDenied below */
  if (result.isConfirmed) {
  //  var url = '{{ route("contacto_entidad", ":slug") }}';
  //  url = url.replace(':slug', elid);
  //  window.location.href=url;
    delEvidence(evidence)

  } else if (result.isDenied) {
  
  }
})
}


</script>
    
@endsection