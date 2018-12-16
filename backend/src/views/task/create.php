<!DOCTYPE html>
<html class="no-js">
<?php
$dirPath = realpath(dirname(dirname(__FILE__)));
include_once "{$dirPath}/layout/head.php";
?>
<body>
<?php
include_once "{$dirPath}/layout/navbar.php";
?>
<div class="container">

    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6 pb-5">
           <?php
            if($model->hasErrors()) {
                $errors = $model->getErrors();
                include_once "{$dirPath}/layout/errors.php";
            }
           ?>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6 pb-5">
            <form class="form-group" action="/task/create" method="post">
                <div class="card">
                    <div class="card-header p-4">
                        Add Todo
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-control-label" for="title">Task</label>
                            <input type="text" name="title" placeholder="Type title" class="form-control is-valid" id="title" value="<?=$model->title?>">
                        </div>
                        <div class="form-group">
                            <label class="form-control-label" for="description">Status</label>
                            <input type="status" name="status" placeholder="Type status" class="form-control is-valid" id="status" value="<?=$model->status?>">
                        </div>
                        <br />
                        <button type="submit" class="btn btn-outline-secondary">Add</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>

<script src="/assets/js/lib/jquery-min.js"></script>
<script src="/assets/js/lib/bootstrap.js"></script>

</body>
</html>
