<?php namespace RtlWeb\Persian;

use Event;
use RainLab\Blog\Models\Post;
use RainLab\Pages\Classes\Page as StaticPage;
use Cms\Classes\Page as CmsPage;
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
        $this->registerBackendWidgets();
        CmsPage::extend(function ($page) {
            $page->rules['url'] = ['required', 'regex:/^\/[۰-۹آا-یa-z0-9\/\:_\-\*\[\]\+\?\|\.\^\\\$]*$/iu'];
        });


        //edit blog url validation rule
        if (PluginManager::instance()->exists('rainlab.blog')) {
            Post::extend(function ($post) {
                $post->rules['slug'] = ['required', 'regex:/^[۰-۹آا-یa-z0-9\/\:_\-\*\[\]\+\?\|]*$/iu', 'unique:rainlab_blog_posts'];
            });
            Post::validating(function ($a) {
            });
        }
        //extending rainlab.pages
        if (PluginManager::instance()->exists('rainlab.pages')) {
            //edit rainlab page url validation rule
            StaticPage::extend(function ($page) {
                $page->rules['url'] = ['required', 'regex:/^\/[۰-۹آا-یa-z0-9\/_\-]*$/iu', 'uniqueUrl'];
            });

            //edit rainlab page filename in crating
            StaticPage::creating(function ($page) {
                $page->fileName = \Str::ascii($page->fileName);
            }, -1);
        }
        BackendController::extend(
            function ($controller) {
                $controller->addJs('/plugins/rtlweb/persian/assets/js/fix.inputpreset.js');
            });
        Event::listen('backend.page.beforeDisplay', function($controller, $action, $params) {
            $controller->addJs(\Config::get('cms.pluginsPath').('/rtlweb/persian/assets/js/moment-jalaali.js'));
            $controller->addJs(\Config::get('cms.pluginsPath').('/rtlweb/persian/assets/js/october.datetime.jalali.js'));
        });
    }

    public function register()
    {
//        AliasLoader::getInstance()->alias('Model', '\RtlWeb\Persian\Database\Model');
        AliasLoader::getInstance()->alias('October\Rain\Database\Traits\Sluggable', 'RtlWeb\Persian\Database\Traits\Sluggable');
        AliasLoader::getInstance()->alias('October\Rain\Argon\Argon', 'RtlWeb\Persian\Classes\Argon');

        Event::listen('backend.page.beforeDisplay', function ($controller, $action, $params) {
            $controller->addCss('/plugins/rtlweb/persian/assets/css/style.css');
        });
        Event::listen('cms.beforeRoute', function () {
            AliasLoader::getInstance()->alias('Cms\Classes\Page', 'RtlWeb\Persian\Classes\CmsPage');
            AliasLoader::getInstance()->alias('Page', 'RtlWeb\Persian\Classes\Page');
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
    }
}
