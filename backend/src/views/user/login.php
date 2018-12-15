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
            <form class="form-vertical" id="login-form" action="/user/login" method="post">
                    <div class="card">
                        <div class="card-header p-4">Login</div>
                        <div class="card-body">
                            <fieldset>

                                <div class="form-group">
                                    <label>Username</label>
                                    <input class="form-control" placeholder="Username" name="username" id="username" type="text" value="<?=$model->username?>">
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <input class="form-control" placeholder="Password" name="password" id="password" type="password" value="<?=$model->password?>">
                                </div>
                                <div class="form-group">
                                    <br />
                                </div>
                                <div class="form-group">
                                        <input class="btn btn-success" type="submit" value="Login">
                                </div>
                            </fieldset>
                        </div>
                    </div>
            </form> <!-- </form> -->
        </div>
    </div>

</div>

<script src="/assets/js/lib/jquery-min.js"></script>
<script src="/assets/js/lib/bootstrap.js"></script>

</body>
</html>
