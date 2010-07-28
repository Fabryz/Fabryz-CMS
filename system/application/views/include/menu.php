<?php
        //showing main top menu if exists
        if (!(empty($menu))) {
            echo "<div id=\"menu\">\n";
                echo "<ul>\n";
                foreach ($menu as $elem) {
                    echo "<li>".$elem."</li>\n";
                }
                echo "</ul>\n";
            echo "</div>\n";
        }
        //showing sub menu if exists
        if (!(empty($submenu))) {
            echo "<div id=\"submenu\">\n";
                echo "<ul>\n";
                foreach ($submenu as $elem) {
                    echo "<li>".$elem."</li>\n";
                }
                echo "</ul>\n";
            echo "</div>\n";
        }