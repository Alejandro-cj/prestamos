<?= $this->extend('layouts/main'); ?>
<?= $this->section('title'); ?>
Monto inicial
<?= $this->endSection('title'); ?>

<?= $this->section('content'); ?>
<div class="card">
    <div class="card-header">
        <h4>Monto inicial</h4>
    </div>
    <div class="card-body">
        <form action="<?php echo base_url('cajas'); ?>" method="post">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="id_caja" value="0">
            <div class="form-group">
                <label>Monto</label>
                <input type="text" name="monto" class="form-control" value="<?php echo set_value('monto'); ?>" placeholder="0.00">
                <?php if (!empty($errors['monto_inicial'])) { ?>
                    <span class="text-danger"><?php echo $errors['monto_inicial']; ?></span>
                <?php } ?>
            </div>
            <div class="text-end">
                <a href="<?php echo base_url('cajas'); ?>" class="btn btn-danger">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection('content'); ?>