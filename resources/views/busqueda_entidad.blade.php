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
<div class="col-md-5">
<p class="h3 mb-4" id="cantidadEntidades">Resultados de la búsqueda '{{$searched}}' ({{count($entidades)}})

<p>
</div>


</div>




<div class="container-fluid"  >

<div class="row" id="placeview" >

    @foreach ($entidades as $entidad)
    @php
$imageUrl = asset('\storage\entidades\\').$entidad->archivo;
$imageUrl= "https://storage.googleapis.com/findall_bucket/" . $entidad->archivo;
    @endphp

    <div class="d-flex justify-content-center">
        <div class="card" style="width: 18rem;">
            <img src="{{$imageUrl}}" class="card-img-top" alt="...">
            <div class="card-body">
              <h5 class="card-title">{{$entidad->nombre}}</h5>
              <p class="card-text">{{$entidad->descripcion}}</p>
              <a href="{{route('detalles_entidad',$entidad->entidad_id)}}" class="btn btn-primary">Detalles</a>
            </div>
          </div>
    </div>

    @endforeach
    
   

 

</div>
</div>

@endsection

@section('scripts')
    <script src="{{asset('js/getToken.js')}}"></script>

    


    
@endsection