<?= $this->extend('layouts/main'); ?>
<?= $this->section('title'); ?>
Datos de la empresa
<?= $this->endSection('title'); ?>

<?= $this->section('content'); ?>
<div class="card">
    <div class="card-header">
        <h4>Datos de la empresa</h4>
    </div>
    <div class="card-body">
        <?php if (!empty(session()->getFlashdata('respuesta'))) { ?>
            <div class="alert alert-<?php echo session()->getFlashdata('respuesta')['type']; ?>">
                <?php echo session()->getFlashdata('respuesta')['msg']; ?>
            </div>
        <?php } ?>
        <form action="<?php echo base_url('admin/' . $admin['id']); ?>" method="post" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" name="id" value="<?php echo $admin['id']; ?>">
            <div class="row">
                <div class="form-group col-lg-4">
                    <label>Identidad <span class="text-danger">*</span></label>
                    <input type="number" name="identidad" class="form-control" value="<?php echo set_value('identidad', $admin['identidad']); ?>" placeholder="Identidad">
                    <?php if (!empty($errors['identidad'])) { ?>
                        <span class="text-danger"><?php echo $errors['identidad']; ?></span>
                    <?php } ?>
                </div>
                <div class="form-group col-lg-4">
                    <label>Nombre <span class="text-danger">*</span></label>
                    <input type="text" name="nombre" class="form-control" value="<?php echo set_value('nombre', $admin['nombre']); ?>" placeholder="Nombre">
                    <?php if (!empty($errors['nombre'])) { ?>
                        <span class="text-danger"><?php echo $errors['nombre']; ?></span>
                    <?php } ?>
                </div>
                <div class="form-group col-lg-4">
                    <label>Teléfono <span class="text-danger">*</span></label>
                    <input type="number" name="telefono" class="form-control" value="<?php echo set_value('telefono', $admin['telefono']); ?>" placeholder="Teléfono">
                    <?php if (!empty($errors['telefono'])) { ?>
                        <span class="text-danger"><?php echo $errors['telefono']; ?></span>
                    <?php } ?>
                </div>
                <div class="form-group col-lg-4">
                    <label>Correo electrónico <span class="text-danger">*</span></label>
                    <input type="email" name="correo" class="form-control" value="<?php echo set_value('correo', $admin['correo']); ?>" placeholder="Correo electrónico">
                    <?php if (!empty($errors['correo'])) { ?>
                        <span class="text-danger"><?php echo $errors['correo']; ?></span>
                    <?php } ?>
                </div>
                <div class="form-group col-lg-4">
                    <label>Tasa interes <span class="text-danger">*</span></label>
                    <input type="number" name="tasa_interes" class="form-control" value="<?php echo set_value('tasa_interes', $admin['tasa_interes']); ?>" placeholder="0">
                    <?php if (!empty($errors['tasa_interes'])) { ?>
                        <span class="text-danger"><?php echo $errors['tasa_interes']; ?></span>
                    <?php } ?>
                </div>
                <div class="form-group col-lg-4">
                    <label>Cuotas <span class="text-danger">*</span></label>
                    <input type="number" name="cuotas" class="form-control" value="<?php echo set_value('cuotas', $admin['cuotas']); ?>" placeholder="Cuotas">
                    <?php if (!empty($errors['cuotas'])) { ?>
                        <span class="text-danger"><?php echo $errors['cuotas']; ?></span>
                    <?php } ?>
                </div>
                <div class="form-group col-lg-4">
                    <label>Dirección <span class="text-danger">*</span></label>
                    <textarea class="form-control" name="direccion" rows="3" placeholder="Dirección"><?php echo set_value('direccion', $admin['direccion']); ?></textarea>
                    <?php if (!empty($errors['direccion'])) { ?>
                        <span class="text-danger"><?php echo $errors['direccion']; ?></span>
                    <?php } ?>
                </div>
                <div class="form-group col-lg-4">
                    <label>Mensaje (Opcional)</label>
                    <textarea class="form-control" name="mensaje" rows="3" placeholder="Mensaje"><?php echo set_value('mensaje', $admin['mensaje']); ?></textarea>
                </div>
                <div class="form-group col-lg-4">
                    <label for="" class="form-label">Logo (PNG - Opcional)</label>
                    <input type="file" class="form-control" name="logo">
                    <?php if (!empty($errors['logo'])) { ?>
                        <span class="text-danger"><?php echo $errors['logo']; ?></span>
                    <?php } ?>
                </div>
            </div>
            <div class="text-end">
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection('content'); ?>