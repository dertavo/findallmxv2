<style>.dropdown-item{color: black !important;}</style>

<nav class="navbar navbar-expand-lg navbar-light bg-light mb-4" >
  <div class="container-fluid">
    <a class="navbar-brand" href="{{route('/')}}">
    
      Findallmx
    
    </a>
    
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="{{route('/')}}">{{__('inicio')}}</a>
        </li>
        <li id="liregistro" class="nav-item"  style="display:none">
          <a class="nav-link active" href="{{route('registro_entidad')}}">{{__('registrar_entidad')}}</a>
       
        <li id="limisdatos" style="display:none" class="nav-item dropdown">
          <a class="nav-link dropdown-toggle active" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            {{__('mis_datos')}}
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li>
            <form >
            
            <input hidden id="inpuser" name="user" >
             <a type="submit" class="dropdown-item" href="{{route('proweb')}}">{{__('perfil')}} </a>
            </form>
           
            
            </li>
            {{-- el 1 aquí debería ser consultado de la sesión activa del usuario --}}
            <li><a  class="dropdown-item" href="{{route('entidades')}}">{{__('mis_entidades')}}</a></li>
             <li><a  class="dropdown-item" id="finded">{{__('entidades_encontradas')}}</a></li>
            <li><hr class="dropdown-divider"></li>
            {{-- <li><a class="dropdown-item" href="#">Contacto</a></li> --}}
            <li><a  id="logout" class="dropdown-item" href="#">{{__('salir')}}</a></li>
          </ul>
        </li>
       
      </ul>
    
        <a id="ainiciar" class="link-success" 
        style="margin :0px 10px 0px 0px !important" href="{{route('login')}}">
        {{__('iniciar')}}</a>
          <a id="a_registrar" class="link-success" 
          style="margin :0px 10px 0px 0px !important" href="{{route('registro')}}">
          {{__('registro')}}</a>


            <div id="noti">
             
            </div>
         

      <form class="d-flex" method="post" action ="{{route('buscar_entidad')}}">
      @csrf
        <input required name="entidad" class="form-control me-2" type="search" placeholder="Buscar" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Bucar</button>
      </form>
    </div>
  </div>
</nav>

<!-- Diálogo/modal de notificaciones -->
<div id="notificacionesModal" class="modal fade">
  <div class="modal-dialog ">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Notificaciones</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <ul id="notificacionesLista" class="list-group"></ul>
      </div>
    </div>
  </div>
</div>




<script src="{{asset('js/getToken.js')}}"></script>

<script>
  window.CSRF_TOKEN = '{{ csrf_token() }}';
 

//console.log(document.cookie)

function deleteAllCookies() {
    const cookies = document.cookie.split(";");

    for (let i = 0; i < cookies.length; i++) {
        const cookie = cookies[i];
        const eqPos = cookie.indexOf("=");
        const name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
        document.cookie = name + ";expires=Thu, 01 Jan 1970 00:00:00 GMT";
    }
}
//get username
username= getC('username')
//alert(username)
document.getElementById('inpuser').value=getC('user')

 let url = "{{ route('finded', ':user') }}";
 url = url.replace(':user', getC('user'));

 a = document.getElementById("finded");
a.setAttribute("href", url);

aux_t=getC('mytoken')


if(aux_t!=''){
  createBtn()
}

if(username!=""){
t = document.getElementById("navbarDropdown")
t.innerHTML = t.innerHTML+ "("+username+")"




}

function createBtn(){
  btn = '<button id="btn-notificaciones" type="button" class="btn btn-outline-primary me-1">'+
              '<i class="fas fa-bell"></i>'+
              '<span id="badge-notificaciones" class="ms-1 badge bg-danger"></span>'+
            '</button>';
  document.getElementById('noti').innerHTML += btn
  
  configBtn()

}

btn_out = document.getElementById('logout');

btn_out.addEventListener('click',function(){
  
    //document.cookie = "mytoken=; expires=Sat, 20 Jan 1980 12:00:00 UTC";
    //console.log("token delete");
    logout()
})


function closeCookie(){
    document.cookie = "mytoken=; expires=Sat, 20 Jan 1980 12:00:00 UTC;  path=/;";
    document.cookie = "user=; expires=Sat, 20 Jan 1980 12:00:00 UTC;  path=/;";
    document.cookie = "username=;  expires=Sat, 20 Jan 1980 12:00:00 UTC; path=/;";
   
}

async function logout(){
  //primero eliminamos la cookie del front
  
  //despues el token del servidor (back)
 


      id_user=getC('user');
      console.log(id_user)
      closeCookie()

      //return;
  
          const rawResponse = await fetch(server_name+'/api/logout', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                 'X-CSRF-TOKEN': window.CSRF_TOKEN
            },
            body: JSON.stringify({'token':id_user}),
        });
        const content = await rawResponse.json();
        console.log(content)

        if(content['code'] == 200){
            // alert("exit app")
            //  
            closeCookie()
            // window.location.href=url;
            ruta = "{{route('login')}}"
            window.location.href =ruta

        }else{
            closeCookie()
            alert(JSON.stringify(content['response']))
        }



}

