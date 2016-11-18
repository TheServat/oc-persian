<?php namespace RtlWeb\Persian;

use RtlWeb\Persian\Classes\Persian;
use System\Classes\PluginBase;


/**
 * Persian Plugin Information File
 */
class Plugin extends PluginBase
{

    /**
     * @var array Plugin dependencies
     */
    public $require = [];

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name' => 'Persian',
            'description' => 'This plugin add Persian support to OctoberCMS',
            'author' => 'RtlWeb',
            'icon' => 'icon-leaf'
        ];
    }

    public function boot()
    {
        Persian::instance()->boot();
    }

    public function register()
    {
        Persian::instance()->register();
    }


}
