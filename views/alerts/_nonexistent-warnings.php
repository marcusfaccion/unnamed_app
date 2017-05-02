<table class="table table-hover table-responsive">

<?php foreach ($alerts as $alert): ?>
    
    <?php echo("{$alert->created_date} alert tipo {$alert->type->description} "); ?>
<?php endforeach; ?>

</table>