// function getC(n){
//     const value = `; ${document.cookie}`;
//     const parts = value.split(`; ${n}=`);
//     if (parts.length === 2) return parts.pop().split(';').shift();
//     else
//       return "";
// }

var myModalEl = document.querySelector('#notificacionesModal')
//var modal = bootstrap.Modal.getOrCreateInstance(myModalEl)

async function getNotifications(){
  let response;
  id = getC('user')
  url =server_name+'/api/get-noti/'+id
  token=getC('mytoken')
  
  try {
      response = await fetch(url,{
      headers:{
        'Authorization': `Bearer ${token}`,
      }

    });
   
    const data = await response.json();

    return data
  } catch (error) {
    // console.log(error)
    console.error(error);
  }

}
var btnNotificaciones;
var badgeNotificaciones;

function configBtn(){
// Obtener el botón de campana y el diálogo/modal de notificaciones
 btnNotificaciones = document.querySelector('#btn-notificaciones');
badgeNotificaciones = document.querySelector('#badge-notificaciones');



// Agregar un evento de clic al botón de campana para mostrar el diálogo/modal de notificaciones
btnNotificaciones.addEventListener('click', () => {
  loadN(btnNotificaciones,badgeNotificaciones,true)
  
});

//h
loadN(btnNotificaciones,badgeNotificaciones,false)
}




function eliminarNoti(id,modal){
  url =server_name+'/api/del-noti/'+id
      token = getC('mytoken')
      fetch(url,{
        headers:{
        'Authorization': `Bearer ${token}`,
      }
      })
      .then(function(response) {
        return response.text();
      })
      .then(function(data) {
        console.log(data)
        modal.hide();
        loadN(btnNotificaciones,badgeNotificaciones,true)
      });

}

function loadN(btnNotificaciones,badgeNotificaciones,s){
  
  getNotifications().then((notificaciones) => {
    badgeNotificaciones.innerHTML = notificaciones['notificaciones'].length
    const notificacionesLista = document.querySelector('#notificacionesLista');
    const notificacionesModal = new bootstrap.Modal(document.getElementById('notificacionesModal'), {});
    document.querySelector('#notificacionesModal').addEventListener('show.bs.modal', () => {
      notificacionesLista.innerHTML = '';
      // Agregar las notificaciones a la lista de notificaciones
      notificaciones['notificaciones'].forEach((notificacion) => {
      
        const fecha = new Date(notificacion.created_at);
        const options = {
          year: "numeric",
          month: "2-digit",
          day: "2-digit",
          hour: "2-digit",
          minute: "2-digit",
          hour12: false,
          timeZone: "UTC"
        };
        const fechaFormateada = fecha.toLocaleString("es-ES", options);
        const btn_trash = document.createElement('i')
        btn_trash.classList.add('fa')
        btn_trash.classList.add('fa-trash')
        btn_trash.classList.add('ms-2')

        btn_trash.style.color ='gray'
        
        btn_trash.addEventListener('click',function(){
            eliminarNoti(notificacion.id,notificacionesModal)
        });  
       

        if(notificacion.tipo=="normal"){
          public=""
          if(host!=""){
            public = "/public"
          }

          ruta = server_name+public+'/contacts/'+ notificacion.entidad;
          const li = document.createElement('li');
          const span = document.createElement('span')
          const a = document.createElement('a');
          li.classList.add('list-group-item');
          a.classList.add('ms-2');
          span.classList.add('ms-2');
          li.innerHTML = notificacion.descripcion;
          a.textContent = "Ver solicitud"
          span.textContent= fechaFormateada
          a.href = ruta
          li.appendChild(a);
          li.appendChild(span)
          li.appendChild(btn_trash)
          notificacionesLista.appendChild(li);
        }else{
          //system
          if(host!=""){
            ruta = server_name+'/public/proweb';
          }else{
            ruta = server_name+'/proweb';
          }
          
          console.log(ruta)
          const li = document.createElement('li');
          const span = document.createElement('span')
          const a = document.createElement('a');
          li.classList.add('list-group-item');
          a.classList.add('ms-2');
          a.classList.add('badge');
          a.classList.add('rounded-pill');
          a.classList.add('bg-primary');

          span.classList.add('ms-2');
          span.classList.add('float-end');
          span.style.fontSize = '12px'
          span.style.color ='gray'
          

          li.innerHTML = notificacion.descripcion;
          a.textContent = "Completar ahora"
         
          span.textContent= fechaFormateada
          a.href = ruta
          li.appendChild(a);
          li.appendChild(span)
          li.appendChild(btn_trash)
          notificacionesLista.appendChild(li);
        }
        
        
      });
    });
  
    
    if(s){
      notificacionesModal.show();
    }
   
    
  }).catch((error) => {
    console.error(error);
  });

}


</script>
