<!DOCTYPE html>
<html class="no-js">
<?php
    $dirPath = realpath(dirname(dirname(__FILE__)));
    include_once "{$dirPath}/layout/head.php";
?>
<body>
<?php
    include_once "{$dirPath}/layout/navbar.php";
    $sort = 'created_at';
    if(isset($_GET['sort']) && $_GET['sort']==='created_at') {
        $sort = '-created_at';
    }
?>

<div class="container">

    <div class="row">
        <div class="col-md-12">
            <h2>Todo List</h2>
            <br/>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <br/>
            <table class="table table-hover" id="posts-index-table">
                <thead>
                <tr>
                    <th>Task ID</th>
                    <th>Title</th>
                    <th>Status</th>
                    <th><a href="/task/index?sort=<?=$sort?>">Created At</a></th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody class="post-index">
                <?php foreach ($model as $task) : ?>
                    <tr>
                        <td><?= $task['id'] ?></td>
                        <td><?= $task['title'] ?></td>
                        <td><?=\App\Models\Task::getStatusLabel($task['status'])?></td>
                        <td><?=$task['created_at']?></td>
                        <td>
                            <form action="/task/delete" method="post">
                                <input type="hidden" name="id" value="<?= $task['id'] ?>"/>
                                <input type="submit" value="Delete" class="btn btn-sm btn-danger">
                                <a href="/task/update?id=<?=$task['id']?>" class="btn btn-sm btn-info"> Update </a>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script src="/assets/js/lib/jquery-min.js"></script>
<script src="/assets/js/lib/bootstrap.js"></script>

</body>
</html>
