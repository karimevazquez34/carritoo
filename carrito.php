<?php
session_start();
include('config/conexion.php');

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

if (isset($_GET['action']) && $_GET['action'] == 'add') {
    $prod_nombre = $_GET['producto'];
    $categoria = (strpos(strtolower($prod_nombre), 'chamarra') !== false || 
                  strpos(strtolower($prod_nombre), 'playera') !== false || 
                  strpos(strtolower($prod_nombre), 'top') !== false || 
                  strpos(strtolower($prod_nombre), 'pants') !== false || 
                  strpos(strtolower($prod_nombre), 'sudadera') !== false || 
                  strpos(strtolower($prod_nombre), 'conjunto') !== false || 
                  strpos(strtolower($prod_nombre), 'tracksuit') !== false) ? 'Ropa' : 'Calzado';

    $item = [
        'nombre'    => $prod_nombre,
        'precio'    => $_GET['precio'],
        'img'       => $_GET['img'],
        'categoria' => $categoria
    ];
    array_push($_SESSION['carrito'], $item);
    header("Location: carrito.php");
    exit();
}

if (isset($_GET['action']) && $_GET['action'] == 'empty') {
    $_SESSION['carrito'] = [];
    header("Location: carrito.php");
    exit();
}

$mensaje_exito = false;
if (isset($_POST['finalizar_compra'])) {
    if (!empty($_SESSION['carrito'])) {
        $usuario_id = isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : 1;
        $talla = isset($_POST['talla']) ? mysqli_real_escape_string($conexion, $_POST['talla']) : 'Unitalla';
        $cantidad = isset($_POST['cantidad']) ? (int)$_POST['cantidad'] : 1;
        
        $calle = mysqli_real_escape_string($conexion, $_POST['direccion']);
        $municipio = mysqli_real_escape_string($conexion, $_POST['municipio']);
        $estado = mysqli_real_escape_string($conexion, $_POST['estado']);
        $direccion_completa = $calle . ", " . $municipio . ", " . $estado;

        foreach ($_SESSION['carrito'] as $prod) {
            $nombre_p = mysqli_real_escape_string($conexion, $prod['nombre']);
            $categoria_p = mysqli_real_escape_string($conexion, $prod['categoria']);

            $query = "INSERT INTO pedidos (usuario_id, categoria, producto, talla, cantidad, direccion) 
                      VALUES ('$usuario_id', '$categoria_p', '$nombre_p', '$talla', '$cantidad', '$direccion_completa')";
            mysqli_query($conexion, $query);
        }
        
        $_SESSION['carrito'] = [];
        $mensaje_exito = true;
    }
}

