<?php require APPROOT . '/views/inc/header.php';?>
<div class="p-5 mb-4 bg-light rounded-3">
    <div class="container-fluid py-5">
        <h1 class="display-5 fw-bold"><?php echo $data['title'];?></h1>
        <p class="col-md-8 fs-4">Website to record train journeys</p>
        <p>Version: <?php echo APPVERSION; ?></p>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php';?>