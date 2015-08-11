<?php

/*
 * This is a file parser to extract localizable strings from moonmoon
 *
 * It will scan the whole moonmoon repository for php files, extract
 * localized strings and their localization notes and create .lang files.
 * Existing translations will be automatically updated.
 * A short report will be displayed afterwards.
 *
 * The easiest way to add a new locale is just to create an empty .lang file and then run the script
 *
 * The script scans for the files in the l10n/ folder to know which locales are supported
 */

// released versions of moonmoon should immediately return for security
// return;

$root = __DIR__ . '/../../';

require_once $root.'/vendors/autoload.php';

$GLOBALS['english'] = array();

/*
 * This is a file parser to extract localizable strings (in .php files)
 * $GLOBALS['english'] is populated with localizable strings and their associated localization notes
 *
 */


function extract_l10n_strings($file) {
    $lines    = file($file);
    $patterns = array('/_g\([\'"](.*?)[\'"]\)/', '/getString\([\'"](.*?)[\'"]\)/',);

    foreach ($lines as $line) {

        // Skip comments
        if($line[0] == '#' || $line[0] == '/') continue;

        // parsing logic
        foreach($patterns as $pattern) {
            if(preg_match_all($pattern, $line, $matches, PREG_PATTERN_ORDER)) {
                foreach($matches[1] as $val) {

                    // Do not extract php variables calls or empty strings
                    if($val[0] == '$' || $val == '') continue;

                    // Is there a localization comment ?
                    $l10n_note = explode("',", $val);

                    // Also test strings in double quotes
                    if(count($l10n_note) == 1) {
                        $l10n_note = explode('",', $val);
                    }

                    // Extract cleaned up strings
                    if(count($l10n_note) == 2) {
                        $l10n_str  = trim($l10n_note[0]);
                        $l10n_note = trim(substr(trim($l10n_note[1]),1)); # Remove quote at begining of string
                    } else {
                        $l10n_str  = trim($val);
                        $l10n_note = '';
                    }

                    if(!array_key_exists($l10n_str, $GLOBALS['english'])) {
                        $GLOBALS['english'][$l10n_str] = array($l10n_str, $l10n_note);
                    }
                }
            }
        }
    }
}

/*
 * This is a function echoing $GLOBALS['english'] in .lang format
 * Typical usage would be:
 *     <?php
 *     extract_l10n_strings('.');
 *     show_l10n_strings() ;
 */

function show_l10n_strings() {

    header('Content-Type:text/plain');

    foreach($GLOBALS['english'] as $val) {
        if($val[1]) {
            echo '# ' . $val[1] . "\n";
        }
        echo ";$val[0]\n";
        echo "$val[0]\n\n\n";
    }
}

/*
 * Recursively scan  files in a folder
 * returns an array of file paths
 */

function find_all_files($dir) {

    $result = array();
    $root = scandir($dir);

    $ignore = array('.', '..', '.git', '.svn', '.hg', 'cache', '.gitignore', 'lib');

    foreach($root as $value) {

        if(in_array($value, $ignore)) {
            continue;
        }

        if(is_file("$dir/$value")) {
            $split = explode('.', $value);
            if(end($split) == 'php'){
                $result[] = "$dir/$value";
            }
            continue;
        }

        foreach(find_all_files("$dir/$value") as $value) {
            $result[]=$value;
        }
    }

    return $result;
}

function update_lang_files($source, $dest) {

    $files = find_all_files($source);

    foreach($files as $file) {
        extract_l10n_strings($file);
    }


    $files = scandir($dest);
    $ignore = array('.', '..');


    // list locales
    $locales = array();
    foreach($files as $file) {

        if(in_array($file, $ignore)) {
            continue;
        }

        $split   = explode('.', $file);

        if($split[1] == 'lang') {
            $locales[] = $split[0];
        }
     }


     foreach($locales as $locale) {
        $status[$locale] = 0;
        $lang_file_path  = $dest . '/' . $locale;

        Simplel10n::load($lang_file_path);

        ob_start();
        foreach($GLOBALS['english'] as $key => $val) {
            $warning = '';
            $value   = @Simplel10n::extractString($key);

            if($value == $val[0]) {
                $status[$locale]++;
                $warning = ' ** String needs translation **';
            }

            if($val[1]) {
                echo '# Translation note: ' . $val[1] . $warning . "\n";
            } elseif($warning != '') {
                echo '# Translation note: ' . $warning . "\n";
            }

            echo ";$val[0]\n";
            echo $value . "\n\n\n";
        }

        $content = ob_get_contents();
        ob_end_clean();
        file_put_contents($lang_file_path. '.lang', $content);

        unset($GLOBALS['locale']);
     }


     // Display a short status report
     header('Content-Type:text/plain');
     echo "Number of English strings: " . count($GLOBALS['english']) . "\n";
     echo "Your installation has these languages installed: " . implode(', ', $locales) . "\n";
     foreach($locales as $val) {
        echo $val . " has " . $status[$val] . " untranslated strings.\n";
     }
}

update_lang_files($root, $root . 'app/l10n');
