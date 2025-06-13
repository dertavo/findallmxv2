@extends('layout.app')

@section('header')

<style>
/* Reglas para los mensajes enviados por el usuario */
li[data-origen="usuario"] {
  float: right;
  clear: both;
  margin-bottom: 1rem;
}

li[data-origen="usuario"] .media-body {
  background-color: #007bff;
  color: white;
  border-radius: 10px;
  padding: 10px;
}

/* Reglas para los mensajes recibidos */
li[data-origen="destino"] {
  float: left;
  clear: both;
  margin-bottom: 1rem;
}

li[data-origen="destino"] .media-body {
  background-color: #f8f9fa;
  color: #495057;
  border-radius: 10px;
  padding: 10px;
}
.text-right .media-body {
  text-align: right;
}

.text-left .media-body {
  text-align: left;
}

</style>
@endsection

@section('contenido')
<div class="container ">

    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header bg-primary text-white">Chat
         
            <a id="pop" tabindex="0" class="float-end btn btn-light" role="button" data-bs-toggle="popover"
             data-bs-trigger="focus" title="Información chat" 
             data-bs-content="Por defecto este chat solo admite el envío de 5 mensajes">
            
              <i class="fa fa-info-circle" aria-hidden="true"></i>
            </a>
          </div>
          <div class="card-body p-0">
            <div id="div-chat" class="overflow-auto" style="max-height: 400px;">
              <ul id="ul-chat" class="list-unstyled">
                {{-- <li class="media mb-4">
                  <img src="https://via.placeholder.com/64" class="mr-3 rounded-circle" alt="User">
                  <div class="media-body">
                    <h5 class="mt-0 mb-1">User</h5>
                    Hello, how can I help you?
                  </div>
                </li>
                <li class="media mb-4">
                  <img src="https://via.placeholder.com/64" class="mr-3 rounded-circle" alt="Bot">
                  <div class="media-body">
                    <h5 class="mt-0 mb-1">Bot</h5>
                    Hi there! What do you need assistance with?
                  </div>
                </li>
                <li class="media mb-4">
                  <img src="https://via.placeholder.com/64" class="mr-3 rounded-circle" alt="User">
                  <div class="media-body">
                    <h5 class="mt-0 mb-1">User</h5>
                    I need help with my account.
                  </div>
                </li>
                <!-- Agrega más mensajes aquí --> --}}
                {{-- <li class="media mb-4" data-origen="usuario">
                    <img src="https://via.placeholder.com/64" class="mr-3 rounded-circle" alt="User">
                    <div class="media-body">
                      <h5 class="mt-0 mb-1">User</h5>
                      Hello, how can I help you?
                    </div>
                  </li>
                  <li class="media mb-4" data-origen="destino">
                    <img src="https://via.placeholder.com/64" class="mr-3 rounded-circle" alt="Bot">
                    <div class="media-body">
                      <h5 class="mt-0 mb-1">Bot</h5>
                      Hi there! What do you need assistance with?
                    </div>
                  </li>
                  <li class="media mb-4" data-origen="usuario">
                    <img src="https://via.placeholder.com/64" class="mr-3 rounded-circle" alt="User">
                    <div class="media-body">
                      <h5 class="mt-0 mb-1">User</h5>
                      I need help with my account.
                    </div>
                  </li> --}}
              </ul>
            </div>
          </div>
          <div class="card-footer mt-4">
           
             <div class="input-group mb-3">
              <input id="mensaje" type="text" class="form-control" placeholder="Mensaje" aria-label="Recipient's username" aria-describedby="button-addon2">
              <button id="btn-mss" class="btn btn-primary" type="button" id="button-addon2">Mensaje

               <i class="fa fa-paper-plane" aria-hidden="true"></i>

              </button>
            </div>
             
           
          </div>
        </div>
      </div>
    </div>
  </div>
  
<!-- Contenedor donde se mostrará la información del servidor -->
<div id="server-info"></div>
{{-- 
<input name="mensaje" id="mensaje" />

<button id="btn-mss" >Enviar</button> --}}

@endsection

@section('scripts')

<script>
var popover = new bootstrap.Popover(document.querySelector('#pop'), {
  container: 'body',
  
})
popover.show()

  // Oculta el popover después de 5 segundos
  setTimeout(function() {
    popover.hide();
  }, 5000);

var du = '{{ $destino_user }}'
var dus = '{{$destino_username}}'
var entity = '{{$entidad_id}}'
// console.log(entity)
var lastMessageCount = 0; // Inicializa la variable de conteo

const list = document.querySelector(".list-unstyled");
var div_chat = document.getElementById("div-chat");

var calls_server = 0;

var continue_read = true

var elorigen = getC('username');    
//console.log(elorigen)
var id_origen = getC('user')


