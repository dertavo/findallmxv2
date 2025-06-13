@extends('layout.app')

@section('header')

<style>

</style>
    
@endsection

@section('contenido')
<div class="container-fluid mt-4">
  <h5>A continuación te pedimos añadas evidencia de la entidad encontrada:</h5>

<div class="col-md-6">
<form id ="form-r" class="" method="post"  enctype='multipart/form-data'>
@csrf
{{-- <label>Entidad contacto</label> --}}
<input hidden class="form-control" required name="entidad_contacto" value="{{$entidad}}" />

{{-- <label>Destino contacto</label> --}}
<input hidden  class="form-control" required name="usuario_destino" value="{{$usuario_destino->id}}" />



{{-- <label>Contacto</label> --}}
<input hidden id="input_contacto" class="form-control" required name="usuario_contacto" />

<label>Descripción</label>

<textarea class="form-control" placeholder="Escribe una descripción de lo que encontraste" 
required name="descripcion" rows="5"></textarea>



<label class="mt-2">Archivo (evidencia)</label>
<input hidden class="form-control" required name="archivo" />


<div class="form-group">
        <div class="form-check-inline">
       

            <div class='file-input'>
                <input multiple="multiple" class='btn btn-light' type="file" name="imagenes[]"  id="img-entidad" accept="image/jpeg,image/png">
            </div>
            
            <div class="scrollmenuv mt-5" id="galeria">
                <div class="gallery" id="ga2"></div>
            </div>
            
        </div>
        
  </div>



</div>


</form>


</div>
<div class="" style="">
  <div class="d-flex justify-content-center">
    <button id="sf" class ="btn btn-primary mt-5">Enviar</button>
  </div>
  
</div>
</div>
</div>
@endsection

@section('scripts')
 <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
 <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>


input_contacto = document.getElementById('input_contacto')
input_contacto.value = getC('user')
var divimg = document.getElementById('ga2')

  // Multiple images preview in browser
  var imagesPreview = function(input, placeToInsertImagePreview) {
    const imgElements = divimg.querySelectorAll('img')
    console.log(imgElements)
    if(imgElements.length>=2){
        alert("El máximo de imagenes a subir es de 2")
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

      if (input.files) {
          var filesAmount = input.files.length;
          console.log(filesAmount)
          for (i = 0; i < filesAmount; i++) {
              var reader = new FileReader();

              reader.onload = function(event) {
                $('#galeria').show();
                  $($.parseHTML('<img class="head-a imagenesproducto img-thumbnail">')).attr('src', event.target.result).appendTo(placeToInsertImagePreview);
              }
              reader.readAsDataURL(input.files[i]);
              console.log(input.files[i])
          }
      }
    
  };

  document.getElementById('img-entidad').addEventListener('change',function(){
        imagesPreview(this, 'div.gallery');
  })

  sf.addEventListener('click', function(e) {
    e.preventDefault();
    sf.disabled=true;
    Swal.fire({
        width: 200,
  timerProgressBar: true,
  allowOutsideClick: false,
  didOpen: () => {
    Swal.showLoading()
    // const b = Swal.getHtmlContainer().querySelector('b')
    // timerInterval = setInterval(() => {
    //   b.textContent = Swal.getTimerLeft()
    // }, 100)
  },
 
})

    respuesta = sendData();
})

  async  function sendData(){
    
       
         ruta = 'contact_user';

        var formElement = document.getElementById("form-r");
         formData = new FormData(formElement);

         full_path = server_name+'/api/'+ruta

         console.log(full_path)
       token = getC('mytoken')
      
       const rawResponse = await fetch(full_path, {
            method: "post",  
            body: formData,
            headers:{
              'Authorization': `Bearer ${token}`,
            },
        });
        Swal.close()
        const content = await rawResponse.json();
        console.log(content)
        sf.disabled=false;
       
        if(content['code'] == 200){
             alert(JSON.stringify(content['response']))
            
             let url = "{{ route('finded', ':user') }}";
            url = url.replace(':user', getC('user'));
             window.location.href= url;
        }
       
        else{
            alert(JSON.stringify(content['response']))
            //window.location.reload();
        }


    }

</script>
    
@endsection