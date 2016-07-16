<?php
// Created by Jakub Szymsza, 2016
// Inspired by http://jecas.cz/slouceni-js-css
class Minifier {
    private $scripts = array(); private $styles = array();
    private $js; private $css;

    public function add($source, $type="") { //first division
        if ($source) {
            if (is_array($source)) {
                foreach ($source as $item) {
                    $this->divide($item, $type);
                }
            } elseif (is_string($source)) {
                $this->divide($source, $type);
            } else {
                throw new Exception('Source file must be either an array or a string!');
            }
        } else {
            throw new Exception('Source file can\'t be empty!');
        }
    }

    private function divide($source, $type) { //divides given sources into arrays
        if (substr($source, -3) == "css" || $type == "css") {
            $this->styles[] = $this->validate($source);
        } else {
            $this->scripts[] = $this->validate($source);
        }
    }

    private function validate($source) { //validates source - must be a path or an URL
        $source = trim($source);
        if (mb_ereg("[\\^?*:;{}]+", $source) && !filter_var($source, FILTER_VALIDATE_URL)) {
            throw new Exception('Source is neither URL nor file!');
        } else {
            return $source;
        }
    }

    private function minify() {
        $this->minifyCSS();
        $this->minifyJS();
    }

    private function minifyCSS() {
        $replace = array(
            "#/\*.*?\*/#s" => "",  // Strip C style comments.
            "#\s\s+#"      => " ", // Strip excess whitespace.
        );

        $css = preg_replace(array_keys($replace), $replace, $this->css);

        $replace = array(
            ": "  => ":",
            "; "  => ";",
            " {"  => "{",
            " }"  => "}",
            ", "  => ",",
            "{ "  => "{",
            ";}"  => "}", // Strip optional semicolons.
            ",\n" => ",", // Don't wrap multiple selectors.
            "\n}" => "}", // Don't wrap closing braces.
            "} "  => "}\n", // Put each rule on it's own line.
            "\n"  => "", // Strip \n
        );

        $search = array_keys($replace);
        $css = str_replace($search, $replace, $css);

        return $this->css = $css;
    }

    private function minifyJS() {   // JavaScript compressor by John Elliot <jj5@jj5.net>
        $replace = array(
            '#\'([^\n\']*?)/\*([^\n\']*)\'#' => "'\1/'+\'\'+'*\2'", // remove comments from ' strings
            '#\"([^\n\"]*?)/\*([^\n\"]*)\"#' => '"\1/"+\'\'+"*\2"', // remove comments from " strings
            '#/\*.*?\*/#s'            => "",      // strip C style comments
            '#[\r\n]+#'               => "\n",    // remove blank lines and \r's
            '#\n([ \t]*//.*?\n)*#s'   => "\n",    // strip line comments (whole line only)
            '#([^\\])//([^\'"\n]*)\n#s' => "\\1\n",
                                          // strip line comments
                                          // (that aren't possibly in strings or regex's)
            '#\n\s+#'                 => "\n",    // strip excess whitespace
            '#\s+\n#'                 => "\n",    // strip excess whitespace
            '#(//[^\n]*\n)#s'         => "\\1\n", // extra line feed after any comments left
                                          // (important given later replacements)
            '#/([\'"])\+\'\'\+([\'"])\*#' => "/*" // restore comments in strings
        );

        $script = preg_replace(array_keys($replace), $replace, $this->js);

        $replace = array(
            "&&\n" => "&&",
            "||\n" => "||",
            "(\n"  => "(",
            ")\n"  => ")",
             "[\n"  => "[",
            "]\n"  => "]",
            "+\n"  => "+",
            ",\n"  => ",",
            "?\n"  => "?",
            ":\n"  => ":",
            ";\n"  => ";",
            "{\n"  => "{",
        //  "}\n"  => "}", (because I forget to put semicolons after function assignments)
            "\n]"  => "]",
            "\n)"  => ")",
            "\n}"  => "}",
            "\n\n" => "\n"
        );

        $script = str_replace(array_keys($replace), $replace, $script);

        return $this->js = $script;
    }
    
    private function compile($minify) {   // save content from all files in one string
        foreach ($this->styles as $style) {
            $this->css .= file_get_contents($style);
        }

        foreach ($this->scripts as $script) {
            $this->js .= file_get_contents($script);
        }

        if ($minify)
            $this->minify();
    }

    public function render($minify=true, $version="") {  // final rendering
        $this->compile($minify);

        if ($version) {
            $version = "-".$version;
        }
        fwrite(fopen("cache/style$version.css", "w"), $this->css);
		fwrite(fopen("cache/script$version.js", "w"), $this->js);

        echo "<link href='cache/style$version.css' rel='stylesheet'><script type='text/javascript' src='cache/script$version.js'></script>";
    }

    public function clear() {  // delete all files inside cache folder
        $files = glob('cache/*'); // get all file names
        foreach($files as $file){
            if(is_file($file))
                unlink($file); // delete file
        }
    }
}
