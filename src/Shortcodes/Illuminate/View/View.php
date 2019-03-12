<?php

namespace Gornymedia\Shortcodes\Illuminate\View;

use Illuminate\View\View as IlluminateView;
use Illuminate\Contracts\View\Engine as EngineInterface;
use Gornymedia\Shortcodes\Shortcode;

class View extends IlluminateView {

    /**
     * @var Shortcode
     */
    public $shortcode;

    /**
     * View constructor.
     *
     * @param Factory $factory
     * @param EngineInterface $engine
     * @param string $view
     * @param string $path
     * @param array $data
     * @param Shortcode $shortcode
     */
    public function __construct(Factory $factory, EngineInterface $engine, $view, $path, $data = [], Shortcode $shortcode)
    {
        parent::__construct($factory, $engine, $view, $path, $data);

        $this->shortcode = $shortcode;
    }

    /**
     * Compile the shortcodes.
     *
     * @return $this
     */
    public function compileShortcodes()
    {
        $this->shortcode->mode = Shortcode::modeCompile;

        return $this;
    }

    /**
     * Strip the shortcodes.
     *
     * @return $this
     */
    public function stripShortcodes()
    {
        $this->shortcode->mode = Shortcode::modeStrip;

        return $this;
    }

    /**
     * Get the contents of the view instance.
     *
     * @return string|void
     */
    protected function renderContents()
    {
        $this->factory->incrementRender();
        $this->factory->callComposer($this);

        $contents       = $this->getContents();

        if ($this->shortcode->mode === Shortcode::modeCompile) {
            $contents   = $this->shortcode->compile($contents);
        } elseif ($this->shortcode->mode === Shortcode::modeStrip) {
            $contents   = $this->shortcode->strip($contents);
        }

        $this->factory->decrementRender();

        return $contents;
    }

}
