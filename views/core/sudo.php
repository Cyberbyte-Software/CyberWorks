<div class="row">
    <div class="col-lg-8">
        <h1 class="page-header">
            <?php echo $lang['sudoMode'] ?>
        </h1>
    </div>
</div>
<?php echo $lang['sudo'] ?>
<form method="post" action="<?php echo $currentPage ?>" class="form-inline">
<label for="pass"><?php echo $lang['password'] ?><div class="form-group"></label><input style="margin-left: 5px;" class="form-control" id="passTest" type="password" name="passTest"></div>
<button type="submit" class="btn btn-default">Verify</button></form>