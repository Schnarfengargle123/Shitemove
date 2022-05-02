<?php

class Renderer
{
    private static $injection = array();

    public static function inject($key, $value)
    {
        self::$injection[$key] = $value;
    }

    public static function render($contentFile, $variables = array())
    {
        $contentFileFullPath = "../views/" . $contentFile;

        if (count($variables) > 0) {
            foreach ($variables as $key => $value) {
                if (strlen($key) > 0) {
                    ${$key} = $value;
                }
            }
        }

        foreach (self::$injection as $key => $value) {
            if (strlen($key) > 0) {
                ${$key} = $value;
            }
        }

        require_once("../views/components/header.php");
        // require_once("../views/components/header.php");

        echo "\n<div class=\"container\">\n";

        if (file_exists($contentFileFullPath)) {
            require_once($contentFileFullPath);
        } else {
            require_once("../views/error.php");
        }

        echo "</div>\n";

        require_once("../views/components/footer.php");
    }
}
