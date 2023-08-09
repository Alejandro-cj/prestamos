<!-- General JS Scripts -->
<script src="<?= base_url('assets/js/app.min.js'); ?>"></script>
<script src="<?= base_url('assets/bundles/all.min.js'); ?>"></script>
<!-- Template JS File -->
<script src="<?= base_url('assets/js/scripts.js'); ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?= base_url('assets/js/jquery-ui.min.js'); ?>"></script>
<script src="<?= base_url('assets/js/custom.js'); ?>"></script>
<script src="<?= base_url('assets/bundles/datatables/datatables.min.js'); ?>"></script>
<script>
    const base_url = '<?= base_url(); ?>';
</script>

<?= $this->renderSection('js'); ?>