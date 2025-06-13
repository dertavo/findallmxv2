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
{{-- <meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container rounded bg-white mt-5 mb-5">
    <div class="row">

       
         <div class="col-md-3 border-right">
            <div class="d-flex flex-column align-items-center text-center p-3 py-5"><img class="rounded-circle mt-5" width="150px" src="https://st3.depositphotos.com/15648834/17930/v/600/depositphotos_179308454-stock-illustration-unknown-person-silhouette-glasses-profile.jpg"><span class="font-weight-bold">Edogaru</span><span class="text-black-50">edogaru@mail.com.my</span><span> </span></div>
        </div>
        <div class="col-md-8 border-right">
            <div class="p-3 py-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="text-right">Profile Settings</h4>
                </div>
                <div class="row mt-2">
                    <div class="col-md-4"><label class="labels">Nombre</label>
                    <input id="name" type="text" class="form-control" placeholder="first name"
                   
                    ></div>
                    <div class="col-md-4"><label class="labels">Apellido paterno</label>
                    <input type="text" class="form-control"  placeholder=""
            
                    >
                    </div>
                    <div class="col-md-4"><label class="labels">Apellido materno</label>
                        <input type="text" class="form-control"  placeholder=""></div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12"><label class="labels">Dirección</label>
                        <input type="text" class="form-control" placeholder="" value="">
                    </div>
                    <div class="col-md-4"><label class="labels">Ciudad</label><input type="text" class="form-control" placeholder="enter address line 1" value=""></div>
                    <div class="col-md-4"><label class="labels">Estado</label><input type="text" class="form-control" placeholder="enter address line 2" value=""></div>
                    <div class="col-md-4"><label class="labels">Código Postal</label><input type="text" class="form-control" placeholder="enter address line 2" value=""></div>
                    <div class="col-md-6"><label class="labels">Télefono</label><input type="text" class="form-control" placeholder="enter address line 2" value=""></div>
                    <div class="col-md-6"><label class="labels">Correo electrónico</label><input type="text" class="form-control" placeholder="enter address line 2" value=""></div>
                </div>
                <div class="mt-5 text-center"><button class="btn btn-primary profile-button" type="button">Save Profile</button></div>
            </div>
        </div>
        {{-- <div class="col-md-4">
            <div class="p-3 py-5">
                <div class="d-flex justify-content-between align-items-center experience"><span>Edit Experience</span><span class="border px-3 p-1 add-experience"><i class="fa fa-plus"></i>&nbsp;Experience</span></div><br>
                <div class="col-md-12"><label class="labels">Experience in Designing</label><input type="text" class="form-control" placeholder="experience" value=""></div> <br>
                <div class="col-md-12"><label class="labels">Additional Details</label><input type="text" class="form-control" placeholder="additional details" value=""></div>
            </div>
        </div> --}}
    {{-- </div>
</div> --}} 

<div id="example"></div>


@endsection

@section('scripts')

<script src="{{asset('js/app.js')}}"></script>


    

    <script>

token = getC('mytoken')
user = getC('user')
let ruta = server_name + '/api/my-profile'

//callProfile()


function drawData(){

}

async function callProfile(){
    const rawResponse = await fetch(ruta, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`,
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({'user':user}),
            credentials:'include',
        });
        const content = await rawResponse.json();
        
        if(content['response']=="Success"){
            drawData()
        }else{
            console.log(content)
            alert("Ha ocurrido un error, vuelva a intentarlo");
        }
}

        </script>
@endsection