<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            <?php echo $lang['navDashboard']; ?>
        </h1>
    </div>
</div>
<?php if (isset($message)) {
    echo '<div class="alert alert-info" role="alert">' . $message . '</div>';
}
?>

<div class="row mt mb">
  <div class="col-md-12">
	  <section class="task-panel tasks-widget">
		<div class="panel-heading">
			<div class="pull-left"><h5><i class="fa fa-tasks"></i> Your Servers</h5></div>
			<br>
		</div>
		  <div class="panel-body">
			  <div class="task-content">
				  <ul id="sortable" class="task-list ui-sortable">
<?php
    $sql = "SELECT `sid`,`dbid`,`type`,`name` FROM `servers`;";
    $result_of_query = $db_connection->query($sql);

    if ($result_of_query->num_rows >= 1) {
        while ($row = mysqli_fetch_assoc($result_of_query)) {
            if ($row['type'] == 'life'){ ?>
						<li class="list-primary">
							<i class=" fa fa-ellipsis-v"></i>
							<div class="task-title">
								<span class="task-title-sp"><?php echo $row['name']; ?></span>
								<span class="badge bg-theme">Life</span>
								<div style="float:right; padding-right: 15px;">
									<form method="post" action="<?php echo $settings['url'] ?>dashboard">
										<input type="hidden" name="type" value="<?php echo $row['type']; ?>">
										<input type="hidden" name="dbid" value="<?php echo $row['dbid']; ?>">
										<button class="btn btn-success btn-sm fa fa-eye" type="submit"></button>
									</form>
								</div>
							</div>
						</li>
			<?php }elseif ($row['type'] == 'waste') { ?>
						<li class="list-danger">
							<i class=" fa fa-ellipsis-v"></i>
							<div class="task-title">
								<span class="task-title-sp"><?php echo $row['name']; ?></span>
								<span class="badge bg-important">Wasteland</span>
								<div class="pull-right hidden-phone">
									<form method="post" action="<?php echo $settings['url'] ?>dashboard">
										<input type="hidden" name="type" value="<?php echo $row['type']; ?>">
										<input type="hidden" name="dbid" value="<?php echo $row['dbid']; ?>">
										<button class="btn btn-success btn-sm fa fa-eye" type="submit"></button>
									</form>
							</div>
						</li>
<?php
            }
        }
        echo '</select>';
    }
?>
				  </ul>
			  </div>
		  </div>
	  </section>
  </div>
</div>