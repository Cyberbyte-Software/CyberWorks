<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            <?php echo $lang['databases']; ?>
        </h1>
    </div>
</div>

<div class="row mt mb">
  <div class="col-md-12">
	  <section class="task-panel tasks-widget">
		  <div class="panel-body">
			  <div class="task-content">
				  <ul id="sortable" class="task-list ui-sortable">
<?php
    $sql = "SELECT `sid`,`dbid`,`type`,`name` FROM `servers`;";
    $result_of_query = $db_connection->query($sql);

    if ($result_of_query->num_rows >= 1) {
        while ($row = mysqli_fetch_assoc($result_of_query)) {
            if ($row['type'] == 'life'){
?>
						<li class="list-primary">
							<i class=" fa fa-ellipsis-v"></i>
							<div class="task-title">
								<span class="task-title-sp"><?php echo $row['name']; ?></span>
								<span class="badge bg-theme">Life</span>
								<div style="float:right; padding-right: 15px;">
                                    <a href="<?php echo $settings['url'] ?>editServer/<?php echo $row['dbid'] ?>" class="btn btn-success btn-sm fa fa-pencil" type="submit"></a>
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
                                    <a href="<?php echo $settings['url'] ?>editServer/<?php echo $row['dbid'] ?>" class="btn btn-success btn-sm fa fa-pencil" type="submit"></a>
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