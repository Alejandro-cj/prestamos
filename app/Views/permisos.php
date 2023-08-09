<?= $this->extend('layouts/principal/main'); ?>
<?= $this->section('title'); ?>
Permisos
<?= $this->endSection('title'); ?>

<?= $this->section('content'); ?>
<div class="card">
    <div class="card-header">
        <h4>Permisos</h4>
    </div>
    <div class="card-body">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        
            <strong>Advertencia!</strong> No tienes permisos.
        </div>
        
    </div>
</div>
<?= $this->endSection('content'); ?>