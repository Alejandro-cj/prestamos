<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes</title>
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
                    <span class="contrato">Información</span>
                    <p>Fecha: <?php
                                $dato = date('Y-m-d');
                                echo fechaPerzo(date('Y-m-d', strtotime($dato)));
                                ?></p>
                    <p>Hora: <?php echo date('h:i A'); ?></p>
                </div>
            </td>
        </tr>
    </table>
    <h5 class="title">Datos de los prestamos</h5>
    <table id="container-cuotas">
        <thead>
            <tr>
                <th class="text-left">#</th>
                <th class="text-left">Cliente</th>
                <th class="text-left">Importe</th>
                <th class="text-left">Modalidad</th>
                <th class="text-left">Tasa interes</th>
                <th class="text-left">F. vencimiento</th>
            </tr>
        </thead>
        <tbody>
            <?php $item = 1;
            $total = 0;
            $date = date('Y-m-d');
            foreach ($prestamos as $prestamo) {
                $total += $prestamo['importe'];
                if ($date > $prestamo['fecha_venc']) {
                    $class = 'bg-danger';
                } else if ($date == $prestamo['fecha_venc']) {
                    $class = 'bg-warning';
                } else {
                    $class = '';
                }
            ?>
                <tr class="<?php echo $class; ?>">
                    <td><?php echo $item; ?></td>
                    <td><?php echo $prestamo['cliente']; ?></td>
                    <td><?php echo $prestamo['importe']; ?></td>
                    <td><?php echo $prestamo['modalidad']; ?></td>
                    <td><?php echo $prestamo['tasa_interes']; ?></td>
                    <td><?php echo fechaPerzo($prestamo['fecha_venc']); ?></td>
                </tr>
            <?php $item++;
            } ?>
            <tr>
                <td colspan="3" class="text-right">
                    <h3>Total <?php echo number_format($total, 2); ?></h3>
                </td>
                <td></td>
            </tr>
        </tbody>
    </table>
    <div class="mensaje">
        <?php echo $empresa['mensaje']; ?>
    </div>
</body>

</html>