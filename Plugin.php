<?php namespace RtlWeb\Persian;

use Event;
use RainLab\Blog\Models\Post;
use System\Classes\PluginBase;
use Backend\Classes\WidgetManager;
use Illuminate\Foundation\AliasLoader;
use \Backend\Classes\Controller as BackendController;
use System\Classes\PluginManager;

/**
 * Persian Plugin Information File
 */
class Plugin extends PluginBase
{

    /**
     * @var array Plugin dependencies
     */
    public $require = [
        'RtlWeb.Rtler'
    ];

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name' => 'Persian',
            'description' => 'No description provided yet...',
            'author' => 'RtlWeb',
            'icon' => 'icon-leaf'
        ];
    }

    public function boot()
    {
        $this->registerBackendWidgets();
        if(PluginManager::instance()->exists('rainlab.blog')){
            Post::extend(function($post){
                $post->rules['slug'] = ['required', 'regex:/^[۰-۹آا-یa-z0-9\/\:_\-\*\[\]\+\?\|]*$/i', 'unique:rainlab_blog_posts'];
            });
        }
        BackendController::extend(
            function ($controller) {
                $controller->addJs('/plugins/rtlweb/persian/assets/js/fix.inputpreset.js');
            });
    }

    public function register()
    {
        AliasLoader::getInstance()->alias('Model', '\RtlWeb\Persian\Database\Model');
        AliasLoader::getInstance()->alias('October\Rain\Database\Traits\Sluggable', 'RtlWeb\Persian\Database\Traits\Sluggable');

        Event::listen('backend.page.beforeDisplay', function ($controller, $action, $params) {
            $controller->addCss('/plugins/rtlweb/persian/assets/css/style.css');
        });
        Event::listen('cms.beforeRoute', function() {
            AliasLoader::getInstance()->alias('Cms\Classes\Page', 'RtlWeb\Persian\Classes\CmsPage');
            AliasLoader::getInstance()->alias('Page', 'RtlWeb\Persian\Classes\Page');
            AliasLoader::getInstance()->alias('RainLab\Pages\Classes\Page', 'RtlWeb\Persian\Classes\RainLabPage');
        });

    }

    protected function registerBackendWidgets()
    {
        WidgetManager::instance()->registerFormWidgets(function ($manager) {
            $manager->registerFormWidget('RtlWeb\Persian\FormWidgets\DatePicker', [
                'label' => 'Date picker',
                'code' => 'datepicker'
            ]);
        });
//        dd(WidgetManager::instance()->listFormWidgets());
    }
}
