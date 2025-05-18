var map =null;
var markers =[]
var btnAgain = document.getElementById('btnAgain')

function setMapOnAll(map) {
  for (let i = 0; i < markers.length; i++) {
    markers[i].setMap(map);
  }
}

btnAgain.addEventListener('click',function(e){
  e.preventDefault()
  btnAgain.style.display = 'none'

  setMapOnAll(null);
  markers =[]
  puntos =[]
})

const loader_2 = document.getElementById("loader_2");

const textarea = document.querySelector("#description");
const contadorCaracteres = document.querySelector("#contador-caracteres");

textarea.addEventListener("input", function() {
  const longitudActual = textarea.value.length;
  const longitudMaxima = textarea.getAttribute("maxlength");
  const caracteresRestantes = longitudMaxima - longitudActual;

  contadorCaracteres.innerHTML = caracteresRestantes + " caracteres restantes";
});

function processFile(reader){
    console.log("buenatio")
} 


    user_id = getC('user')

   
     async function delImg(source,filename){

        const result = window.confirm('¿Estás seguro de eliminar este elemento?', 'Eliminar elemento');
        if (!result) {

        } else {

        data = {
            "userid" : user_id,
            "imgid" : source,
            "filename":filename,
        } 
        token = getC('mytoken')
        const rawResponse = await fetch(server_name+'/api/delImg', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`,
            },
            body: JSON.stringify(data),
            credentials:'include',
        });
        const content = await rawResponse.json();
        // console.log(content)
        window.location.reload()

        }

}


   var ruta ="";
   var themetodo="";

    var detailcountImg=0;

   var update = false;

    var load_u=[]
    var thename = document.getElementById('name');
    var description = document.getElementById('description'); 
    var reward = document.getElementById('reward');
    var thedate = document.getElementById('date'); 
    var divimg = document.getElementById('ga2')
    var btn_s= document.getElementById('sf')
  
  if (typeof theid != 'undefined') { 

    //getting the user id for updating.
    //alert("updating");

    console.log(theid)

    ruta = 'editar_entidad/' + theid;

    console.log(ruta)
   

    document.getElementById('idEnt').value=theid;

    inputEdit = document.getElementById('isEdit');
    inputEdit.value=1

      btn_s.innerHTML="Actualizar"
        update = true;
       drawData();
  }else{

     ruta = 'registro_entidad';
   // alert("new data")
      getLocation();
  }

   function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }
      function showPosition(position) {
        mlat= position.coords.latitude;
        mlng= position.coords.longitude;
        initMap();
    }

        // Initialize and add the map
      
function initMap() {
  // The location of Uluru
  const uluru = { lat: mlat, lng: mlng };
  puntos = load_u;
  
  // The map, centered at Uluru
    map = new google.maps.Map(document.getElementById("map"), {
    zoom: 17,
    center: uluru,
  });

  //const img_marker = "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.6.0/images/marker-icon.png";
  const img_marker = "http://leafletjs.com/examples/custom-icons/leaf-green.png"
  //el marcador con tu ubicación actual
  const marker = new google.maps.Marker({
    position: uluru,
    map: map,
  });

  //el evento en el mapa para dibujar nuevos marcadores
    map.addListener("click", (e) => {

     
      if(markers.length <4){
      const marker = new google.maps.Marker({
      position: e.latLng,
      map: map,
      icon: img_marker,
      });
      p = e.latLng.toJSON();
      puntos.push(p);

      markers.push(marker)

     
      btnAgain.style.display = 'block'
    }else{
      alert("Ha llegado al límite de puntos disponibles")
    }
  });
  

  //esto es solo para cuando se actualiza?
// console.log(puntos)
    if(update){

      aux_p =[]
 for(var u of puntos){

        const uluru = { lat: parseFloat(u['latitud']), lng: parseFloat(u['longitud'])};
        // console.log(uluru)
        const marker_l = new google.maps.Marker({
        position: uluru,
        map: map,
        icon: img_marker,
        });
     aux_p.push(marker_l)
      
  }
  markers = aux_p;
  // console.log(puntos)
  puntos = []

}

 
}
async function drawData(){
    data = await loadData();
   
    

    info = data['entitys']

    
    console.log(info)


    imgs = data['imgs']
    
    thename.value = info['nombre']
    description.value = info['descripcion']

    reward.value = info['recompensa']
    load_u=info['ubicaciones']
    markers= load_u
    thedate.value = info['fecha_extravio']
    imgs.forEach(imagen => {
    detailcountImg++
    imgsrc = imagen['archivo'];
    imgid  = imagen['id']
    src = server_name+'/public/storage/entidades/'+imgsrc+'';
    src = server_img + imgsrc
    

    imgdata = ''+
                ''+
            '<img  id='+imgid+' src="'+src+'" class="head-a imagenesproducto img-thumbnail">'+
            '<a onclick="delImg('+imgid+',\''+imgsrc+'\')" id="di" class="btn btn-danger btn-circle btn-x  bottom-0 right-0">'+
                '<i class="fa fa-times"></i>'+
            '</a>'+
            ''

      
    divimg.innerHTML += imgdata;   
   

  });

    getLocation()
   
}

async function loadData(){
  token = getC('mytoken')
      const rawResponse = await fetch(server_name+'/api/detalles_entidad/'+theid, {
    method: 'GET',
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      'Authorization': `Bearer ${token}`,
    },
    credentials:'include',
    
  });
let response = await rawResponse.json();

    if(response['code'] == 500){
             //alert(JSON.stringify(response['response']))
            window.location.replace(server_name+'/public/');

        }
       

return response;

}
let selectedFiles = [];
const fileList = document.getElementById('galeria');
const fileInput = document.getElementById('img-entidad');
  // Multiple images preview in browser
 function imagesPreview(input, placeToInsertImagePreview) {
  // Create an array to store the files

const imgElements = divimg.querySelectorAll('img')
if(imgElements.length>=4){
  alert("El máximo de imagenes a subir es de 4")
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


  const files = fileInput.files;
  if (input.files) {
  
    for (let i = 0; i < files.length; i++) {
      selectedFiles.push(files[i]);
    }

    
    //console.log(filesAmount);
    for (let i = 0; i < selectedFiles.length; i++) {
      
      var reader = new FileReader();

      reader.onload = function(event) {
        $('#galeria').show();

        html =
        
          '<img id=imgid' +
          i +
          ' img class="head-a imagenesproducto img-thumbnail">' +
          '<a onclick="delImgG(' +
          i +
          ')" id="di'+i+'"  class=" btn btn-danger btn-circle btn-x">' +
          '<i data-index="${i}" class="removeButton fa fa-times"></i>' +
          '</a>';

        $($.parseHTML(html))
          .attr('src', event.target.result)
          .appendTo(placeToInsertImagePreview);
      };



      reader.readAsDataURL(input.files[i]);
    }
    di = document.getElementById('di');
  }

 
}
function deleteImage(index) {
 
    // Eliminar el archivo de la lista
selectedFiles.splice(index, 1);
//delete selectedFiles[index]

// console.log("deleting : "+index)

}
  document.getElementById('img-entidad').addEventListener('change',function(){
        imagesPreview(this, 'div.gallery');
  });


  fileList.addEventListener('click', event => {
    
    if (event.target.className === 'removeButton fa fa-times') {
    
    // Obtener el índice del archivo a eliminar
      const index = event.target.dataset.index;
      // Eliminar el archivo de la lista
      selectedFiles.splice(index, 1);
     
    }
  });

 function delImgG(index){
  $('#di' + index).remove();
  $('#imgid' + index).remove();
  // Call the deleteImage function
  //deleteImage(index);
       
}
