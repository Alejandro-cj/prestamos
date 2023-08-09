<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="csrf_token" content="<?= csrf_token() ?>">
  <meta name="csrf_hash" content="<?= csrf_hash() ?>">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Sistema de prestamos | <?= $this->renderSection('title'); ?></title>
  <?= $this->include('layouts/styles.php'); ?>
  <link rel='shortcut icon' type='image/x-icon' href='<?= base_url('assets/img/favicon.ico'); ?>' />
</head>