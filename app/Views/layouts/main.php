<?= $this->include('layouts/head.php'); ?>
<body>
  <div class="loader"></div>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
      <div class="navbar-bg"></div>
      <?= $this->include('layouts/menu.php'); ?>
      
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
            <!-- contenido -->
            <?= $this->renderSection('content'); ?>
        </section>
        
        <?= $this->renderSection('modal'); ?>

        <?= $this->include('layouts/paint.php'); ?>
      </div>
      <?= $this->include('layouts/footer.php'); ?>
    </div>
  </div>
  <?= $this->include('layouts/scripts.php'); ?>
</body>

</html>