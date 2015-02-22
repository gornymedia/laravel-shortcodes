<?php

namespace Gornymedia\Shortcodes\Illuminate\View;

use Illuminate\Events\Dispatcher,
    Illuminate\View\ViewFinderInterface,
    Illuminate\View\Engines\EngineResolver,
    Illuminate\View\Factory as IlluminateViewFactory;
use Gornymedia\Shortcodes\Shortcode;

class Factory extends IlluminateViewFactory {

    /**
     * @var \Gornymedia\Shortcodes\Shortcode
     */
    public $shortcode;

    /**
     * Create a new view factory instance.
     *
     * @param  \Illuminate\View\Compilers\EngineResolver  $engines
     * @param  \Illuminate\View\ViewFinderInterface  $finder
     * @param  \Illuminate\Events\Dispatcher  $events
     * @return void
     */
    public function __construct(EngineResolver $engines, ViewFinderInterface $finder, Dispatcher $events, Shortcode $shortcode) {
        parent::__construct($engines, $finder, $events);
        $this->shortcode = $shortcode;
    }

    /**
     * Get the evaluated view contents for the given view.
     *
     * @param  string  $view
     * @param  array   $data
     * @param  array   $mergeData
     * @return \Illuminate\View\View
     */
    public function make($view, $data = array(), $mergeData = array()) {
        if (isset($this->aliases[$view]))
            $view = $this->aliases[$view];

        $view = $this->normalizeName($view);
        $path = $this->finder->find($view);
        $data = array_merge($mergeData, $this->parseData($data));
        $this->callCreator($view = new View($this, $this->getEngineFromPath($path), $view, $path, $data, $this->shortcode));
        return $view;
    }

}
