<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contrato</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/reporte.css'); ?>">
</head>

<body>
    <table id="datos-empresa">
        <tr>
            <td class="logo">
                <img src="<?php echo base_url('assets/img/logo.png'); ?>" alt="">
            </td>
            <td class="info-empresa">
                <p><?php echo $empresa['nombre']; ?></p>
                <p><?php echo $empresa['identidad']; ?></p>
                <p>Telefono: <?php echo $empresa['telefono']; ?></p>
                <p>Dirección: <?php echo $empresa['direccion']; ?></p>
            </td>
            <td class="info-fecha">
                <div class="container-fecha">
                    <span class="contrato">Contrato</span>
                    <p>N° <strong><?php echo $prestamo['id']; ?></strong></p>
                    <p>Fecha: <?php
                                $dato = $prestamo['fecha'];
                                $fecha = date('Y-m-d', strtotime($dato));
                                $hora = date('h:i A', strtotime($dato));
                                echo fechaPerzo($fecha);
                                ?></p>
                    <p>Hora: <?php echo $hora; ?></p>
                </div>
            </td>
        </tr>
    </table>
    <h5 class="title">Datos del cliente</h5>
    <div class="container-cliente">
        <table id="container-info">
            <tr>
                <td>
                    <strong><?php echo $prestamo['identidad']; ?></strong>
                    <p><?php echo $prestamo['num_identidad']; ?></p>
                </td>
                <td>
                    <strong>Nombre</strong>
                    <p><?php echo $prestamo['cliente'] . ' ' . $prestamo['apellido']; ?></p>
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Teléfono</strong>
                    <p><?php echo $prestamo['telefono']; ?></p>
                </td>
                <td>
                    <strong>Dirección</strong>
                    <p><?php echo $prestamo['direccion']; ?></p>
                </td>
            </tr>
        </table>
    </div>
    <h5 class="title">Datos de las cuotas</h5>
    <table id="container-cuotas">
        <thead>
            <tr>
                <th class="text-left">Item</th>
                <th class="text-left">Cuota</th>
                <th class="text-left">Vencimiento</th>
                <th class="text-left">Importe x cuota</th>
                <th class="text-left">Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php $item = 1;
            $total = 0;
            $date = date('Y-m-d');
            foreach ($detalles as $detalle) {
                $total += $detalle['importe_cuota'];
                $estado = '<span class="text-danger">PENDIENTE</span>';
                if ($date > $detalle['fecha_venc'] && $detalle['estado'] == 1) {
                    $class = 'bg-danger';
                } else if ($date == $detalle['fecha_venc'] && $detalle['estado'] == 1) {
                    $class = 'bg-warning';
                } else {
                    $class = '';
                    if ($detalle['estado'] == 1) {
                        $estado = '<span class="text-danger">PENDIENTE</span>';
                    } else {
                        $estado = '<span class="text-success">PAGADO</span>';
                    }
                }
            ?>
                <tr class="<?php echo $class; ?>">
                    <td><?php echo $item; ?></td>
                    <td>Cuota <?php echo $detalle['cuota']; ?></td>
                    <td><?php echo fechaPerzo($detalle['fecha_venc']); ?></td>
                    <td><?php echo $detalle['importe_cuota']; ?></td>
                    <td><?php echo $estado; ?></td>
                </tr>
            <?php $item++;
            } ?>
            <tr>
                <td colspan="4" class="text-right">
                    <h3>Total <?php echo number_format($total, 2); ?></h3>
                </td>
                <td></td>
            </tr>
        </tbody>
    </table>
    <div class="mensaje">
        <?php
        echo $empresa['mensaje'];
        if ($prestamo['estado'] == 0) {
            echo '<h1>CONTRATO FINALIZADO</h1>';
        }
        ?>
    </div>
</body>

</html>