<?= $this->extend('layouts/main'); ?>
<?= $this->section('title'); ?>
Gestion clientes
<?= $this->endSection('title'); ?>

<?= $this->section('content');

if (verificar('nuevo cliente', $_SESSION['permisos'])) { ?>
    <a href="<?php echo base_url('clientes/new'); ?>" class="btn btn-primary mb-2">Nuevo</a>
<?php } ?>

<div class="card">
    <div class="card-header">
        <h4>Gestion clientes</h4>
    </div>
    <div class="card-body">
        <?php if (!empty(session()->getFlashdata('respuesta'))) { ?>
            <div class="alert alert-<?php echo session()->getFlashdata('respuesta')['type']; ?>">
                <?php echo session()->getFlashdata('respuesta')['msg']; ?>
            </div>
        <?php } ?>
        <div class="table-responsive">
            <table class="table table-striped nowrap" id="tblClientes" style="width:100%">
                <thead>
                    <tr>
                        <th>Acciones</th>
                        <th class="text-center">
                            #
                        </th>
                        <th>Identidad</th>
                        <th>N° identidad</th>
                        <th>Nombres</th>
                        <th>Telefono</th>
                        <th>Correo</th>
                        <th>Direccion</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection('content'); ?>

<?= $this->section('js'); ?>
<script src="<?php echo base_url('assets/js/pages/clientes.js'); ?>"></script>
<?= $this->endSection('js'); ?>