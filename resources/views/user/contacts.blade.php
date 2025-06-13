@extends('layout.app')

@section('header')

<style>
 .imagenesproducto{
 
  max-width:100%;
 
}
</style>
    
@endsection

@section('contenido')


<div class="row">
<div class="col-md-4">
<p class="h3 mb-4" id="cantidadEntidades">Solicitudes de evidencia ({{$countEvidence}})

<p>
</div>
<div class="col-md-6">
<p class="h3 mb-4" id="cantidadEntidades"> 
<a class="link-info" href="{{route('registro_entidad',['id'=>$entidad->id])}}">
{{$entidad->nombre}} ({{$entidad->id}})

</a>

</div>

</div>




<div class="container-fluid"  >

<div class="row" id="placeview" >

<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Descripción</th>
      <th scope="col">Fecha</th>
      <th scope="col">Archivo de evidencia</th>
      <th scope="col">Username</th>
      <th scope="col">Acciones</th>
    </tr>
  </thead>
  <tbody>
    @foreach($contacts as $key => $contact)
        <tr>
        <th scope="row">{{$contact->id}}</th>
        <td>{{$contact->descripcion}}</td>
        <td>{{$contact->prueba_fecha}}</td>
        <td class="col-md-2">
          <span style="cursor: pointer" onclick="openModal('{{$contact->archivo}}')" 
            class="badge rounded-pill bg-info text-dark">
            Ver imagen completa
        </span>
        @php
        $ruta = "https://storage.googleapis.com/findall_bucket/" . $contact->archivo;
        $imgid = "img" . $contact->id;
       
        @endphp
        <img id="{{$imgid}}" onclick="toggleFullScreen({{$imgid}})" src="{{$ruta}}" class="img-thumbnail" width="50">
        <td>
        <a href="{{route('contact-profile',['user'=>$contact->contact_user])}}">
        {{$contact->username}}
        </a>
        <td class="d-flex align-items-center">
        @if($contact->handshake!=1)

        {{-- <a onclick="accepted({{$contact->id}})" class="btn btn-success" href="{{route('accepted',['contact'=>$contact->id])}}">Aceptar</a> --}}
        @if($contact->status!='aceptado')
        <a onclick="accepted({{$contact->id}})" class="btn btn-success" >Aceptar</a>
        @else
        <span class="badge rounded-pill bg-info ms-2">Solicitud aceptada</span>
        @endif
        @if($contact->status!='rechazada')
        <a class="btn btn-danger ms-2" href="{{route('declined',['contact'=>$contact->id])}}">Rechazar</a>
        @endif
        @else
        <span class="badge rounded-pill bg-success ms-2">Objecto encontrado</span>
        @endif

        @if($contact->status == "rechazada")
        <span class="badge rounded-pill bg-secondary ms-2">Lo rechaste</span>
        @endif
        @if($contact->status == "aceptado")
        <form target="_blank"  method="post" action="{{route('chat')}}">
          @csrf
        <button  class="btn btn-outline-success ms-2">
          <input hidden name="destino_user" value="{{$contact->contact_user}}">
          <input hidden name="destino_username" value="{{$contact->username}}">
          <input hidden name="entidad_id" value="{{$contact->entidad_id}}">
          <i style="font-size: 20px; color:black" class="fas fa-eye"></i>
          Ver
       </button>
      </form>
        @endif
        @if($contact->status == "aceptado")
        
        @if($contact->handshake ==0)

         <a class="btn btn-primary ms-2 " href="{{route('handshake',['contact'=>$contact->id])}}">
         Finalizar
         </a>
        @endif

        
    @endif
        </td>
      
        </tr>
    @endforeach

  </tbody>
</table>

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

function accepted(contact){
  Swal.fire({
  title: 'Al confirmar,  aceptas que la solicitud coincide con tu objecto perdido y aceptas compartir tu información, para que se contacten contigo',
  showDenyButton: true,
  showCancelButton: false,
  confirmButtonText: 'Sí, estoy seguro',
  denyButtonText: 'Corroborar',
}).then((result) => {

  /* Read more about isConfirmed, isDenied below */
  if (result.isConfirmed) {
   var url = '{{ route("accepted", ":contact") }}';
   url = url.replace(':contact', contact);
    window.location.href=url;
  } else if (result.isDenied) {
  
  }
})
}

var myModalEl = document.querySelector('#myModal')
var modal = bootstrap.Modal.getOrCreateInstance(myModalEl)

img_file = document.querySelector('#img-file')

img_file.addEventListener('click',function(){

  img_file.requestFullscreen()

})

myModalEl.addEventListener('hidden.bs.modal', function (event) {
  // do something...

 img_file.src="";
})


function toggleFullScreen(id) {
          
          id.requestFullscreen()
  }

function openModal(file){
  
  var publicPath = "{{ asset('') }}";

  var imageUrl = publicPath + "storage/pruebasContacto/" + file;
  imageUrl = server_img + file
  img_file.src = imageUrl

  modal.show()
  
}



function getC(n){
  const value = `; ${document.cookie}`;
  const parts = value.split(`; ${n}=`);
  if (parts.length === 2) return parts.pop().split(';').shift();
  else
  return "";
}

</script>
    
@endsection