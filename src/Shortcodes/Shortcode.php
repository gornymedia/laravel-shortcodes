<?php

namespace Gornymedia\Shortcodes;

class Shortcode {

    const modeCompile   = 1;
    const modeStrip     = 2;

    /**
     * Shortcodes render mode.
     *
     * @var int
     */
    public $mode;

    /**
     * Container for storing shortcode tags.
     *
     * @var array
     */
    protected $tags     = [];

    /**
     * Add shortcode tag and their callaback.
     *
     * @param $tag
     * @param $callback
     * @return bool
     */
    public function add($tag, $callback)
    {
        if (!$this->exists($tag)) {
            $this->tags[$tag] = $callback;

            return true;
        }

        return false;
    }

    /**
     * Get count of all shortcode tags.
     *
     * @return int
     */
    public function count()
    {
        return count($this->tags);
    }

    /**
     * Whether a registered shortcode tag exists.
     *
     * @param $tag
     * @return bool
     */
    protected function exists($tag)
    {
        return array_key_exists($tag, $this->tags);
    }

    /**
     * Search content for shortcode tags.
     *
     * @param $content
     * @return mixed
     */
    public function compile($content)
    {
        if (!$this->count()) {
            return $content;
        }

        $pattern = $this->getRegex();

        return preg_replace_callback("/$pattern/s", [$this, 'render'], $content);
    }

    /**
     * Regular Expression callable for compile.
     *
     * @param $m
     * @return bool|string
     */
    protected function render($m)
    {
        if ($m[1] == '[' && $m[6] == ']') {
            return substr($m[0], 1, -1);
        }

        $tag    = $m[2];
        $attr   = $this->parseAtts(html_entity_decode($m[3], ENT_QUOTES));

        if (isset($m[5])) {
            return $m[1] . call_user_func($this->tags[$tag], $attr, $m[5], $tag) . $m[6];
        } else {
            return $m[1] . call_user_func($this->tags[$tag], $attr, null, $tag) . $m[6];
        }
    }

    /**
     * Retrieve the shortcode regular expression for searching.
     *
     * @author Wordpress
     * @return string
     */
    protected function getRegex()
    {
        $tagnames   = array_keys($this->tags);
        $tagregexp  = join('|', array_map('preg_quote', $tagnames));
        return
                '\\['                       // Opening bracket
                . '(\\[?)'                  // 1: Optional second opening bracket for escaping shortcodes: [[tag]]
                . "($tagregexp)"            // 2: Shortcode name
                . '(?![\\w-])'              // Not followed by word character or hyphen
                . '('                       // 3: Unroll the loop: Inside the opening shortcode tag
                . '[^\\]\\/]*'              // Not a closing bracket or forward slash
                . '(?:'
                . '\\/(?!\\])'              // A forward slash not followed by a closing bracket
                . '[^\\]\\/]*'              // Not a closing bracket or forward slash
                . ')*?'
                . ')'
                . '(?:'
                . '(\\/)'                   // 4: Self closing tag ...
                . '\\]'                     // ... and closing bracket
                . '|'
                . '\\]'                     // Closing bracket
                . '(?:'
                . '('                       // 5: Unroll the loop: Optionally, anything between the opening and closing shortcode tags
                . '[^\\[]*+'                // Not an opening bracket
                . '(?:'
                . '\\[(?!\\/\\2\\])'        // An opening bracket not followed by the closing shortcode tag
                . '[^\\[]*+'                // Not an opening bracket
                . ')*+'
                . ')'
                . '\\[\\/\\2\\]'            // Closing shortcode tag
                . ')?'
                . ')'
                . '(\\]?)';                 // 6: Optional second closing brocket for escaping shortcodes: [[tag]]
    }

    /**
     * Retrieve all attributes from the shortcodes tag.
     *
     * @author Wordpress
     * @param $text
     * @return array|string
     */
    protected function parseAtts($text)
    {
        $atts       = [];
        $pattern    = '/(\w+)\s*=\s*"([^"]*)"(?:\s|$)|(\w+)\s*=\s*\'([^\']*)\'(?:\s|$)|(\w+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|(\S+)(?:\s|$)/';
        $text       = preg_replace("/[\x{00a0}\x{200b}]+/u", " ", $text);

        if (preg_match_all($pattern, $text, $match, PREG_SET_ORDER)) {
            foreach ($match as $m) {
                if (!empty($m[1]))
                    $atts[strtolower($m[1])] = stripcslashes($m[2]);
                elseif (!empty($m[3]))
                    $atts[strtolower($m[3])] = stripcslashes($m[4]);
                elseif (!empty($m[5]))
                    $atts[strtolower($m[5])] = stripcslashes($m[6]);
                elseif (isset($m[7]) and strlen($m[7]))
                    $atts[] = stripcslashes($m[7]);
                elseif (isset($m[8]))
                    $atts[] = stripcslashes($m[8]);
            }
        } else {
            $atts = ltrim($text);
        }

        return $atts;
    }

    /**
     * Combine user attributes with known attributes and fill in defaults when needed.
     *
     * @param $pairs
     * @param $atts
     * @return array
     */
    public static function atts($pairs, $atts)
    {
        $atts   = (array) $atts;
        $out    = [];

        foreach ($pairs as $name => $default) {
            if (array_key_exists($name, $atts)) {
                $out[$name] = $atts[$name];
            } else {
                $out[$name] = $default;
            }
        }

        return $out;
    }

    /**
     * Remove all shortcode tags from the given content.
     *
     * @param $content
     * @return mixed
     */
    public function strip($content)
    {
        if (!$this->count()) {
            return $content;
        }

        $pattern = $this->getRegex();

        return preg_replace_callback("/$pattern/s", [$this, 'stripTag'], $content);
    }

    /**
     * Remove shortcode tag
     *
     * @param $m
     * @return bool|string
     */
    protected function stripTag($m)
    {
        if ($m[1] == '[' && $m[6] == ']') {
            return substr($m[0], 1, -1);
        }

        return $m[1] . $m[6];
    }

}
