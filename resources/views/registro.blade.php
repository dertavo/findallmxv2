@extends('layout.app')
@section('header')

<style>

.none {
    display: none;
    width: 100%;
    margin-top: .25rem;
    font-size: .875em;
    color: #dc3545;
}
.btn-google {
  display: flex;
  align-items: center;
}

.google-icon {
  display: inline-block;
  width: 20px;
  height: 20px;
  margin-right: 10px;
  background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' viewBox='0 0 48 48'%3E%3Cdefs%3E%3Cpath id='a' d='M44.5 20H24v8.5h11.8C34.7 33.9 30.1 37 24 37c-7.2 0-13-5.8-13-13s5.8-13 13-13c3.1 0 5.9 1.1 8.1 2.9l6.4-6.4C34.6 4.1 29.6 2 24 2 11.8 2 2 11.8 2 24s9.8 22 22 22c11 0 21-8 21-22 0-1.3-.2-2.7-.5-4z'/%3E%3C/defs%3E%3CclipPath id='b'%3E%3Cuse xlink:href='%23a' overflow='visible'/%3E%3C/clipPath%3E%3Cpath clip-path='url(%23b)' fill='%23FBBC05' d='M0 37V11l17 13z'/%3E%3Cpath clip-path='url(%23b)' fill='%23EA4335' d='M0 11l17 13 7-6.1L48 14V0H0z'/%3E%3Cpath clip-path='url(%23b)' fill='%2334A853' d='M0 37l30-23 7.9 1L48 0v48H0z'/%3E%3Cpath clip-path='url(%23b)' fill='%234285F4' d='M48 48L17 24l-4-3 35-10z'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-size: contain;
}

  </style>

@endsection

@section('contenido')

<div class="container-fluid">

<form id="form-registro" class="row g-3 border">
  @csrf
  <div class="row mt-2">

    <div class="col-md-4">
      <label for="nombre" class="form-label">Nombre de usuario</label>
      <input required type="text" class="form-control" id="username" name="username">
      <div id="erru" class="none">
       Elige un nombre sin espacios
      </div>
      <div id="erru2" class="none">
        Elige un nombre con al menos 5 letras
       </div>
    </div>
    <div class="col-md-4">
      <label for="email" class="form-label">Correo eletrónico</label>
      <input type="email" class="form-control" id="email" name="email">
      <div id="errc" class="none">
        Elige un correo electrónico válido
       </div>
    </div>
    
  </div>

  {{-- <div class="col-md-4">
    <label for="nombre" class="form-label">Nombre</label>
    <input  type="text" class="form-control" id="nombre" name="nombre">
  </div>
  <div class="col-md-4">
    <label for="ap" class="form-label">Apellido paterno</label>
    <input type="text" class="form-control" id="ap" name="ap">
  </div>
 <div class="col-md-4">
    <label for="am" class="form-label">Apellido materno</label>
    <input type="text" class="form-control" id="am" name="am">
  </div>
  
  <div class="col-12">
    <label for="direccion" class="form-label">Dirección</label>
    <input type="text" class="form-control" id="direccion" name="direccion">
  </div>
 
  <div class="col-md-6">
    <label for="ciudad" class="form-label">Ciudad</label>
    <input type="text" class="form-control" id="ciudad" name="ciudad">
  </div>
  <div class="col-md-4">
    <label for="estado" class="form-label">Estado</label>
    <select id="estado" class="form-select" name="estado"> 
      <option selected>Elegir estado</option>
      <option>...</option>
    </select>
  </div>
  <div class="col-md-2">
    <label for="cp" class="form-label">Código postal  </label>
    <input type="text" class="form-control" id="cp" name="cp">
  </div>
   --}}
  <div class="col-md-4">
    <label for="password" class="form-label">Contraseña</label>
    <input type="password" class="form-control" id="password" placeholder="*************" name="password">
  </div>
 

  <div class="col-md-4">
    <label for="confirmpass" class="form-label">Confirmar contraseña</label>
    <input type="password" class="form-control" id="confirmpass" name="confirmpass">
  </div>

  <div class="row">
    {{-- <div class="col-md-4">
      <label for="email" class="form-label">Correo eletrónico</label>
      <input type="email" class="form-control" id="email" name="email">

    </div> --}}
    {{-- <div class="col-md-3">
      <label for="email" class="form-label">Télefono</label>
      <input type="number" class="form-control" id="telefono" name="telefono">
    </div> --}}
    
  </div>



  {{-- <div class="col-md-3">
    <div class="form-check mt-4 pl-4">
      <input class="form-check-input" type="checkbox" id="gridCheck">
      <label class="form-check-label" for="gridCheck">
        Datos públicos
      </label>
    </div>
  </div> --}}
   
  <div class="col-12 d-flex justify-content-center  mt-4">
    <button id="btn-registro" type="submit" class="btn btn-primary g-recaptcha"
    data-sitekey="6Lcscg4lAAAAALTLCW6oE85a7xesc1rc2pnXopzk" 
    data-callback='onSubmit' 
    data-action='submit'
    >Registrarse</button>
    
  </div>
  <div class="col-md-12 d-flex justify-content-center">
    o
  </div>

  <div class="col-12 d-flex justify-content-center mb-4 ">
    
    <a id="btn-google" href="{{route('login.google')}}" class="btn btn-outline-primary btn-google">
      <span class="google-icon"></span>
      Continuar con Google
    </a>
    

   
    {{-- <button type="submit" class="c28fbf930 c66190dcc cf5f21cd2" data-provider="google" data-action-button-secondary="true">
                    
      <span class="cc555f487 c4001df5f" data-provider="google"></span>
    
  
    <span class="cd00cd935">Continue with Google</span>
  </button> --}}

  </div>
 

