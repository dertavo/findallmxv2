<!DOCTYPE html>
<html>
<head>
    <title>Renovación de contraseña olvidada</title>
</head>
<body>
    <p>Hola,</p>
    {{$token}}
    <p>Por favor haz clic en el siguiente enlace para recuperar tu contraseña:</p>
    <a href="{{route('new-pass',$token)}}">{{route('new-pass',$token) }}</a>
    <p>Si no solicitaste esta confirmación, puedes ignorar este correo electrónico.</p>
</body>
</html>
