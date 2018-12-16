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

    <div class="row">
        <div class="col-md-12">
            <h2>Todo List</h2>
            <br />
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <br />
            <form action="/vote/create" method="post">
                <table class="table table-hover" id="posts-index-table">
                <thead>
                <tr>
                    <th>Task ID</th>
                    <th>User ID</th>
                    <th>Title</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody class="post-index">
                    <?php foreach ($model as $task) : ?>
                    <tr>
                        <td><?=$task['id']?></td>
                        <td><?=$task['user_id']?></td>
                        <td><?=$task['title']?></td>
                        <td><?=$task['status']?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
                <div class="form-group float-right">
                    <input type="submit" value="Poll" class="btn btn-lg btn-success">
                </div>
            </form>
        </div>
    </div>

</div>

<script src="/assets/js/lib/jquery-min.js"></script>
<script src="/assets/js/lib/bootstrap.js"></script>

</body>
</html>
