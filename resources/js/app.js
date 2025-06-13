require('./bootstrap');
import Example from './components/Example';
import { createRoot } from 'react-dom/client';
const container = document.getElementById('example');
const root = createRoot(container); // createRoot(container!) if you use TypeScript

const id =getC('user');
const token = getC('mytoken');

var url="";
host="";
var app_protocol="";
var app_port="";

app_protocol  ="http://"
app_port =""

//napp_port = ":8000"

if(window.location.host == "localhost"){
    host = "/findall"
    app_protocol  ="http://"
}

url = app_protocol + window.location.hostname + host + app_port


root.render(<Example name={id} token={token} server={url}/>);