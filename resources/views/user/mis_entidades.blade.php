@extends('layout.app')

@section('header')

<style>
 .imagenesproducto{
 
  max-width:100%;
  height: ;
 
}

.btnm:hover{
color:white !important;
}
.btnm:hover i{
  color:white !important;
}

@media screen and (max-width: 375px) {
  a.btn-danger {
    margin-top: 10px;
  }
}

</style>
    
@endsection

@section('contenido')

<div  class="loader"></div>

<h3 class="p-4" id="cantidadEntidades"></h3>



<div class="container-fluid" >
<div class="row" id="placeview">

</div>
</div>



@endsection

@section('scripts')
  
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

loader = document.querySelector('.loader');



window.CSRF_TOKEN = '{{ csrf_token() }}';
placeview = document.getElementById('placeview')

loadData();



async function drawInfo(info){

    //info = await loadData();

    opciones = ['perdido','encontrado','revision','sin definir'];
    opciones_dom="";
    aux_img=[];

    //console.log(info['imagenes'])
    
    
     info['imagenes'].forEach(function (dato,i){
        if(dato[0].length!=0){
         
          aux_img.push(dato[0][0]['archivo'])//solo toma 1 imagen por cada entidad 
        }

           
        });
    
    count = 0;
    view="";
    info['entidades'].forEach(function (value, i) {
        entidad_id = value['id']
        count++;
       
        //La logica de donde obtener la url, solo aplica a 2 casos. No se implementa la interface creada para determinar
        //desde que servicio se obtiene la imagen.
        
        const filename = '6uqV2NJkeFgu16If4O1Q7naGrIa0oAYNKUB8lRN4.jpg'; // Reemplaza con el nombre real del archivo

        // Construye la URL usando la ruta o el enlace simbólico (ver respuesta anterior)
        const src_img = `/storage/entidades/${filename}`;




        src = server_img+aux_img[i]+'';
       
        contacts = info['contacts'][i][0]
         opciones.forEach( dato =>{

        if(value['status'] == dato){
        opciones_dom += '<option value='+dato+' selected>'+dato+'</option>'
        }else{
        opciones_dom += '<option value='+dato+'>'+dato+'</option>'
        }
    
        });
        let url = "{{ route('editar_entidad', ':id') }}";
        url = url.replace(':id', value['id']);

        contact_url = "{{ route('contacts', ':id') }}";
        contact_url = contact_url.replace(':id', value['id']);
        status = value['status'].toUpperCase()

        view='<div class="col-md-4">'+
        '<div class="card mb-2 h-100">'+
        '<a href="'+url+'"><img alt="imagen entidad" src="'+src+'" class="card-img-top img-fluid" style="height: 300px;"></a>'+
        '<div class="card-body">'+
            '<h5 class="card-title">'+value['nombre']+'</h5>'+
            '<p class="card-text">'+value['descripcion']+'</p>'+
            
            '<a href="'+url+'" class="btn btn-primary" style="margin-right:10px">Detalles</a>'+
            '<a href="'+contact_url+'" class="btn btn-outline-warning btnm" style="margin-right:10px; color:black;">'+
            'Solicitudes <span class="badge bg-danger">'+contacts+'</span>'+
            '</a>'+
            '<a onclick="confirmAction('+entidad_id+')" class="btn btn-outline-danger btnm" style="margin-right:10px; color:black;">'+
             ' <i class="fa fa-trash" style="color:red"></i> Eliminar</a>'+
            '<div class="mb-3">'+
            '<label for="label_status" class="form-label mt-2">Estatus</label>'+
            // '<p>'+status+'</p>'+
            '<select onchange="changeStatus(event,'+entidad_id+')" class="form-select" aria-label="Default select example">'+
            '<option value="">Seleccionar</option>'+
            opciones_dom+
            '</select>'+
            '</div>'+
            
            '<button onclick="btnChange('+entidad_id+')" style="display:none" id="btnS'+entidad_id+'" class="btn btn-success ">Cambiar estatus</button>'+
        '</div>'+
        '</div>'+
        '</div>';

    placeview.innerHTML += view;
    opciones_dom=""

    });
  document.getElementById('cantidadEntidades').innerHTML = "Cantidad de entidades registradas ("+count+"/5): "
}





function confirmAction(entidad) {
  Swal.fire({
  title: '¿Seguro de eliminarlo?',
  text: "No serás capaz de revertir esta acción",
  icon: 'warning',
  showCancelButton: true,
  showLoaderOnConfirm: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Sí, ¡eliminalo!',
  cancelButtonText :"Cancelar",
}).then((result) => {
  if (result.isConfirmed) {
    deleteItem(entidad)
  }
})
}

async function deleteItem(entidad){

    console.log("deleting "+ entidad)
    token = getC('mytoken')
    const rawResponse = await fetch(server_name+'/api/deleteEntidad/'+entidad, {
    method: 'POST',
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': window.CSRF_TOKEN,
      'Authorization': `Bearer ${token}`,
    },
    
  });
 let response = await rawResponse.json();
 console.log(response)
if(response['error']){
  console.log(response)
  alert(response['response'])
}else{ 
  Swal.fire(
      'Eliminado',
      'Tu registro ha sido eliminado',
      'success'
    ).then((result) => {
  if (result.isConfirmed) {
    window.location.reload();
  }
})
   
}
  }
  
var valorOption;

async function btnChange(id){
let mybtn = document.getElementById('btnS'+id)
token = getC('mytoken')
ruta = server_name+'/api/changeStatusEntidad'
const rawResponse = await fetch(ruta, {
    method: 'POST',
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      
      'Authorization': `Bearer ${token}`,
    },
    body : JSON.stringify({"status":valorOption,"entidad":id}),
    credentials:'include',
    
  });

 let response = await rawResponse.json();
 console.log(response)
 mybtn.style.display = "none"
 if(response['code']==200){
    alert(response['response'])
 }else{
  alert(response['response'])
 }


}


function changeStatus(event,id){
  console.log(id)
  let mybtn = document.getElementById('btnS'+id)
  
  valorOption = event.target.value
  if(valorOption!=""){
    mybtn.style.display = "block"
  }else{
    mybtn.style.display = "none"
  }
}


async function loadData(){


id= getC('user');

token = getC('mytoken')
const rawResponse = await fetch(server_name+'/api/getEntidadesUser/'+id, {
    method: 'GET',
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      
      'Authorization': `Bearer ${token}`,
    },
  
    credentials:'include',
    
  });

  loader.style.visibility = "hidden";
 let response = await rawResponse.json();
 console.log(response)
if(response['error']){
  console.log(response)
  alert(response['response'])
}else{ 

  if(response['entidades'] == undefined){
    document.getElementById('cantidadEntidades').innerHTML = "Cantidad de entidades registradas (Actual): " + 0;
  }else{
    drawInfo(response)
  }

}

}



</script>
    
@endsection