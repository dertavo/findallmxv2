<!DOCTYPE html>
<html>
<head>
    <title>Confirmar dirección de correo electrónico</title>
</head>
<body>
    <p>Hola,</p>
    <p>Por favor haz clic en el siguiente enlace para confirmar tu dirección de correo electrónico:</p>
    <a href="{{route('registro.confirm-email',$verifyLink->email_confirmation_token)}}">{{ $verifyLink }}</a>
    <p>Si no solicitaste esta confirmación, puedes ignorar este correo electrónico.</p>
</body>
</html>
