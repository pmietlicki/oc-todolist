<?php namespace Pmietlicki\Todo;

use System\Classes\PluginBase;
use RainLab\User\Models\User;

/**
 * Todo Plugin Information File
 */
class Plugin extends PluginBase
{

    public $require = ['RainLab.User'];

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'Simple Todo List',
            'description' => 'A simple database driven todo list',
            'author'      => 'Pmietlicki',
            'icon'        => 'icon-leaf'
        ];
    }

    public function registerComponents()
    {
        return [
            'Pmietlicki\Todo\Components\Todo' => 'todoList'
        ];
    }

}
