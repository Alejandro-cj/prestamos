<?= $this->extend('layouts/principal/main'); ?>
<?= $this->section('title'); ?>
Restablecer
<?= $this->endSection('title'); ?>

<?= $this->section('content'); ?>
<div class="card card-primary">
    <div class="card-header">
        <h4>Restablecer</h4>
    </div>
    <div class="card-body">
        <img src="<?php echo base_url('assets/img/logo.png'); ?>" class="img-fluid rounded-top" alt="LOGO" width="200">
        <?php if (!empty(session()->getFlashdata('respuesta'))) { ?>
            <div class="alert alert-<?php echo session()->getFlashdata('respuesta')['type']; ?>">
                <?php echo session()->getFlashdata('respuesta')['msg']; ?>
            </div>
        <?php } ?>
        <form method="POST" action="<?= base_url('restablecer'); ?>" autocomplete="off">
            <?= csrf_field() ?>
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" name="token" value="<?php echo $token; ?>">
            <div class="mb-3">
                <label for="" class="form-label">Nueva contraseña</label>
                <input type="password" class="form-control" name="nueva" placeholder="Nueva contraseña">
                <?php if (isset($validacion)) { ?>
                    <span class="text-danger"><?php echo $validacion->getError('nueva'); ?></span>
                <?php } ?>
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Confirmar contraseña</label>
                <input type="password" class="form-control" name="confirmar" placeholder="Confirmar contraseña">
                <?php if (isset($validacion)) { ?>
                    <span class="text-danger"><?php echo $validacion->getError('confirmar'); ?></span>
                <?php } ?>
            </div>
            <div class="form-group text-end">
                <button type="submit" class="btn btn-primary btn-lg" tabindex="4">
                    Restablecer
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection('content'); ?>