const app = require('express')();
const http = require('http').createServer(app);
const io = require('socket.io')(http);

io.on('connection', (socket) => {
    console.log('Un usuario se ha conectado');

    socket.on('message', (data) => {
        console.log('Mensaje recibido:', data);
        io.emit('message', data);
    });

    socket.on('disconnect', () => {
        console.log('Un usuario se ha desconectado');
    });

    
});

app.get('/chat', (req, res) => {
    res.sendFile(__dirname + '/public/index.php');
    //console.log(__dirname)
});

http.listen(3000, () => {
    console.log('Servidor Socket.io iniciado en el puerto 3000');
});
