function setLog(content,url){

  
    console.log("info")
    console.log(content)
    var expirationDate = new Date();
    var aux = new Date();

        aux.setTime(expirationDate.getTime())
        console.log(aux.toUTCString())
        expirationDate.setTime(expirationDate.getTime() + (1800 * 1000));
        console.log(expirationDate.toUTCString())
        usuario = content['usuario']
        token = content['token']
        // alert(usuario)
        document.cookie ="mytoken="+token+";expires="+expirationDate.toUTCString()+";path=/";
        document.cookie="user="+usuario+";expires="+expirationDate.toUTCString()+";path=/";
        document.cookie ="username=" + content['username']+";expires="+expirationDate.toUTCString()+";path=/";

     
        // alert('Sesión iniciada')
        window.location.href=url
        
}