let count = 0; // Inicializa la variable de conteo

    getServer()
    // Función que se encarga de obtener y mostrar la información del servidor
    function getServer() {
     
      if(continue_read){
        ruta = server_name+'/api/get-messages/'+id_origen+'/'+du+'/'+entity
        console.log(ruta)

        fetch(ruta,{
            method: 'GET',
        })
        .then(response => response.json())
        .then(data => {
            data_mss = data['messages']
            console.log(data_mss)
            if (data_mss.length > lastMessageCount) {
             
                // Agrega solo los mensajes nuevos a la lista
                data_mss.slice(lastMessageCount).forEach(message => {
                    const li = document.createElement('li');
                    li.classList.add('media', 'mb-4');
                     li.classList.add('media', 'me-2');
                     li.classList.add('media', 'ms-2');

                    const img = document.createElement('img');
                    img.src = 'https://via.placeholder.com/64';
                    img.classList.add('mr-3', 'rounded-circle');
                    img.alt = message.origen_name;

                    const body = document.createElement('div');
                    body.classList.add('media-body');

                    const title = document.createElement('h5');
                    title.classList.add('mt-0', 'mb-1');
                    title.textContent = message.origen_name;

                    const content = document.createElement('div');
                    content.textContent = message.content;

                    body.appendChild(title);
                    body.appendChild(content);

                    //li.appendChild(img);
                    li.appendChild(body);

                // Agregar clase para alinear a la derecha o izquierda
                if (message.origen_name == elorigen ) {
                li.dataset.origen = 'usuario';
                } else {
                    li.dataset.origen = 'destino';
                }

                    list.appendChild(li);
                });
                // Actualiza la variable de conteo con la cantidad total de mensajes
                lastMessageCount = data_mss.length;
               
              
                console.log("esto es un nuevo mensaje")
                div_chat.scrollTop = div_chat.scrollHeight;
            }else{
             //no hay nuevos mensajes
            }
            //console.log(data['end'])
            if(data['end']==true){
              //console.log("here")
              continue_read=false;
              mensaje.disabled = true
              btn_mss.disabled=true
            }else{
              //console.log("conviertelo hdp")
              continue_read=true;
              //console.log(continue_read)
            }
  })
  .catch(error => console.error(error));
  }else{
    //no reading server messages
    console.log("no read")
}
}

function addMessagesToList(messages) {
  const list = document.querySelector('.list-unstyled');

  messages.forEach(message => {
    const li = document.createElement('li');
    li.classList.add('media', 'mb-4');

    const img = document.createElement('img');
    img.src = 'https://via.placeholder.com/64';
    img.classList.add('mr-3', 'rounded-circle');
    img.alt = message.origen_name;

    const body = document.createElement('div');
    body.classList.add('media-body');

    const title = document.createElement('h5');
    title.classList.add('mt-0', 'mb-1');
    title.textContent = message.origen_name;

    const content = document.createElement('div');
    content.textContent = message.content;

    body.appendChild(title);
    body.appendChild(content);

    li.appendChild(img);
    li.appendChild(body);

    list.appendChild(li);
  });

  // Scroll hacia abajo para mostrar los nuevos mensajes
  const chatBody = document.querySelector('.card-body');
  chatBody.scrollTop = chatBody.scrollHeight;
}
    // Ejecuta la función cada 5 segundos
  
      setInterval(getServer, 2000)

     // Función que se encarga de cerrar la página si no hay nuevos mensajes o si el chat está inactivo
     function checkActivity() {
        if (count == 0 || count == parseInt(document.getElementById('server-info').getAttribute('data-count'))) {
            window.close(); // Cierra la pestaña o ventana del navegador
            //alert("la pestaña actual se cerrará")
        } else {
            document.getElementById('server-info').setAttribute('data-count', count); // Actualiza el atributo data-count del contenedor
        }
    }

    var btn_mss = document.getElementById('btn-mss');
    var mensaje = document.getElementById('mensaje')

    mensaje.addEventListener('keyup', function(e) {
      var keycode = e.keyCode || e.which;
      if (keycode == 13) {
        sendMessage()
      }
    });


    if (btn_mss) {
      btn_mss.addEventListener('click', sendMessage);
    
    }
    

    function sendMessage(){
      btn_mss.disabled=true;
      if(mensaje.value==""){
        return
      }
    
        var raw = JSON.stringify({
            "entidad_id":entity,
            "origen_name": elorigen,
            "destino_name" :dus,
            "origen_user":id_origen,
            "destino_user":du,  
            "content": mensaje.value
            });
        
        fetch(server_name+'/api/save-message',{
            method: 'POST',
            body: raw,
            headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
            },
            })
            .then(response => response.text())
            .then(data => {
                btn_mss.disabled=false;
                // Actualiza el contenido del contenedor con la respuesta del servidor
                //document.getElementById('server-info').innerHTML = data;
                console.log(data)
            })
            .catch(error => console.error(error));

            mensaje.value=""
    }

    // Ejecuta la función checkActivity cada 3 minutos
    setInterval(checkActivity, 180000);
</script>
@endsection