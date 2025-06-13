@extends('layout.app')
@section('header')

<style>
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

<div class="container-fluid ">


  @if ($message = Session::get('success'))

  <div class="alert alert-info alert-dismissible fade show" role="alert">
    <strong>{{$message}}
    </strong>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  
  @endif 

<form id="form-registro" class="row g-3 border mt-1">
 @csrf
  <div class="col-md-3">
    <label for="email" class="form-label">Correo eletrónico</label>
    <input placeholder="example@example.com" type="email" class="form-control" id="email" name="email">
  </div>

  
  <div class="col-md-3 position-relative">
    <label for="password" class="form-label">Contraseña</label>
    <div class="input-group">
        <input type="password" class="form-control password-toggle" id="password" placeholder="*************" name="password" aria-describedby="toggle-password">
        <button class="btn btn-outline-secondary password-toggle-button" type="button" id="toggle-password" onclick="togglePasswordVisibility()">
          <i class="fa fa-eye-slash"></i>
        </button>
      </div>

</div>
<div class="col-md-3">
  <label class="form-label mt-4 link-primary" style="cursor: pointer;">
    <a href="{{route('recovery')}}">¿Has olvidado tu contraseña?</a>
 
  </label>
    
</div>
 

  
  <div class="col-12 d-flex justify-content-center  mt-4">
    <button id="btn-registro" type="submit" class="btn btn-primary">Iniciar sesión</button>
  </div>

  <div class="col-md-12 d-flex justify-content-center">
    o
  </div>

  <div class="col-12 d-flex justify-content-center mb-4 ">
    
    <a id="btn-google" href="{{route('login.google')}}" class="btn btn-outline-primary btn-google">
      <span class="google-icon"></span>
      Continuar con Google
    </a>
    
</form>


</div>


@endsection


@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>


<script src="{{asset('js/setLog.js')}}"></script>

<script>

user = getC('username');


redirect_to="https://findallmx.herokuapp.com/"

redirect_to ="/";

if(user!=""){
console.log(server_name)
if(window.location.host !="localhost"){
  window.location.href= redirect_to
}else{
  
}

}


function togglePasswordVisibility() {
    var passwordInput = document.getElementById('password');
    var passwordToggle = document.getElementById('toggle-password');
    if (passwordInput.type === 'password') {
      passwordInput.type = 'text';
      passwordToggle.innerHTML = '<i class="fa fa-eye" aria-hidden="true"></i>';
    } else {
      passwordInput.type = 'password';
      passwordToggle.innerHTML = '<i class="fa fa-eye-slash"></i>';
    }
  }

axios.get(server_name+'/public/sanctum/csrf-cookie').then(response => {
    // Login...
    console.log(response);
});


btn_registro = document.getElementById('btn-registro')
//form_registro = document.getElementById('form_registro')

btn_registro.addEventListener('click',function(e){
    e.preventDefault();
    var formEl = document.forms.form_registro;
    f = document.getElementById('form-registro')
    var formData = new FormData(formEl);
    registro(f)

})

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

async function registro(formData){

    datos = buildJsonFormData(formData);

    if(datos>0){
        alert("Deberá llenar todos los campos del login para continuar")
    }else{

        let ruta = server_name+'/api/login'
        console.log(ruta)
    const rawResponse = await fetch(ruta, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(datos),
        });
      
        const content = await rawResponse.json();
        console.log(content)
        token = content['token']
        console.log(content)
        if(token=="" || content['validado']!=true){
            //no regresó token
        alert(content['validado']);
        }else{
            var url = '{{ route("/") }}';
            setLog(content,url); 
            //alert("Iniciando Sesión")
            
        }


        
    }

     

}



</script>


@endsection