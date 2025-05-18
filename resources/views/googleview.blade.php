<html>
  <head>
    <title>Add Map</title>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>

    <style>
        /* Set the size of the div element that contains the map */
#map {
  height: 400px;
  /* The height is 400 pixels */
  width: 100%;
  /* The width is the width of the web page */
}
    
    </style>
  
  </head>
  <body>
    <h3>My Google Maps Demo</h3>
    <!--The div element for the map -->
    <div id="map"></div>

    <!-- 
     The `defer` attribute causes the callback to execute after the full HTML
     document has been parsed. For non-blocking uses, avoiding race conditions,
     and consistent behavior across browsers, consider loading using Promises
     with https://www.npmjs.com/package/@googlemaps/js-api-loader.
    -->
     <button id="sf" class="btn btn-primary">Registro</button>

    <script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDDQ--_3tszoNpmyzgxhCe88Y9DWoJbRaE&callback=initMap&v=weekly"
      defer
    ></script>
  </body>

<script>

    var mlat;
    var mlng;

    getLocation();


    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }

    function showPosition(position) {
        mlat = position.coords.latitude;
        mlng = position.coords.longitude;
        console.log("lat:" + mlat + "lng:" + mlng);

        initMap();
    }


// Initialize and add the map
function initMap() {
  // The location of Uluru
  const uluru = { lat: mlat, lng: mlng };
  puntos = [];
  // The map, centered at Uluru
  const map = new google.maps.Map(document.getElementById("map"), {
    zoom: 17,
    center: uluru,
  });

  const img_marker = "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.6.0/images/marker-icon.png";

  //el marcador con tu ubicación actual
  const marker = new google.maps.Marker({
    position: uluru,
    map: map,
  });
  //el evento en el mapa para dibujar nuevos marcadores
    map.addListener("click", (e) => {
      

      const marker = new google.maps.Marker({
      position: e.latLng,
      map: map,
      icon: img_marker,
      });
      p = e.latLng.toJSON();
      puntos.push(p);
  });

}
sf = document.getElementById('sf')
sf.addEventListener('click', function(e) {
            e.preventDefault();
            //console.log(puntos)
            respuesta = sendData();
        })

//window.initMap = initMap;

 async function sendData() {
       /* nombre = document.getElementById('nombre').value;
        descripcion = document.getElementById('descripcion').value; 
        recompensa = document.getElementById('recompensa').value;
        fecha_extravio = document.getElementById('fecha_extravio').value; */
        data = {
           // "nombre" : nombre,
           // "descripcion" : descripcion,
           // "fecha_extravio":fecha_extravio,
           // "recompensa":recompensa,
            "ubicaciones" : puntos,
        }  

        //primero se manda la info y después con la respuesta, las imagenes.

        const rawResponse = await fetch('https://localhost/findall/api/registro_entidad', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data),
        });
        const content = await rawResponse.json();
    
            alert(JSON.stringify(content['response']))

            if(content['code'] == 200){
                entidad  = content['entidad_id'];
                sendImage(entidad);


            }    

    }


</script>

</html>