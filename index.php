<?php
session_start();
include('config/conexion.php');

$total_carrito = isset($_SESSION['carrito']) ? count($_SESSION['carrito']) : 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adidas Store - Inicio</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #FFF0F5; color: #4A4A4A; display: flex; flex-direction: column; min-height: 100vh; }
        a { text-decoration: none; }

        /* ENCABEZADO CON TÍTULO CENTRADO */
        header {
            background-color: #F8C8DC;
            padding: 25px 5%;
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            align-items: center;
            box-shadow: 0 4px 15px rgba(216, 112, 147, 0.2);
        }
        header h1 {
            grid-column: 2;
            color: #6B4C57;
            font-size: 32px;
            letter-spacing: 3px;
            text-align: center;
            font-weight: 800;
        }
        .header-btn-container { grid-column: 3; justify-self: end; }
        .btn-carrito {
            background-color: #6B4C57;
            color: white;
            border: none;
            padding: 10px 22px;
            font-size: 14px;
            font-weight: bold;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .btn-carrito:hover { background-color: #523942; transform: translateY(-2px); }

        /* BARRA DE NAVEGACIÓN */
        nav {
            background-color: #ffffff;
            display: flex;
            justify-content: center;
            gap: 15px;
            padding: 14px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
            margin-bottom: 35px;
        }
        .nav-link {
            padding: 10px 25px;
            border-radius: 20px;
            color: #6B4C57;
            font-weight: bold;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        .nav-link.active { background-color: #F8C8DC; }
        .nav-link:hover { background-color: #FFF0F5; color: #D87093; }

        /* CONTENIDO PRINCIPAL */
        .container { max-width: 950px; margin: 0 auto; padding: 0 20px; flex: 1; }
        .banner-card {
            background-color: #FFFFFF;
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(216, 112, 147, 0.15);
            border: 1px solid #F3D1DC;
            text-align: center;
        }
        .banner-card h2 { color: #D87093; font-size: 28px; margin-bottom: 15px; }
        .banner-card p { color: #6B4C57; line-height: 1.7; font-size: 16px; margin-bottom: 35px; }

        .grid-catalogo { display: grid; grid-template-columns: 1fr 1fr; gap: 25px; }
        .card-seccion {
            background-color: #FFF0F5;
            border: 2px solid #F3D1DC;
            border-radius: 14px;
            padding: 30px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: all 0.3s ease;
        }
        .card-seccion:hover { transform: translateY(-6px); box-shadow: 0 12px 25px rgba(181, 101, 118, 0.18); }
        .card-seccion h3 { color: #D87093; margin-bottom: 12px; font-size: 22px; }
        .card-seccion p { font-size: 14px; margin-bottom: 25px; color: #6B4C57; line-height: 1.5; }

        .btn-catalogo {
            border: none;
            color: white;
            padding: 14px;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            font-size: 14px;
            transition: opacity 0.3s ease;
        }
        .btn-calzado { background-color: #D87093; }
        .btn-ropa { background-color: #B56576; }
        .btn-catalogo:hover { opacity: 0.9; }

        /* MODAL DE CUENTA */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background-color: rgba(0, 0, 0, 0.55);
            backdrop-filter: blur(5px);
            z-index: 1000;
        }
        .modal-content {
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            background-color: #FFFFFF;
            padding: 35px;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.25);
            width: 90%;
            max-width: 650px;
            max-height: 90vh;
            overflow-y: auto;
        }
        .modal-overlay:target { display: block; }
        .btn-close { float: right; color: #dc3545; font-weight: bold; font-size: 14px; }

        .forms-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px; }
        .form-box { background-color: #FFF0F5; padding: 22px; border-radius: 12px; border: 1px solid #F3D1DC; }
        .form-box h3 { color: #6B4C57; margin-bottom: 15px; text-align: center; }
        .form-box input[type="text"], .form-box input[type="email"], .form-box input[type="password"] {
            width: 100%; padding: 10px; margin: 6px 0 14px 0; border: 1px solid #F8C8DC; border-radius: 6px; outline: none;
        }
        .form-box input[type="submit"] {
            width: 100%; padding: 12px; color: white; border: none; border-radius: 6px; font-weight: bold; cursor: pointer;
        }

        /* FOOTER */
        footer {
            background-color: #F8C8DC;
            text-align: center;
            padding: 20px;
            color: #6B4C57;
            font-weight: bold;
            margin-top: 50px;
            font-size: 15px;
        }
    </style>
</head>
<body>

    <header>
        <div></div>
        <h1>ADIDAS STORE</h1>
        <div class="header-btn-container">
            <a href="carrito.php"><button class="btn-carrito">Carrito (<?php echo $total_carrito; ?>)</button></a>
        </div>
    </header>

    <nav>
        <a href="index.php" class="nav-link active">INICIO</a>
        <a href="calzado.html" class="nav-link">CALZADO</a>
        <a href="ropa.html" class="nav-link">ROPA</a>
        <a href="#modal-cuenta" class="nav-link">MI CUENTA</a>
    </nav>

    <div class="container">
        <div class="banner-card">
            <h2>Bienvenido/a a la Colección Adidas Pastel</h2>
            <p>
                Descubre nuestra exclusiva tienda en línea inspirada en los tonos suaves y la elegancia urbana de Adidas. 
                Nos especializamos en ofrecerte las mejores siluetas de calzado y la indumentaria más icónica de la marca, 
                combinando el estilo deportivo retro con acabados en colores pastel cuidadosamente seleccionados.
            </p>

            <div class="grid-catalogo">
                <div class="card-seccion">
                    <div>
                        <h3>Calzado Exclusivo</h3>
                        <p>Explora nuestros modelos clásicos como Samba, Gazelle, Campus, Forum y Ultraboost.</p>
                    </div>
                    <a href="calzado.html"><button class="btn-catalogo btn-calzado">Ver Catálogo de Calzado</button></a>
                </div>

                <div class="card-seccion">
                    <div>
                        <h3>Ropa & Outfits</h3>
                        <p>Encuentra conjuntos deportivos, chamarras track, sudaderas cozy y playeras oversized.</p>
                    </div>
                    <a href="ropa.html"><button class="btn-catalogo btn-ropa">Ver Catálogo de Ropa</button></a>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL DE REGISTRO E INICIO DE SESIÓN -->
    <div id="modal-cuenta" class="modal-overlay">
        <div class="modal-content">
            <a href="index.php" class="btn-close">Cerrar [X]</a>
            <h2 style="color: #D87093; text-align: center; margin-bottom: 10px;">Gestión de Cuenta</h2>

            <div class="forms-grid">
                <div class="form-box">
                    <h3>Registro</h3>
                    <form action="registro.php" method="POST">
                        <label><small><b>Nombre Completo:</b></small></label>
                        <input type="text" name="nombre" placeholder="Tu nombre" required>
                        <label><small><b>Correo Electrónico:</b></small></label>
                        <input type="email" name="email" placeholder="correo@ejemplo.com" required>
                        <label><small><b>Contraseña:</b></small></label>
                        <input type="password" name="password" placeholder="********" required>
                        <input type="submit" value="Registrarse" style="background-color: #D87093;">
                    </form>
                </div>

                <div class="form-box">
                    <h3>Iniciar Sesión</h3>
                    <form action="login.php" method="POST">
                        <label><small><b>Correo Electrónico:</b></small></label>
                        <input type="email" name="email" placeholder="correo@ejemplo.com" required>
                        <label><small><b>Contraseña:</b></small></label>
                        <input type="password" name="password" placeholder="********" required>
                        <input type="submit" value="Ingresar" style="background-color: #B56576; margin-top: 38px;">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <footer>
        Vazquez Hernandez Eliana Karime- 0908- 5J
    </footer>

</body>
</html>