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
<?php } if ($_SESSION['permissions']['view']['houses']) { 	?>
		<li>
			<a href="<?php echo $settings['url'] ?>houses">
				<i class="fa fa-fw fa-home"></i>
				<span><?php echo $lang['houses']; ?></span>
			</a>
		</li>
<?php } if ($_SESSION['permissions']['view']['gangs']) { ?>
		<li>
			<a href="<?php echo $settings['url'] ?>gangs">
				<i class="fa fa-fw fa-sitemap"></i>
				<span><?php echo $lang['gangs']; ?></span>
			</a>
		</li>
	<?php } if (isset($_SESSION['steamsignon'])) {
        if ($_SESSION['steamsignon'] && $_SESSION['user_level'] == 1) {
            if ($settings['url'] == "/") {
                include("views/steam/life/nav.php");
            } else {
                include(realpath($settings['url']) . "views/steam/life/nav.php");
            }
		
        }
}