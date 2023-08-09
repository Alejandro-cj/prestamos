<?= $this->extend('layouts/main'); ?>
<?= $this->section('title'); ?>
Perfil de usuario
<?= $this->endSection('title'); ?>

<?= $this->section('content'); ?>
<div class="card">
    <div class="card-header">
        <h4>Perfil de usuario</h4>
    </div>
    <div class="card-body">
        <?php if (!empty(session()->getFlashdata('respuesta'))) { ?>
            <div class="alert alert-<?php echo session()->getFlashdata('respuesta')['type']; ?>">
                <?php echo session()->getFlashdata('respuesta')['msg']; ?>
            </div>
        <?php } ?>
        <div class="row mt-sm-4">
            <div class="col-12 col-md-12 col-lg-4">
                <div class="card author-box">
                    <div class="card-body">
                        <div class="author-box-center">
                            <?php $perfil = ($usuario['perfil'] != null) ? 'assets/img/users/' . $usuario['perfil'] : 'assets/img/logo.png'; ?>
                            <img alt="image" src="<?php echo base_url($perfil); ?>" class="rounded-circle author-box-picture">
                            <div class="clearfix"></div>
                            <div class="author-box-name">
                                <a href="#"><?php echo $_SESSION['nombre']; ?></a>
                            </div>
                            <div class="author-box-job"><?php echo $_SESSION['rol']; ?></div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4>Cambiar contraseña</h4>
                    </div>
                    <div class="card-body">
                        <div class="py-4">
                            <form action="<?php echo base_url('usuarios/cambiarClave'); ?>" method="post">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="_method" value="PUT">
                                <div class="mb-3">
                                    <label for="" class="form-label">Contraseña Actual</label>
                                    <input type="password" class="form-control" name="actual" placeholder="Contraseña Actual">
                                    <?php if (isset($validacion)) { ?>
                                        <span class="text-danger"><?php echo $validacion->getError('actual'); ?></span>
                                    <?php } ?>
                                </div>
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
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">Cambiar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-12 col-lg-8">
                <div class="card">
                    <div class="padding-20">
                        <ul class="nav nav-tabs" id="myTab2" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab2" data-bs-toggle="tab" href="#profile" role="tab" aria-selected="true">Perfil</a>
                            </li>
                        </ul>
                        <div class="tab-content tab-bordered" id="myTab3Content">
                            <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="home-tab2">
                                <form method="post" action="<?php echo base_url('profile'); ?>" enctype="multipart/form-data">
                                    <div class="card-header">
                                        <h4>Editar Perfil</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="_method" value="PUT">
                                            <input type="hidden" name="id_usuario" value="<?php echo $usuario['id']; ?>">

                                            <div class="form-group col-md-6 col-12">
                                                <label>Nombre</label>
                                                <input type="text" name="nombre" class="form-control" value="<?php echo set_value('nombre', $usuario['nombre']); ?>">
                                                <?php if (isset($validacion)) { ?>
                                                    <span class="text-danger"><?php echo $validacion->getError('nombre'); ?></span>
                                                <?php } ?>
                                            </div>
                                            <div class="form-group col-md-6 col-12">
                                                <label>Apellido</label>
                                                <input type="text" name="apellido" class="form-control" value="<?php echo set_value('apellido', $usuario['apellido']); ?>">
                                                <?php if (isset($validacion)) { ?>
                                                    <span class="text-danger"><?php echo $validacion->getError('apellido'); ?></span>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-7 col-12">
                                                <label>Correo</label>
                                                <input type="email" name="correo" class="form-control" value="<?php echo set_value('correo', $usuario['correo']); ?>">
                                                <?php if (isset($validacion)) { ?>
                                                    <span class="text-danger"><?php echo $validacion->getError('apellido'); ?></span>
                                                <?php } ?>
                                            </div>
                                            <div class="form-group col-md-5 col-12">
                                                <label>Teléfono</label>
                                                <input type="tel" name="telefono" class="form-control" value="<?php echo set_value('telefono', $usuario['telefono']); ?>">
                                                <?php if (isset($validacion)) { ?>
                                                    <span class="text-danger"><?php echo $validacion->getError('telefono'); ?></span>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-12">
                                                <label>Direccion</label>
                                                <textarea class="form-control summernote-simple" name="direccion"><?php echo set_value('direccion', $usuario['direccion']); ?></textarea>
                                                <?php if (isset($validacion)) { ?>
                                                    <span class="text-danger"><?php echo $validacion->getError('direccion'); ?></span>
                                                <?php } ?>
                                            </div>
                                            <div class="form-group col-12">
                                                <label for="" class="form-label">Foto (Opcional)</label>
                                                <input type="file" class="form-control" name="foto">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer text-end">
                                        <button class="btn btn-primary" type="submit">Guardar cambios</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection('content'); ?>