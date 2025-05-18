<!DOCTYPE html>
<html lang="en" id="html">

<?php App::setLocale('es'); ?>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find All MX</title>
    <link rel="icon" type="image/x-icon" href="{{asset('favicon.ico')}}">

    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    
<!-- Incluimos la librería de Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog==" crossorigin="anonymous" />


    @yield('header')

    <style>
      html, body{ 
        width:100%;
    }
    body{
      margin-bottom: 60px; /* Altura del footer */
    }
    .loader {
  border: 10px solid #f3f3f3; /* Light grey */
  border-top: 10px solid #3498db; /* Blue */
  border-radius: 50%;
  width: 50px;
  height: 50px;
  animation: spin 2s linear infinite;
  position: absolute;
  left: 50%;
  top: 50%;
  -webkit-transform: translate(-50%, -50%);
  transform: translate(-50%, -50%);
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
    </style>

</head>
<body> 

    <div class="" style="">
      @include('layout.navbar')
      @if ($message = Session::get('error'))

        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <strong>{{$message}}
          </strong>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>

        @endif
      <div  class="m-2">
       @yield('contenido')
      </div>
     
    </div>
      
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>    
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/push.js/0.0.11/push.min.js"></script> --}}

    <script>


    // Push.Permission.request();

//     Push.create('Hi there!', {
//     body: 'This is a notification.',
//     icon: 'icon.png',
//     timeout: 8000,               // Timeout before notification closes automatically.
//     vibrate: [100, 100, 100],    // An array of vibration pulses for mobile devices.
//     onClick: function() {
//         // Callback for when the notification is clicked. 
//         console.log(this);
//     }  
// });
    </script>

</body>
@include('layout.footer')


@yield('scripts')

</html>