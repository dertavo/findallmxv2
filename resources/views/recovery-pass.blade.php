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

<div class="container-fluid   ">
<form action="{{route('recoveryPass')}}" method="post">
@csrf
<div class="row d-felx justify-content-center mt-5 border">

  @if (session('response'))
  <div class="alert alert-success">
      {{ session('response') }}
  </div>
@endif
  
<div class="col-md-6 border p-4 mt-5">
  <label for="email">Correo de recuperación</label>
  <input required type="email" name="email" id="email" class="form-control col-md-3" placeholder="Escribe tu correo para recuperar tu contraseña">
 
  <div class="row d-flex justify-content-center mt-3 mb-2">

    <button class="btn btn-primary col-md-2">Enviar</button>
  </div>
 
</div>

</div>
</form>

</div>


@endsection


@section('scripts')
<script src="https://www.google.com/recaptcha/api.js"></script>
@endsection