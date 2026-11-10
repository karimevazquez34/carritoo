<?php
session_start();
include('config/conexion.php');

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$producto = isset($_GET['producto']) ? $_GET['producto'] : '';
$categoria = isset($_GET['categoria']) ? $_GET['categoria'] : 'Calzado';
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario_id = $_SESSION['usuario_id'];
    $producto   = $_POST['producto'];
    $categoria  = $_POST['categoria'];
    $talla      = $_POST['talla'];
    $cantidad   = $_POST['cantidad'];
    $direccion  = $_POST['direccion'];

    $sql = "INSERT INTO pedidos (usuario_id, categoria, producto, talla, cantidad, direccion) 
            VALUES ('$usuario_id', '$categoria', '$producto', '$talla', '$cantidad', '$direccion')";

    if ($conexion->query($sql) === TRUE) {
        $mensaje = "¡Tu pedido de <b>$producto</b> se registró correctamente!";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Adidas Store - Finalizar Compra</title>
</head>
<body bgcolor="#FFF0F5" text="#4A4A4A">

    <table border="0" width="100%" cellpadding="15" cellspacing="0" bgcolor="#F8C8DC">
        <tr>
            <td align="center">
                <h1><font color="#6B4C57" face="Segoe UI, Arial">ADIDAS STORE</font></h1>
            </td>
        </tr>
    </table>

    <br><br>

    <?php if (!empty($mensaje)) { ?>
        <table border="1" align="center" cellpadding="10" cellspacing="0" bgcolor="#D4EDDA" bordercolor="#C3E6CB" width="500">
            <tr>
                <td align="center">
                    <font color="#155724" face="Segoe UI, Arial"><?php echo $mensaje; ?></font>
                    <br><br>
                    <a href="index.php"><font color="#B56576">Volver al Inicio</font></a>
                </td>
            </tr>
        </table>
    <?php } else { ?>
        <table border="2" align="center" cellpadding="15" cellspacing="0" bgcolor="#FFFFFF" bordercolor="#F3D1DC" width="500">
            <tr>
                <td>
                    <h3 align="center"><font color="#D87093" face="Segoe UI, Arial">Formulario de Pedido</font></h3>
                    <p align="center"><font color="#705060" face="Segoe UI, Arial">Producto: <b><?php echo htmlspecialchars($producto); ?></b> (<?php echo htmlspecialchars($categoria); ?>)</font></p>
                    <hr color="#F8C8DC" size="1">

                    <form action="comprar.php" method="POST">
                        <input type="hidden" name="producto" value="<?php echo htmlspecialchars($producto); ?>">
                        <input type="hidden" name="categoria" value="<?php echo htmlspecialchars($categoria); ?>">

                        <p>
                            <label><font color="#705060" face="Segoe UI, Arial"><b>Talla:</b></font></label><br>
                            <select name="talla" required>
                                <option value="" disabled selected>-- Selecciona Talla --</option>
                                <?php if ($categoria == 'Calzado') { ?>
                                    <option value="23 MX">23 MX</option>
                                    <option value="24 MX">24 MX</option>
                                    <option value="25 MX">25 MX</option>
                                    <option value="26 MX">26 MX</option>
                                <?php } else { ?>
                                    <option value="S">Chica (S)</option>
                                    <option value="M">Mediana (M)</option>
                                    <option value="L">Grande (L)</option>
                                <?php } ?>
                            </select>
                        </p>

                        <p>
                            <label><font color="#705060" face="Segoe UI, Arial"><b>Cantidad:</b></font></label><br>
                            <input type="number" name="cantidad" value="1" min="1" max="10" required>
                        </p>

                        <p>
                            <label><font color="#705060" face="Segoe UI, Arial"><b>Dirección de Envío:</b></font></label><br>
                            <textarea name="direccion" rows="3" cols="35" placeholder="Calle, número, colonia y C.P." minlength="10" required></textarea>
                        </p>

                        <p align="center">
                            <input type="submit" value="Confirmar y Comprar">
                        </p>
                    </form>
                </td>
            </tr>
        </table>
    <?php } ?>

</body>
</html>