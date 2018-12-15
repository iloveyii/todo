<div class="row">
    <div class="col-md-12">
        <div class="alert alert-danger" role="alert">
            <?php foreach ($errors as $field=>$messages): ?>
                <strong><?=ucfirst($field)?>:   <br /></strong>
                <?php foreach ($messages as $message): ?>
                    <p style="padding-left:5em"><?=ucfirst($message)?> </p>
                <?php endforeach;?>
            <?php endforeach;?>
        </div>
    </div>
</div>
