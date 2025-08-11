<?php require_once 'assets/php/header.php'; ?>
<style>
#fee_type {
    padding-left: 20px;
    color: #6777ef !important;
    background-color: #f0f3ff;
}
</style>
<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h2>All Student Fee Structure</h2>
        </div>
        <div id="feeTable">Loading...</div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    $.get("ajax/fetch_student_fee_structure.php", function(data) {
        $("#feeTable").html(data);
    });
});
</script>
<?php require_once 'assets/php/footer.php'; ?>