<style>

    html {
      position: relative;
      min-height: 100%;
    }
    body {
      margin-bottom: 60px; /* Altura del footer */
    }
    .footer {
      position: absolute;
      bottom: 0;
      width: 100%;
      height: 60px; /* Altura del footer */
      background-color: #f5f5f5;
    }
        </style>

    <div style="margin-top:50px; border-top:1px solid #f5f5f5">
    <footer class="footer mt-auto py-3 mt-2">
        <div class="container">
            <ul class="nav justify-content-center border-bottom pb-3 mb-3">
            <li class="nav-item"><a href="{{route('/')}}" class="nav-link px-2 text-muted">Inicio</a></li>
            <li class="nav-item"><a href="{{route('politicas')}}" class="nav-link px-2 text-muted">Políticas de uso y privacidad</a></li>
            <li class="nav-item"><a href="{{route('acerca')}}" class="nav-link px-2 text-muted">Acerca de</a></li>
          </ul>
          <p class="text-center text-muted">© 2023 Findallmx, Inc</p>
        </div>
      </footer>
    </div>