</form>





</div>


@endsection




@section('scripts')
<script src="https://www.google.com/recaptcha/api.js"></script>




<script>
  function onSubmit(token) {
    document.getElementById("btn-registro").submit();
  }
</script>

<script src="{{asset('js/setLog.js')}}"></script>

<script>

function setToken(content){
 
 var url = '{{ route("/") }}';
 setLog(content,url);
}
</script>

@php

if(isset($token)){

echo "<script> content ={usuario:$usuario,token:'$token',username:'$username'}; setToken(content) </script>";

}
    
@endphp

<script>




p1 = document.getElementById('password')
p2 = document.getElementById('confirmpass')

username = document.getElementById('username');
var regex = /^\S+$/;
var regex2 = /^\S.{4,}$/;
erru = document.getElementById('erru')
erru2 = document.getElementById('erru2')
email = document.getElementById('email')


username.addEventListener('input',function(){
  if (!regex2.test(username.value)) {
    erru2.style.display ="block"
    return false
  }else{
    erru2.style.display ="none"
  }

  if (!regex.test(username.value)) {
 
    erru.style.display ="block"
    
    return false;
  }else{
    erru.style.display ="none"
  
  }
})

email.addEventListener('input',function(){
  
  if (!validarEmail(email.value)) {
    console.log("ssss")
    errc.style.display ="block"

    return false;
  }else{
    console.log("noo")
    errc.style.display ="none"
  }
})


var validado = false;

btn_registro = document.getElementById('btn-registro')
//form_registro = document.getElementById('form_registro')

btn_registro.addEventListener('click',function(e){
    e.preventDefault();
    // var formEl = document.forms.form_registro;
    f = document.getElementById('form-registro')
    var formData = new FormData(f);
    registro(formData)

})

function validarEmail(email) {
  const expresionRegular = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
  return expresionRegular.test(email);
}

function buildJsonFormData(form){
    const jsonFormData = {};
    let valido =0;
    for(const pair of new FormData(form)){
        if(pair[1] == ""){
            valido++;
            return valido;
        }else{
            jsonFormData[pair[0]] = pair[1];
        }
      

    }
    return jsonFormData;
}

function samePass(){

    auxp1 = p1.value
    auxp2 = p2.value


    if(auxp1!="" || auxp2!=""){
   
      if(auxp1==auxp2){
          validado=true
          
      }
    }
   
}

async function registro(formData){

  err=[]

  if (!regex2.test(username.value)) {
    err.push("El nombre de usuario debe tener al menos 5 letras")

  }

  if (!regex.test(username.value)) {
    err.push("El nombre de usuario no puede contener espacios en blanco  o estar vacío");
  }
  if (!validarEmail(email.value)) {
  err.push("La dirección de correo electrónico no es válida");
  }
  samePass()    
  if(validado == false){
    err.push("Las contraseñas deben coincidir");
  }

  if(err.length>0){
    alert(err)
    return false
  }

    
  console.log(formData)
  //   datos = buildJsonFormData(formData);
  //   console.log(datos)
  //   console.log(formData)
  //  if(datos>0  || validado == false){
  //      alert("Deberá llenar todos los campos del registro para continuar")
  //   }else{

    let ruta = server_name + '/api/registro'

    const rawResponse = await fetch(ruta, {
            method: 'POST',
            headers: {
                // 'Accept': 'application/json',
                // 'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData,
        });
        const content = await rawResponse.json();
        console.log(content)

        if(content['response'] =='success'){
          alert("Registrado con éxito")
          alert(content['validado'])
          window.location.href = "{{route('login')}}"
            // console.log(content)
            // var url = '{{ route("/") }}';
            // setLog(content,url);
            //alert("Iniciando Sesión")
            //window.location.href=url;

          //window.location.href = server_name+ '/findall/public/'
          
        }else{


          //alert("Debe verificar que todos los datos ingresados sean correctos");
          //alert(JSON.stringify(content['response']))
          console.log(content['response'])

          if(content['response']['password'])
          alert("La contraseña debe tener al menos 8 caracteres")


          if(content['response']['username'])
          alert("El nombre de usuario que ingresó ya está en uso")

          if(content['response']['email'])
          alert("El correo que ingresó ya está en uso")
        }

        // console.log(content)
  //  }

     

}





</script>


@endsection