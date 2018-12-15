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
            <form class="form-group" action="/user/signup" method="post">
                <div class="card">
                    <div class="card-header p-4">
                        Sign up
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-control-label" for="title">Username</label>
                            <input type="text" name="username" placeholder="Type username" class="form-control is-valid" id="username" value="<?=$model->username?>">
                        </div>
                        <div class="form-group">
                            <label class="form-control-label" for="description">Password</label>
                            <input type="password" name="password" placeholder="Type password" class="form-control is-valid" id="password" value="<?=$model->password?>">
                        </div>
                        <div class="form-group">
                            <label class="form-control-label" for="description">Verify Password</label>
                            <input type="password" name="verifyPassword" placeholder="Verify password" class="form-control is-valid" id="verifyPassword" value="<?=$model->verifyPassword?>">
                        </div>
                        <br />
                        <button type="submit" class="btn btn-outline-secondary">Sign up</button>
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
