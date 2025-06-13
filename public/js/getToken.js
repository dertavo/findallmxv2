
host ="";
public=""

var cadena_host ="";

var server_img = "https://storage.googleapis.com/findall_bucket/"

var server_name = location.protocol + '//' + location.host  + host; 




cadena_host = window.location.host;

if(cadena_host.includes("localhost")){
  host = "/findall"
  //public ="/public"
  server_img= server_name + "/storage/entidades/"
}




var url_string = window.location.href;
var aux = url_string.split('/')
var p = aux[aux.length - 1]



var neg = url_string.search("detalles_entidad")


let g =  url_string.search("state")

np = url_string.search("new-pass")

var token = getC('mytoken');


if(token == ""){

    if(g<0 && p != "" && p !="login" && neg<0 && p!="registro" && p!="buscar_entidad" && p!="acerca" && p!="politicas" && p!="recovery-pass" && np<0){
   
    alert("Debes iniciar sesión para ingresar")
     window.location = server_name+public+'/login';
    }else{
     
    }
}else{
  
 
  document.getElementById('liregistro').setAttribute('style', 'display:inline !important');
  document.getElementById('limisdatos').setAttribute('style', 'display:inline !important');
  document.getElementById('ainiciar').setAttribute('style', 'display:none !important');
  document.getElementById('a_registrar').setAttribute('style', 'display:none !important');

}


function getC(n){
  const value = `; ${document.cookie}`;
  const parts = value.split(`; ${n}=`);
  if (parts.length === 2) return parts.pop().split(';').shift();
  else
  return "";
}

function delete_cookie(name) {
  document.cookie = "mytoken=; expires=Sat, 20 Jan 1980 12:00:00 UTC";
  console.log(document.cookie);
}





