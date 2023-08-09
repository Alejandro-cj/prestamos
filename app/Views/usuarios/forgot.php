<?= $this->extend('layouts/principal/main'); ?>
<?= $this->section('title'); ?>
Olvidaste tu contraseña
<?= $this->endSection('title'); ?>

<?= $this->section('content'); ?>
<div class="card card-primary">
    <div class="card-header">
        <h4>Olvidaste tu contraseña</h4>
    </div>
    <div class="card-body">
        <img src="<?php echo base_url('assets/img/logo.png'); ?>" class="img-fluid rounded-top" alt="LOGO" width="200">
        <?php if (!empty(session()->getFlashdata('respuesta'))) { ?>
            <div class="alert alert-<?php echo session()->getFlashdata('respuesta')['type']; ?>">
                <?php echo session()->getFlashdata('respuesta')['msg']; ?>
            </div>
        <?php } ?>
        <form method="POST" action="<?= base_url('reset'); ?>" autocomplete="off">
            <?= csrf_field() ?>
            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="text" class="form-control" name="email" value="<?= set_value('email'); ?>" placeholder="Correo electrónico" tabindex="1" autofocus>
                <?php if (isset($validator)) { ?>
                    <span class="text-danger"><?php echo $validator->getError('email'); ?></span>
                <?php } ?>
            </div>
            <div class="form-group text-end">
                <button type="submit" class="btn btn-primary btn-lg" tabindex="4">
                    Enviar
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection('content'); ?>