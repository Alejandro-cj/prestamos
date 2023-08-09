<?= $this->extend('layouts/main'); ?>
<?= $this->section('title'); ?>
Nuevo usuario
<?= $this->endSection('title'); ?>

<?= $this->section('content'); ?>
<div class="card">
    <div class="card-header">
        <h4>Nuevo usuario</h4>
    </div>
    <div class="card-body">
        <form action="<?php echo base_url('usuarios'); ?>" method="post" autocomplete="off">
            <?php echo csrf_field(); ?>
            <div class="row">
                <div class="form-group col-lg-4">
                    <label>Nombre</label>
                    <input type="text" name="nombre" class="form-control" value="<?php echo set_value('nombre'); ?>" placeholder="Nombre">
                    <?php if (isset($validacion)) { ?>
                        <span class="text-danger"><?php echo $validacion->getError('nombre'); ?></span>
                    <?php } ?>
                </div>
                <div class="form-group col-lg-4">
                    <label>Apellidos</label>
                    <input type="text" name="apellido" class="form-control" value="<?php echo set_value('apellido'); ?>" placeholder="Apellido">
                    <?php if (isset($validacion)) { ?>
                        <span class="text-danger"><?php echo $validacion->getError('apellido'); ?></span>
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
                        <input type="text" name="telefono" class="form-control phone-number" value="<?php echo set_value('telefono'); ?>" placeholder="Telefono">

                    </div>
                    <?php if (isset($validacion)) { ?>
                        <span class="text-danger"><?php echo $validacion->getError('telefono'); ?></span>
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
                        <input type="text" name="correo" class="form-control" value="<?php echo set_value('correo'); ?>" placeholder="Correo">

                    </div>
                    <?php if (isset($validacion)) { ?>
                        <span class="text-danger"><?php echo $validacion->getError('correo'); ?></span>
                    <?php } ?>
                </div>
                <div class="form-group col-lg-4">
                    <label>Direccion</label>
                    <input type="text" name="direccion" class="form-control" value="<?php echo set_value('direccion'); ?>" placeholder="Direccion">
                    <?php if (isset($validacion)) { ?>
                        <span class="text-danger"><?php echo $validacion->getError('direccion'); ?></span>
                    <?php } ?>
                </div>
                <div class="form-group col-lg-4">
                    <label>Rol</label>
                    <select name="rol" class="form-control">
                        <option value="">Seleccionar</option>
                        <?php foreach ($roles as $rol) { ?>
                            <option value="<?php echo $rol['id']; ?>" <?= set_select('rol', $rol['id'], (!empty($rol) && $rol == $rol['id'] ? true : false)) ?>>
                                <?php echo $rol['nombre']; ?>
                            </option>
                        <?php } ?>
                    </select>
                    <?php if (isset($validacion)) { ?>
                        <span class="text-danger"><?php echo $validacion->getError('rol'); ?></span>
                    <?php } ?>
                </div>
                <div class="form-group col-lg-4">
                    <label>Contraseña</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </div>
                        </div>
                        <input type="password" name="clave" class="form-control pwstrength" placeholder="Contraseña">

                    </div>
                    <?php if (isset($validacion)) { ?>
                        <span class="text-danger"><?php echo $validacion->getError('clave'); ?></span>
                    <?php } ?>
                </div>
                <div class="form-group col-lg-4">
                    <label>Confirmar Contraseña</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </div>
                        </div>
                        <input type="password" name="confirmar" class="form-control pwstrength" placeholder="Confirmar Contraseña">

                    </div>
                    <?php if (isset($validacion)) { ?>
                        <span class="text-danger"><?php echo $validacion->getError('confirmar'); ?></span>
                    <?php } ?>
                </div>
            </div>
            <div class="text-end">
                <a href="<?php echo base_url('usuarios'); ?>" class="btn btn-danger">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection('content'); ?>