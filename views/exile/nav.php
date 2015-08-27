<?php if ($_SESSION['permissions']['view']['player']) { ?>
		<li>
			<a href="<?php echo $settings['url'] ?>players">
				<i class="fa fa-fw fa-child "></i>
				<span><?php echo $lang['players']; ?></span>
			</a>
		</li>
<?php } if ($_SESSION['permissions']['view']['vehicles']) { ?>
		<li>
			<a href="<?php echo $settings['url'] ?>vehicles">
				<i class="fa fa-fw fa-car"></i>
				<span><?php echo $lang['vehicles']; ?></span>
			</a>
		</li>
<?php } if (isset($_SESSION['steamsignon'])) {
        if ($_SESSION['steamsignon'] && $_SESSION['user_level'] == 1) {
            if ($settings['url'] == "/") {
                include("views/steam/exile/nav.php");
            } else {
                include(realpath($settings['url']) . "views/steam/exile/nav.php");
            }
        }
}

/*
if ($_SESSION['permissions']['view']['player']) { 	?>
		<li>
			<a href="<?php echo $settings['url'] ?>territory">
				<i class="fa fa-fw fa-map"></i>
				<span><?php echo $lang['territory']; ?></span>
			</a>
		</li>
<?php } if ($_SESSION['permissions']['view']['player']) { ?>
		<li>
			<a href="<?php echo $settings['url'] ?>clan">
				<i class="fa fa-fw fa-users"></i>
				<span><?php echo $lang['clan']; ?></span>
			</a>
		</li>
	<?php }
*/