<?= $this->extend('layouts/main'); ?>
<?= $this->section('title'); ?>
Editar cliente
<?= $this->endSection('title'); ?>

<?= $this->section('content'); ?>
<div class="card">
    <div class="card-header">
        <h4>Editar cliente</h4>
    </div>
    <div class="card-body">
        <form action="<?php echo base_url('clientes/' . $cliente['id']); ?>" method="post" autocomplete="off">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" name="id_cliente" value="<?php echo $cliente['id']; ?>">
            <div class="row">
                <div class="form-group col-lg-4">
                    <label>Identidad</label>
                    <input type="text" name="identidad" class="form-control" value="<?php echo set_value('identidad', $cliente['identidad']); ?>" placeholder="Identidad">
                    <?php if (!empty($errors['identidad'])) { ?>
                        <span class="text-danger"><?php echo $errors['identidad']; ?></span>
                    <?php } ?>
                </div>
                <div class="form-group col-lg-4">
                    <label>N° identidad</label>
                    <input type="text" name="num_identidad" class="form-control" value="<?php echo set_value('num_identidad', $cliente['num_identidad']); ?>" placeholder="N° identidad">
                    <?php if (!empty($errors['num_identidad'])) { ?>
                        <span class="text-danger"><?php echo $errors['num_identidad']; ?></span>
                    <?php } ?>
                </div>
                <div class="form-group col-lg-4">
                    <label>Nombre</label>
                    <input type="text" name="nombre" class="form-control" value="<?php echo set_value('nombre', $cliente['nombre']); ?>" placeholder="Nombre">
                    <?php if (!empty($errors['nombre'])) { ?>
                        <span class="text-danger"><?php echo $errors['nombre']; ?></span>
                    <?php } ?>
                </div>
                <div class="form-group col-lg-4">
                    <label>Apellidos</label>
                    <input type="text" name="apellido" class="form-control" value="<?php echo set_value('apellido', $cliente['apellido']); ?>" placeholder="Apellido">
                    <?php if (!empty($errors['apellido'])) { ?>
                        <span class="text-danger"><?php echo $errors['apellido']; ?></span>
                    <?php } ?>
                </div>
                <div class="form-group col-lg-4">
                    <label>Teléfono</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i class="fas fa-phone"></i>
                            </div>
                        </div>
                        <input type="text" name="telefono" class="form-control phone-number" value="<?php echo set_value('telefono', $cliente['telefono']); ?>" placeholder="Telefono">

                    </div>
                    <?php if (!empty($errors['telefono'])) { ?>
                        <span class="text-danger"><?php echo $errors['telefono']; ?></span>
                    <?php } ?>
                </div>
                <div class="form-group col-lg-4">
                    <label>Whatsapp</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i class="fab fa-whatsapp-square"></i>
                            </div>
                        </div>
                        <input type="text" name="whatsapp" class="form-control phone-number" value="<?php echo set_value('whatsapp', $cliente['whatsapp']); ?>" placeholder="Whatsapp">
                    </div>
                    <?php if (!empty($errors['whatsapp'])) { ?>
                        <span class="text-danger"><?php echo $errors['whatsapp']; ?></span>
                    <?php } ?>
                </div>
                <div class="form-group col-lg-4">
                    <label>Correo</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i class="fas fa-envelope"></i>
                            </div>
                        </div>
                        <input type="text" name="correo" class="form-control" value="<?php echo set_value('correo', $cliente['correo']); ?>" placeholder="Correo">

                    </div>
                    <?php if (!empty($errors['correo'])) { ?>
                        <span class="text-danger"><?php echo $errors['correo']; ?></span>
                    <?php } ?>
                </div>
                <div class="form-group col-lg-4">
                    <label>Direccion</label>
                    <input type="text" name="direccion" class="form-control" value="<?php echo set_value('direccion', $cliente['direccion']); ?>" placeholder="Direccion">
                    <?php if (!empty($errors['direccion'])) { ?>
                        <span class="text-danger"><?php echo $errors['direccion']; ?></span>
                    <?php } ?>
                </div>
            </div>
            <div class="text-end">
                <a href="<?php echo base_url('clientes'); ?>" class="btn btn-danger">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection('content'); ?>