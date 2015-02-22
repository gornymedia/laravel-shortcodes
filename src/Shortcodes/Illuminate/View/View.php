<?php

namespace Gornymedia\ShortCodes\Illuminate\View;

use Illuminate\View\View as IlluminateView,
    Illuminate\View\Engines\EngineInterface;
use Gornymedia\Shortcodes\Shortcode;

class View extends IlluminateView {

    /**
     *  @var \Gornymedia\Shortcodes\Shortcode
     */
    public $shortcode;

    /**
     * Create a new view instance.
     *
     * @param  \Illuminate\View\Factory  $factory
     * @param  \Illuminate\View\Compilers\EngineInterface  $engine
     * @param  string  $view
     * @param  string  $path
     * @param  array   $data
     * @return void
     */
    public function __construct(Factory $factory, EngineInterface $engine, $view, $path, $data = array(), Shortcode $shortcode) {
        parent::__construct($factory, $engine, $view, $path, $data);
        $this->shortcode = $shortcode;
    }

    /**
     * Compile the shortcodes
     */
    public function compileShortcodes() {
        $this->shortcode->mode = Shortcode::modeCompile;
        return $this;
    }

    /**
     * Strip the shortcodes
     */
    public function stripShortcodes() {
        $this->shortcode->mode = Shortcode::modeStrip;
        return $this;
    }

    /**
     * Get the contents of the view instance.
     *
     * @return string
     */
    protected function renderContents() {
        $this->factory->incrementRender();
        $this->factory->callComposer($this);
        $contents = $this->getContents();

        if ($this->shortcode->mode === Shortcode::modeCompile)
            $contents = $this->shortcode->compile($contents);
        elseif ($this->shortcode->mode === Shortcode::modeStrip)
            $contents = $this->shortcode->strip($contents);

        $this->factory->decrementRender();
        return $contents;
    }

}
