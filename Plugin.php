<?php namespace RtlWeb\Persian;

use System\Classes\PluginBase;
use Backend\Classes\WidgetManager;
use Illuminate\Foundation\AliasLoader;
/**
 * Persian Plugin Information File
 */
class Plugin extends PluginBase
{

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'Persian',
            'description' => 'No description provided yet...',
            'author'      => 'RtlWeb',
            'icon'        => 'icon-leaf'
        ];
    }

    public function boot()
    {
        $this->registerBackendWidgets();
    }

    public function register()
    {
        AliasLoader::getInstance()->alias('Model','\RtlWeb\Persian\Database\Model');
        \Event::listen('backend.page.beforeDisplay', function($controller, $action, $params){
            $controller->addCss('/plugins/rtlweb/persian/assets/css/style.css');
        });
    }

    protected function registerBackendWidgets()
    {
        WidgetManager::instance()->registerFormWidgets(function ($manager) {
            $manager->registerFormWidget('RtlWeb\Persian\FormWidgets\DatePicker', [
                'label' => 'Date picker',
                'code'  => 'datepicker'
            ]);
        });
//        dd(WidgetManager::instance()->listFormWidgets());
    }
}
