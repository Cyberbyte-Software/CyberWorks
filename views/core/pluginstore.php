<?php
if (file_exists("plugins")) {
    $plugins = json_decode(file_get_contents('http://cyberbyte.org.uk/hooks/cyberworks/plugins.php?id=' . $settings['id']), true);

    function downloadFile($url) {
        $newfname = realpath('downloading') . '/plugin.zip';
        $folder = realpath('plugins');
        $file = fopen($url, "rb");
        if ($file) {
            $newf = fopen($newfname, "wb");
            if ($newf) {
                while (!feof($file)) {
                    fwrite($newf, fread($file, 1024 * 8), 1024 * 8);
            }
                }
        }

        if ($file) {
            fclose($file);
        }
        if ($newf) {
            fclose($newf);
        }

        $zip = new ZipArchive;
        $zip->open($newfname);
        $zip->extractTo($folder);
        $zip->close();
        rrmdir(realpath('downloading'));
    }

    /**
     * @param string $dir
     */
    function rrmdir($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir . "/" . $object) == "dir") {
                        rrmdir($dir . "/" . $object);
                    } else {
                        unlink($dir . "/" . $object);
                    }
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }

    if (isset($_GET['install'])) {
        $install = $_GET['install'];
        $key = array_search($install, array_column($plugins, 'dirname'));
        downloadFile($plugins[$key + 1]['url']);
    }

    if (isset($_GET['activate'])) {
        if (!in_array($_GET['activate'], $settings['plugins'])) {
            array_push($settings['plugins'], $_GET['activate']);
            $json = json_decode(file_get_contents('plugins/' . $_GET['activate'] . '/plugin.json'), true);
            if (isset($json['language']) && isset($json['short'])) {
                $lang = array($json['language'], $json['short']);
                array_push($settings['installedLanguage'], $lang);
            }
            if (isset($json['defaultSettings'])) {
                foreach ($json['defaultSettings'] as $key => $setting) {
                    $settings[$key] = $setting;

                }
            }
            file_put_contents('config/settings.php', '<?php return ' . var_export($settings, true) . ';');
            message($lang['pluginActivated']);
        } message($lang['activeAlready']);
    }

    if (isset($_GET['deactivate'])) {
        if (in_array($_GET['deactivate'], $settings['plugins'])) {
            if (array_count_values($settings['plugins']) <= 1) {
                $settings['plugins'] = array();
            } else {
                $settings['plugins'] = array_diff($settings['plugins'], array($_GET['deactivate']));
            }
            
            $json = json_decode(file_get_contents('plugins/' . $_GET['deactivate'] . '/plugin.json'), true);
            if (isset($json['language']) && isset($json['short'])) {
                $key = array_search($_GET['deactivate'], array_column($settings['installedLanguage'], 0));
                unset($settings['installedLanguage'][$key]);
            }
            file_put_contents('config/settings.php', '<?php return ' . var_export($settings, true) . ';');
        } message($lang['notActive']);
    }

    if (isset($_GET['delete'])) {
        rrmdir(realpath('plugins') . '/' . $_GET['delete']);
    }
    ?>

        <div class="row">
            <div class="col-lg-8">
                <h1 class="page-header">
                    <?php echo $lang['pluginstore']; ?>
                </h1>
            </div>
        </div>
    <?php
    if (!isset($plugins['verify'])) {
        echo "<form method='post' action='plugins' name='plugins'>";

        $files = scandir("plugins");
        unset($files[0]);
        unset($files[1]);

        foreach ($files as &$file) {
            $path = 'plugins/' . $file . '/plugin.json';
            $installed[] = json_decode(file_get_contents($path), true);
        }

        $dropIn = array_diff($files, array_column($plugins, 'dirname'));
        if ($dropIn > 0) echo "<h2 style='margin-top: 0;'>" . $lang['dropIn'] . "</h2>";
        
        foreach ($dropIn as &$plugin) {
            $key = array_search($plugin, array_column($installed, 'dirname'));
            echo '<div class="col-sm-6 col-md-4"><div class="thumbnail"><div class="caption">';
            echo '<h3>' . $installed[$key]['name'] . ' - ' . $installed[$key]['version'];

            if (in_array($plugin, $settings['plugins']))
            echo '<small style="padding-left: 6px;"><span class="label label-success">' . $lang['active'] . '</span></small>';
            else echo '<small style="padding-left: 6px;"><span class="label label-default">' . $lang['installed'] . '</span></small>';

            echo '</h3><p><strong>' . $installed[$key]['author'] . '</strong> - ' . $installed[$key]['description'] . '</p>';

            if (in_array($plugin, $settings['plugins']))
            echo '<p><a href="?deactivate=' . $plugin . '" class="btn btn-primary" role="button">' . $lang['deactivate'] . '</a>';
            else echo '<p><a href="?activate=' . $plugin . '" class="btn btn-primary" role="button">' . $lang['activate'] . '</a>';

            if (isset($installed[$key]['authurl'])) echo '<a style="margin-left: 5px;" href="' . $installed[$key]['authurl'] . '" class="btn btn-default" role="button">' . $lang['visitSite'] . '</a>';
            echo '<a style="float: right;" href="?delete=' . $plugin . '" class="btn btn-danger" role="button">' . $lang['delete'] . '</a>';
            echo '</p></div></div></div>';
        }
        echo "<div class='row'></div><h2>" . $lang['pluginstore'] . "</h2>";

        foreach ($plugins as &$plugin) {
            echo '<div class="col-sm-6 col-md-4"><div class="thumbnail">';
            echo '<img  src="' . $plugin['image'] . '" alt="' . $plugin['name'] . '">';
            echo '<div class="caption">';
            echo '<h3>' . $plugin['name'] . ' - ' . $plugin['version'];

            $key = array_search($plugin['dirname'], array_column($installed, 'dirname'));

            if ($key !== false) {
                if (in_array($plugin['dirname'], $settings['plugins'])) {
                    if ($plugin['version'] > $installed[$key]['version']) {
                        echo '<small style="padding-left: 6px;"><span class="label label-info">' . $lang['updateAvalible'] . '</span></small>';
                    }
                    // Update avalible
                    else {
                        echo '<small style="padding-left: 6px;"><span class="label label-success">' . $lang['active'] . '</span>';
                    }
                    // Active plugin
                } elseif (in_array($plugin['dirname'], $files)) {
                    echo '<small style="padding-left: 6px;"><span class="label label-default">' . $lang['installed'] . '</span></small>';
                }
                // Installed not active
            }
            echo '</h3><p><strong>' . $plugin['author'] . '</strong> - ' . $plugin['description'] . '</p>';

            if ($key !== false) {
                if (in_array($plugin['dirname'], $settings['plugins'])) {
                    if ($plugin['version'] > $installed[$key]['version']) {
                        echo '<div><a href="?install=' . $plugin['dirname'] . '" class="btn btn-primary" role="button">' . $lang['update'] . '</a>';
                    }
                    // Update avalible
                    else {
                        echo '<p><a href="?deactivate=' . $plugin['dirname'] . '" class="btn btn-primary" role="button">' . $lang['deactivate'] . '</a>';
                    }
                    // Active plugin
                } elseif (in_array($plugin['dirname'], $files)) {
                    echo '<p><a href="?activate=' . $plugin['dirname'] . '" class="btn btn-primary" role="button">' . $lang['activate'] . '</a>';
                }
                // Installed not active
            } else {
                echo '<p><a href="?install=' . $plugin['dirname'] . '" class="btn btn-primary" role="button">' . $lang['install'] . '</a>';
            }
            // Not installed

            if (isset($plugin['authurl'])) {
                echo '<a style="margin-left: 5px;" href="' . $plugin['authurl'] . '" class="btn btn-default" role="button">' . $lang['visitSite'] . '</a>';
            }
            if (in_array($plugin['dirname'], $files)) {
                echo '<a style="float: right;" href="?delete=' . $plugin['dirname'] . '" class="btn btn-danger" role="button">' . $lang['delete'] . '</a></div>';
            }
            echo '</div></div></div>';
        }

        echo '</div></div></form>';
    } else {
        echo '<h2>' . $lang['notverified'] . '</h2>';
    }
    } else {
    echo '<h2>' . $lang['noPlugin'] . '</h2>';
}
