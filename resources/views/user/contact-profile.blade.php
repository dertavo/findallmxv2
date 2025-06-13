@extends('layout.app')

@section('header')

<style>
 .imagenesproducto{
 
  max-width:100%;
 
}

body {
    /* background: rgb(99, 39, 120) */
}

.form-control:focus {
    box-shadow: none;
    border-color: #BA68C8
}

.profile-button {
    background: rgb(99, 39, 120);
    box-shadow: none;
    border: none
}

.profile-button:hover {
    background: #682773
}

.profile-button:focus {
    background: #682773;
    box-shadow: none
}

.profile-button:active {
    background: #682773;
    box-shadow: none
}

.back:hover {
    color: #682773;
    cursor: pointer
}

.labels {
    font-size: 11px
}

.add-experience:hover {
    background: #BA68C8;
    color: #fff;
    cursor: pointer;
    border: solid 1px #BA68C8
}

</style>
    
@endsection

@section('contenido')
<div class="container rounded bg-white mt-5 mb-5">
    <div class="row">

       
         <div class="col-md-3 border-right">
            <div class="d-flex flex-column align-items-center text-center p-3 py-5"><img class="rounded-circle mt-5" width="150px" src="https://st3.depositphotos.com/15648834/17930/v/600/depositphotos_179308454-stock-illustration-unknown-person-silhouette-glasses-profile.jpg"><span class="font-weight-bold">{{$user->nombre}} {{$user->ap}}</span>
                <span style="word-break:break-all;" class="text-black-50">{{$user->email}}</span>
                <span> </span></div>
        </div>
        @if($user->public_info)
        <div class="col-md-8 border-right">
            <div class="p-3 py-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="text-right">Profile Settings</h4>
                </div>
                <div class="row mt-2">
                    <div class="col-md-4"><label class="labels">Nombre</label>
                    <input value="{{$user->nombre}}"  disabled id="name" type="text" class="form-control" placeholder="first name"
                   
                    ></div>
                    <div class="col-md-4"><label class="labels">Apellido paterno</label>
                    <input value="{{$user->ap}}"  disabled type="text" class="form-control"  placeholder=""
            
                    >
                    </div>
                    <div class="col-md-4"><label class="labels">Apellido materno</label>
                        <input value="{{$user->am}}"  disabled type="text" class="form-control"  placeholder=""></div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12"><label class="labels">Dirección</label>
                        <input value="{{$user->direccion}}"  disabled type="text" class="form-control" placeholder="" value="">
                    </div>
                    <div class="col-md-4"><label class="labels">Ciudad</label><input value="{{$user->ciudad}}"  disabled type="text" class="form-control" placeholder="" value=""></div>
                    <div class="col-md-4"><label class="labels">Estado</label><input value="{{$user->estado}}"  disabled type="text" class="form-control" placeholder="" value=""></div>
                    <div class="col-md-4"><label class="labels">Código Postal</label><input value="{{$user->cp}}"   disabledtype="text" class="form-control" placeholder="" value=""></div>
                    <div class="col-md-5"><label class="labels">Télefono</label><input value="{{$user->telefono}}"   disabled type="text" class="form-control" placeholder="" value=""></div>
                    <div class="col-md-6"><label class="labels">Correo electrónico</label><input value="{{$user->email}}"  disabled type="text" class="form-control" placeholder="" value=""></div>
                </div>
            </div>
        </div>
        @endif
      
    </div>
</div>


@endsection

@section('scripts')
 
@endsection