$mostrar_pago = isset($_GET['action']) && $_GET['action'] == 'pagar';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adidas Store - Tu Carrito</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #FFF0F5; color: #4A4A4A; display: flex; flex-direction: column; min-height: 100vh; }
        a { text-decoration: none; }

        header {
            background-color: #F8C8DC;
            padding: 25px 5%;
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            align-items: center;
            box-shadow: 0 4px 15px rgba(216, 112, 147, 0.2);
        }
        header h1 { grid-column: 2; color: #6B4C57; font-size: 32px; letter-spacing: 3px; text-align: center; font-weight: 800; }
        .header-btn-container { grid-column: 3; justify-self: end; }
        .btn-inicio { color: #6B4C57; font-weight: bold; }

        nav { background-color: #ffffff; display: flex; justify-content: center; gap: 15px; padding: 14px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04); margin-bottom: 35px; }
        .nav-link { padding: 10px 25px; border-radius: 20px; color: #6B4C57; font-weight: bold; font-size: 14px; transition: all 0.3s ease; }
        .nav-link:hover { background-color: #FFF0F5; color: #D87093; }

        .container { max-width: 800px; margin: 0 auto; padding: 0 20px; flex: 1; }
        .titulo-seccion { text-align: center; color: #D87093; font-size: 26px; margin-bottom: 25px; }

        /* TABLA DE CARRITO ESTILIZADA */
        .cart-table {
            width: 100%; border-collapse: collapse; background-color: #FFFFFF; border-radius: 12px; overflow: hidden; box-shadow: 0 8px 20px rgba(0,0,0,0.05); margin-bottom: 25px;
        }
        .cart-table th { background-color: #F8C8DC; color: #6B4C57; padding: 15px; font-size: 14px; }
        .cart-table td { padding: 15px; text-align: center; border-bottom: 1px solid #FFF0F5; }
        .cart-table img { border-radius: 6px; }

        .btn-action { padding: 10px 20px; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; color: white; }
        .btn-pay { background-color: #28a745; }
        .btn-empty { background-color: #dc3545; }

        /* FORMULARIO DE PAGO */
        .checkout-box {
            background-color: #FFFFFF; border-radius: 16px; padding: 30px; border: 2px solid #F3D1DC; box-shadow: 0 10px 25px rgba(0,0,0,0.05); margin-top: 30px;
        }
        .checkout-box h3 { color: #D87093; text-align: center; margin-bottom: 15px; }
        .checkout-box input, .checkout-box select, .checkout-box textarea {
            width: 100%; padding: 10px; margin: 6px 0 14px 0; border: 1px solid #F8C8DC; border-radius: 6px; outline: none;
        }

        footer { background-color: #F8C8DC; text-align: center; padding: 20px; color: #6B4C57; font-weight: bold; margin-top: 50px; font-size: 15px; }
    </style>
</head>
<body>

    <header>
        <div></div>
        <h1>ADIDAS STORE</h1>
        <div class="header-btn-container">
            <a href="index.php" class="btn-inicio">Volver al Inicio</a>
        </div>
    </header>

    <nav>
        <a href="index.php" class="nav-link">INICIO</a>
        <a href="calzado.html" class="nav-link">CALZADO</a>
        <a href="ropa.html" class="nav-link">ROPA</a>
        <a href="index.php#modal-cuenta" class="nav-link">MI CUENTA</a>
    </nav>

    <div class="container">
        <?php if ($mensaje_exito) { ?>
            <div style="background-color: #D4EDDA; color: #155724; padding: 15px; border-radius: 8px; text-align: center; margin-bottom: 20px; font-weight: bold;">
                ¡Pago procesado con éxito! Los productos han sido registrados en phpMyAdmin.
            </div>
        <?php } ?>

        <h2 class="titulo-seccion">Productos Seleccionados</h2>

        <table class="cart-table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Imagen</th>
                    <th>Precio</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $total = 0;
                if (!empty($_SESSION['carrito'])) {
                    foreach ($_SESSION['carrito'] as $prod) {
                        $total += $prod['precio'];
                ?>
                    <tr>
                        <td><b><?php echo htmlspecialchars($prod['nombre']); ?></b></td>
                        <td><img src="<?php echo htmlspecialchars($prod['img']); ?>" width="60" height="50"></td>
                        <td><font color="#B56576">$<?php echo number_format($prod['precio'], 2); ?></font></td>
                    </tr>
                <?php 
                    }
                } else {
                    echo "<tr><td colspan='3'>El carrito está vacío.</td></tr>";
                }
                ?>
                <tr style="background-color: #FFF0F5;">
                    <td colspan="2" style="text-align: right;"><b>Total:</b></td>
                    <td><font color="#D87093" size="4"><b>$<?php echo number_format($total, 2); ?></b></font></td>
                </tr>
            </tbody>
        </table>

        <?php if (!empty($_SESSION['carrito'])) { ?>
            <div style="text-align: center;">
                <a href="carrito.php?action=pagar#pasarela"><button class="btn-action btn-pay">PAGAR AHORA</button></a>
                <a href="carrito.php?action=empty"><button class="btn-action btn-empty">Vaciar Carrito</button></a>
            </div>
        <?php } ?>

        <?php if ($mostrar_pago && !empty($_SESSION['carrito'])) { ?>
            <a name="pasarela"></a>
            <div class="checkout-box">
                <h3>Datos personales</h3>
                <form action="carrito.php" method="POST">
                    <label><small><b>Forma de Pago:</b></small></label>
                    <select name="forma_pago" required>
                        <option value="">-- Selecciona una Forma de Pago --</option>
                        <option value="tarjeta_credito">Tarjeta de Crédito</option>
                        <option value="tarjeta_debito">Tarjeta de Débito</option>
                        <option value="transferencia">Transferencia Electrónica (SPEI)</option>
                        <option value="efectivo">Pago en Efectivo (OXXO / Conveniencia)</option>
                    </select>

                    <label><small><b>Ubicación:</b></small></label>
                    <input type="text" name="ubicacion" placeholder="Ej. México" required>

                    <div style="display: flex; gap: 10px;">
                        <div style="flex:1;"><label><small><b>Nombre:</b></small></label><input type="text" name="nombre" placeholder="Karime" required></div>
                        <div style="flex:1;"><label><small><b>Apellido:</b></small></label><input type="text" name="apellido" placeholder="Vázquez" required></div>
                    </div>

                    <label><small><b>Número de Teléfono:</b></small></label>
                    <input type="tel" name="telefono" placeholder="5512345678" required>

                    <div style="display: flex; gap: 10px;">
                        <div style="flex:1;"><label><small><b>Código Postal:</b></small></label><input type="text" name="cp" placeholder="01000" required></div>
                        <div style="flex:1;"><label><small><b>Estado:</b></small></label><input type="text" name="estado" placeholder="CDMX / Estado" required></div>
                    </div>

                    <label><small><b>Municipio / Distrito / Ciudad:</b></small></label>
                    <input type="text" name="municipio" placeholder="Ej. Cuauhtémoc" required>

                    <label><small><b>Dirección:</b></small></label>
                    <textarea name="direccion" rows="2" placeholder="Calle, número exterior e interior, colonia" required></textarea>

                    <div style="display: flex; gap: 10px;">
                        <div style="flex:1;"><label><small><b>Talla:</b></small></label><input type="text" name="talla" placeholder="Ej. 24 MX / M" required></div>
                        <div style="flex:1;"><label><small><b>Cantidad:</b></small></label><input type="number" name="cantidad" value="1" min="1" required></div>
                        <div style="flex:1;">
                            <label><small><b>Tipo Documento:</b></small></label>
                            <select name="tipo_doc" required>
                                <option value="CURP">CURP</option>
                                <option value="RFC">RFC</option>
                            </select>
                        </div>
                    </div>

                    <br>
                    <button type="submit" name="finalizar_compra" class="btn-action" style="background-color: #D87093; width: 100%; padding: 12px;">Finalizar Simulación de Pago</button>
                </form>
            </div>
        <?php } ?>
    </div>

    <footer>
        Vazquez Hernandez Eliana Karime- 0908- 5J
    </footer>

</body>
</html>