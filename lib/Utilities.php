<?php

    class Utilities {
        public static function reloadPage($withQueryString = true) {
            // Writes HTTP headers, must be called prior to any output being written
            $location = $withQueryString ? $_SERVER['REQUEST_URI'] : strtok($_SERVER["REQUEST_URI"],'?');
            header('location: ' . $location);
            die();
        }
    }
?>