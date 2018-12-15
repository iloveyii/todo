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
            <h2>List of events</h2>
            <br />
            <h4 id="event"><?= isset($model[0]) ? $model[0]['categoryName'] : 'Poll finished'?></h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <br />
            <form action="/vote/create" method="post">
                <table class="table table-hover" id="posts-index-table">
                <thead>
                <tr>
                    <th>Event</th>
                    <th><div for="home">Home Wins</div></th>
                    <th><div for="home">Draw</div></th>
                    <th><div for="home">Away Wins</div></th>
                </tr>
                </thead>
                <tbody class="post-index">
                    <?php foreach ($model as $event) : ?>
                    <tr>
                        <td><?=$event['name']?></td>
                        <td>
                            <div class="radio icheck-success" for="home">
                                <input type="radio" name="radio_<?=$event['id']?>" id="home_<?=$event['id']?>" value="home" <?=isset($event['winner_id']) && $event['winner_id']==1 ? 'checked' : ''?>>
                                <label for="home_<?=$event['id']?>"></label>
                            </div>
                        </td>
                        <td>
                            <div class="radio icheck-success" for="draw">
                                <input type="radio" name="radio_<?=$event['id']?>" id="draw_<?=$event['id']?>" value="draw" <?=isset($event['winner_id']) && $event['winner_id']==2 ? 'checked' : ''?>>
                                <label for="draw_<?=$event['id']?>"></label>
                            </div>
                        </td>
                        <td>
                            <div class="radio icheck-success" for="home">
                                <input type="radio" name="radio_<?=$event['id']?>" id="away_<?=$event['id']?>" value="away" <?=isset($event['winner_id']) && $event['winner_id']==3 ? 'checked' : ''?>>
                                <label for="away_<?=$event['id']?>"></label>
                            </div>
                        </td>
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
