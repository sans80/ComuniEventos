<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Salón Comunal — Información</title>
  <meta name="description" content="Página informativa del salón comunal: fotos, características y contacto." />
  <link rel="stylesheet" href="assets/css/estilos.css" />
</head>

<body>
  <header>
    <div class="container inner">
      <div>
        <h1>Salón Comunal — Casa de Encuentros</h1>
        <p class="lead">Un espacio para reuniones, talleres y actividades comunitarias.</p>
      </div>
      <nav aria-label="principal">
        <a href="#nosotros">Nosotros</a>
        <a href="#servicios">Servicios</a>
        <a href="#galeria">Galería</a>
        <a href="#contacto">Contacto</a>
        <a href="registro.php">Registrarse</a>
        <a href="inicio_sesion.php">Iniciar sesión</a>
      </nav>
    </div>
  </header>

  <main class="container">
    <section class="hero">
      <div class="card">
        <h2>Bienvenidos al Salón Comunal</h2>
        <p class="small">Este sitio ofrece información general sobre el salón: características, servicios disponibles,
          galerías de fotos y datos de contacto para consultas.</p>

        <div id="nosotros" style="margin-top:12px">
          <h3>Sobre el salón</h3>
          <p>El salón comunal es un espacio amplio y versátil pensado para actividades comunitarias como reuniones de
            junta, talleres, celebraciones y cursos. Está ubicado en el corazón de la comunidad y cuenta con fácil
            acceso para la mayoría de los vecinos.</p>
        </div>

        <div id="servicios" style="margin-top:12px">
          <h3>Servicios y características</h3>
          <div class="features">
            <div class="feature"><strong>Capacidad</strong>
              <div class="small">Aproximadamente 120 personas</div>
            </div>
            <div class="feature"><strong>Mobiliario</strong>
              <div class="small">Sillas y mesas disponibles</div>
            </div>
            <div class="feature"><strong>Audio/Proyector</strong>
              <div class="small">Equipo para presentaciones</div>
            </div>
            <div class="feature"><strong>Accesibilidad</strong>
              <div class="small">Acceso peatonal y entrada principal amplia</div>
            </div>
          </div>
        </div>
      </div>

      <aside class="card">
        <h3>Detalles rápidos</h3>
        <p class="small"><strong>Horario:</strong> Lunes a domingo — 7:00 a.m. a 10:00 p.m.</p>
        <p class="small"><strong>Dirección (ejemplo):</strong> Carrera 10 # 45-23 — Barrio Centro</p>
        <p class="small"><strong>Contacto:</strong><br />Tel: (000) 123 4567<br />Email: salon@comunal.org</p>
        <p style="margin-top:8px" class="small">Si quieres imágenes reales del salón, puedo generar mockups o ayudarte a
          integrar fotos cuando las tengas.</p>
      </aside>
    </section>

    <section id="galeria" style="margin-top:18px">
      <h3>Galería</h3>
      <p class="small">Imágenes de ejemplo (placeholders). Reemplaza por fotos reales cuando las tengas.</p>
      <div class="gallery">
        <div class="photo card">
          <svg viewBox="0 0 800 500" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid meet">
            <rect width="100%" height="100%" fill="#eef2ff" />
            <g fill="#c7d2fe">
              <rect x="40" y="60" width="720" height="320" rx="8" />
            </g>
            <text x="50%" y="55%" dominant-baseline="middle" text-anchor="middle"
              font-family="Arial, Helvetica, sans-serif" font-size="32" fill="#4c51bf">Foto del salón
              (placeholder)</text>
          </svg>
        </div>

        <div class="photo card">
          <svg viewBox="0 0 800 500" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid meet">
            <rect width="100%" height="100%" fill="#f0fdf4" />
            <g fill="#bbf7d0">
              <rect x="40" y="60" width="720" height="320" rx="8" />
            </g>
            <text x="50%" y="55%" dominant-baseline="middle" text-anchor="middle"
              font-family="Arial, Helvetica, sans-serif" font-size="32" fill="#059669">Imagen del interior
              (placeholder)</text>
          </svg>
        </div>

        <div class="photo card">
          <svg viewBox="0 0 800 500" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid meet">
            <rect width="100%" height="100%" fill="#fff7ed" />
            <g fill="#fed7aa">
              <rect x="40" y="60" width="720" height="320" rx="8" />
            </g>
            <text x="50%" y="55%" dominant-baseline="middle" text-anchor="middle"
              font-family="Arial, Helvetica, sans-serif" font-size="32" fill="#c2410c">Escenario / Eventos
              (placeholder)</text>
          </svg>
        </div>
      </div>
    </section>

    <section id="contacto" style="margin-top:22px">
      <div class="card">
        <h3>Contacto</h3>
        <p class="small">Para más información o para coordinar uso del salón escribe a <a
            href="mailto:salon@comunal.org">salon@comunal.org</a> o llama al (000) 123 4567.</p>
        <p class="small">Si quieres, puedo añadir un formulario de contacto simple o un mapa embebido (Google Maps) en
          esta sección.</p>
      </div>
    </section>
  </main>

  <footer>
    <div class="container">
      <p style="color:var(--muted);text-align:center;margin:18px 0">&copy; <span id="year"></span> Junta de Acción
        Comunal — Salón Comunal</p>
    </div>
  </footer>

  <script>document.getElementById('year').textContent = new Date().getFullYear();</script>
</body>

</html>
