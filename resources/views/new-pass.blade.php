@extends('layout.app')
@section('header')



<style>
.min{
  display: none;
}
</style>
  
@endsection

@section('contenido')

<div class="container-fluid">

  @if (session('success'))
  <div class="alert alert-success">
      {{ session('success') }}
  </div>
@endif


<div class="row d-felx justify-content-center mt-5">
  <form id="fs" action="{{route('setNewPass')}}" method="POST">
    @csrf
<div class="col-md-6 offset-md-3 border p-4 mt-5">
  <label for="nueva">Nueva contraseña</label>
  <input type="password" name="contra" id="contra" class="form-control col-md-3" >

  <label for="nuevaconfirmar">Confirmar nueva contraseña</label>
  <input type="password" name="nuevaconfirmar" id="nuevaconfirmar" class="form-control col-md-3" >
 
  <p  class="text-danger min" style="font-size:14px">*La nueva contraseña debe tener al menos 8 digitos</p>
  <input hidden name="token_r" value="{{$token_r}}">
  <div class="row d-flex justify-content-center mt-3 mb-2">

    <button type="submit" id="sb" class="btn btn-primary col-md-4" >Cambiar contraseña</button>
  </div>
 
</div>
</form>

</div>


</div>


@endsection


@section('scripts')
<script src="https://www.google.com/recaptcha/api.js"></script>
<script>

const contra  = document.getElementById('contra')
const nuevaconfirmar  = document.getElementById('nuevaconfirmar')
const pmin = document.querySelector('.min')


console.log(pmin)

document.getElementById('sb').addEventListener('click',
function(event){
  event.preventDefault();

  if(contra.value!=nuevaconfirmar.value){
    alert("Las contraseñas deben coincidir")
  }else{
    if(contra.value=="" || contra.value.length<8){
      alert("La contraseña debe ser mayor a 8")
      pmin.style.display = "block"
    }else{
      document.querySelector('#fs').submit();

    }
  }
  
 
})


</script>
@endsection