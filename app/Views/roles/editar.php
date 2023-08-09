<?= $this->extend('layouts/main'); ?>
<?= $this->section('title'); ?>
Editar rol
<?= $this->endSection('title'); ?>

<?= $this->section('content'); ?>
<div class="card">
    <div class="card-header">
        <h4>Editar rol</h4>
    </div>
    <div class="card-body">
        <form action="<?php echo base_url('roles/' . $rol['id']); ?>" method="post" autocomplete="off">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" name="id_rol" value="<?php echo $rol['id']; ?>">
            <div class="row">
                <div class="form-group col-lg-12">
                    <label>Nombre</label>
                    <input type="text" name="nombre" class="form-control" value="<?php echo set_value('nombre', $rol['nombre']); ?>" placeholder="Nombre">
                    <?php if (!empty($errors['nombre'])) { ?>
                        <span class="text-danger"><?php echo $errors['nombre']; ?></span>
                    <?php } ?>
                </div>
                <?php foreach ($permisos as $permiso) { ?>
                    <div class="col-lg-4">
                        <div class="accordion" id="accordion<?php echo $permiso['modulo']; ?>">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading<?php echo $permiso['id']; ?>">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $permiso['id']; ?>" aria-expanded="false" aria-controls="collapse<?php echo $permiso['id']; ?>">
                                        <?php echo ucfirst($permiso['modulo']); ?>
                                    </button>
                                </h2>
                                <div id="collapse<?php echo $permiso['id']; ?>" class="accordion-collapse collapse fade" aria-labelledby="heading<?php echo $permiso['id']; ?>" data-bs-parent="#accordion<?php echo $permiso['modulo']; ?>">
                                    <div class="accordion-body">
                                        <?php $lista = json_decode($permiso['campos'], true);
                                        for ($i = 0; $i < count($lista); $i++) { ?>
                                            <div class="form-check">
                                              <input class="form-check" type="checkbox" value="<?php echo $lista[$i]; ?>" name="permisos[]"
                                              <?php echo set_checkbox('permisos[]', $lista[$i]); ?>
                                              <?php echo (verificar($lista[$i], $activos)) ? 'checked' : ''; ?>>
                                              <label class="form-check-label text-uppercase">
                                                <?php echo $lista[$i]; ?>
                                              </label>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="text-end">
                <a href="<?php echo base_url('roles'); ?>" class="btn btn-danger">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection('content'); ?>