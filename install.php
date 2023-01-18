<?php

$ds = DIRECTORY_SEPARATOR;

if (
    !is_dir($_SERVER['DOCUMENT_ROOT'] . $ds . 'www')
    && !file_exists($_SERVER['DOCUMENT_ROOT'] . $ds . 'config' . $ds . 've.config')
) {
    if (!function_exists('fullCopy')) {
        function fullCopy($source, $target)
        {
            if (is_dir($source)) {

                @mkdir($target);

                $dir = dir($source);

                while (($entry = $dir->read()) !== false) {
                    if ($entry === '.' || $entry === '..') {
                        continue;
                    }

                    fullCopy(
                        $source . DIRECTORY_SEPARATOR . $entry,
                        $target . DIRECTORY_SEPARATOR . $entry
                    );
                }

                $dir->close();
            } else {
                copy($source, $target);
            }
        }
    }

    fullCopy(
        'src' . $ds . 'Packages' . $ds . 'Updater' . $ds . 'BaseStructure' . $ds,
        $_SERVER['DOCUMENT_ROOT'] . $ds
    );
}
