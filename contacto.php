<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto - DERILU</title>
    <style>
        /* Variables de color corporativas */
        :root {
            --color-fondo: #fcf6dc;
            --color-principal: #ffffff;
            --color-acento: #ffcc00;
            --color-texto-oscuro: #5e0d0d;
            --color-texto-mutado: #333333;
        }

        /* Estilos Base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--color-fondo);
            color: var(--color-texto-mutado);
            line-height: 1.6;
        }

        /* Banner Delgado Corregido */
        .banner-container {
            width: 100%;
            overflow: hidden;
            display: flex;
            justify-content: center;
        }

        .banner-image {
            width: 100%;
            height: 140px;
            object-fit: cover;
            object-position: center;
            display: block;
        }

        /* Menú de Botones */
        nav {
            background-color: var(--color-principal);
            border-bottom: 3px solid var(--color-acento);
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        nav ul {
            display: flex;
            justify-content: center;
            list-style: none;
            padding: 15px 0;
        }

        nav ul li {
            margin: 0 20px;
        }

        nav ul li a {
            text-decoration: none;
            color: var(--color-texto-oscuro);
            font-weight: bold;
            font-size: 1.1rem;
            text-transform: uppercase;
            padding: 8px 16px;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        nav ul li a:hover {
            background-color: var(--color-acento);
            color: #000000;
        }

        /* MARCADOR DEL BOTÓN ACTIVO (Cambiado a Contacto) */
        nav ul li a.activo {
            background-color: var(--color-texto-oscuro);
            color: var(--color-principal);
            border-bottom: 2px solid var(--color-acento);
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.2);
            cursor: default;
        }

        /* Estructura de la Sección de Contacto */
        .contacto-container {
            max-width: 1100px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .titulo-seccion {
            text-align: center;
            color: var(--color-texto-oscuro);
            font-size: 2.5rem;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .subtitulo-seccion {
            text-align: center;
            font-style: italic;
            color: #666;
            margin-bottom: 40px;
            font-size: 1.1rem;
        }

        /* Bloques en paralelo: Información + Formulario */
        .contacto-grid {
            display: flex;
            gap: 40px;
            margin-bottom: 60px;
        }

        /* Columna Izquierda: Datos Directos */
        .info-datos {
            flex: 1;
            background: var(--color-principal);
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .info-datos h3 {
            color: var(--color-texto-oscuro);
            font-size: 1.6rem;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        .dato-item {
            margin-bottom: 20px;
        }

        .dato-item strong {
            color: var(--color-texto-oscuro);
            display: block;
            font-size: 1.1rem;
        }

        /* Columna Derecha: Formulario de Cotización */
        .formulario-bloque {
            flex: 1.5;
            background: var(--color-principal);
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border-top: 4px solid var(--color-texto-oscuro);
        }

        .form-grupo {
            margin-bottom: 20px;
        }

        .form-grupo label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
            color: var(--color-texto-mutado);
        }

        /* Inputs y Selectores Estilizados */
        .form-grupo input, 
        .form-grupo select, 
        .form-grupo textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #cccccc;
            border-radius: 4px;
            font-size: 1rem;
            background-color: #fafafa;
            transition: border-color 0.3s ease;
        }

        .form-grupo input:focus, 
        .form-grupo select:focus, 
        .form-grupo textarea:focus {
            outline: none;
            border-color: var(--color-texto-oscuro);
            background-color: #ffffff;
        }

        /* Fila dividida para campos cortos (Fecha e Invitados) */
        .form-fila {
            display: flex;
            gap: 20px;
        }

        .form-fila .form-grupo {
            flex: 1;
        }

        /* Botón de Envío */
        .btn-enviar {
            width: 100%;
            background-color: var(--color-texto-oscuro);
            color: #ffffff;
            border: none;
            padding: 15px;
            font-size: 1.1rem;
            font-weight: bold;
            text-transform: uppercase;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .btn-enviar:hover {
            background-color: #7a1111;
            color: var(--color-acento);
        }

        /* Pie de Página */
        footer {
            background-color: var(--color-principal);
            color: var(--color-texto-oscuro);
            padding: 40px 20px;
            text-align: center;
            border-top: 2px solid var(--color-acento);
        }

        .footer-info h2 {
            margin-bottom: 15px;
            text-transform: uppercase;
        }

        .footer-info p {
            margin: 5px 0;
            font-size: 1.1rem;
        }

        /* Diseño Adaptable Celulares */
        @media (max-width: 768px) {
            .banner-image {
                max-height: 120px;
            }
            nav ul {
                flex-direction: column;
                align-items: stretch;
                padding: 10px 20px;
            }
            nav ul li {
                margin: 5px 0;
                text-align: center;
            }
            nav ul li a {
                display: block;
                font-size: 1rem;
                padding: 12px 0;
                background-color: #fcfcfc;
                border: 1px solid #e0e0e0;
            }
            nav ul li a.activo {
                background-color: var(--color-texto-oscuro);
                color: white;
                border: 1px solid var(--color-texto-oscuro);
            }
            .titulo-seccion {
                font-size: 1.8rem;
            }
            .contacto-grid {
                flex-direction: column;
                gap: 20px;
            }
            .form-fila {
                flex-direction: column;
                gap: 0;
            }
            .info-datos, .formulario-bloque {
                padding: 20px;
            }
        }
    </style>
</head>
<body>

    <!-- Banner Responsivo Ajustado -->
    <div class="banner-container">
        <img src="banner-derilu.jpg" alt="DERILU Banner" class="banner-image">
    </div>

    <!-- Menú de Navegación (Contacto Activo) -->
    <nav>
        <ul>
            <li><a href="index.html">Inicio</a></li>
            <li><a href="nosotros.html">Nosotros</a></li>
            <li><a href="servicios.html">Servicios</a></li>
            <li><a href="galeria.html">Galería</a></li>
            <li><a href="contacto.html" class="activo">Contacto</a></li>
        </ul>
    </nav>

    <!-- Contenido Principal -->
    <main class="contacto-container">
        <h1 class="titulo-seccion">Cotiza tu Evento</h1>
        <p class="subtitulo-seccion">Cuéntanos tus ideas y diseñemos juntos un menú inolvidable</p>

        <div class="contacto-grid">
            
            <!-- Columna 1: Información de contacto -->
            <div class="info-datos">
                <h3>Atención Directa</h3>
                <div class="dato-item">
                    <strong>📍 Ubicación:</strong>
                    <p>Ciudad de México y Área Metropolitana</p>
                </div>
                <div class="dato-item">
                    <strong>📞 Teléfono / WhatsApp:</strong>
                    <p>(55) 5530-33-3055</p>
                </div>
                <div class="dato-item">
                    <strong>✉️ Correo Electrónico:</strong>
                    <p>atencion_clientes@derilu.com</p>
                </div>
                <div class="dato-item">
                    <strong>⏰ Horarios de Atención:</strong>
                    <p>Lunes a Sábado: 9:00 AM - 7:00 PM</p>
                </div>
            </div>

            <!-- Columna 2: Formulario Estilizado -->
            <div class="formulario-bloque">
                <!-- El atributo action queda listo para enlazarse a un procesador de correos -->
               
<?php if (!empty($mensaje)): ?>
    <div class="alerta <?php echo $tipo_mensaje; ?>">
        <?php echo $mensaje; ?>
    </div>
<?php endif; ?>

<!-- Cambia tu etiqueta <form> actual por esta -->
<form action="insertar_cliente.php" method="POST">

    	<input type="text" name="nombre" placeholder="Nombre" required>
   <br>	
	<input type="text" name="apellido" placeholder="Apellido" required>
    <br>
	<input type="email" name="email" placeholder="Correo Electrónico" required>
    <br>
	<input type="text" name="telefono" placeholder="Teléfono">
    <br>
	<input type="date" name="cumpleanos" placeholder="Fecha de Cumpleaños">
    <br>
    <button type="submit">Enviar Cotización</button>
</form>
