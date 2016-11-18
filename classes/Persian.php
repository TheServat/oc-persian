<?php namespace RtlWeb\Persian\Classes;

use Config;
use Event;
use Cms\Classes\Page as CmsPage;
use Backend\Classes\WidgetManager;
use Illuminate\Foundation\AliasLoader;
use \Backend\Classes\Controller as BackendController;
use October\Rain\Database\Model;
use October\Rain\Support\Traits\Singleton;
use Request;
use System\Classes\PluginManager;
class Persian
{
    use Singleton;

    public function init()
    {

    }
    public function boot(){
        $this->registerBackendWidgets();
    }
    public function register()
    {
        \Model::extend(function($model){
            $model->bindEvent('model.beforeSetAttribute', function($key, $value) use ($model) {
                Argon::$jalali = false;
            });
            $model->bindEvent('model.setAttribute', function($key, $value) use ($model) {
                Argon::$jalali = true;
            });
            
        });
        $this->fixValidations();
        $this->fixJs();
        $this->addAssets();
        $this->replaceClasses();
    }

    public function registerBackendWidgets()
    {
        // WidgetManager::instance()->registerFormWidgets(function ($manager) {
        //     $manager->registerFormWidget('RtlWeb\Persian\FormWidgets\DatePicker', [
        //         'label' => 'Date picker',
        //         'code' => 'datepicker'
        //     ]);
        // });
    }

    public function fixValidations()
    {
        CmsPage::extend(function ($page) {
            $page->rules['url'] = ['required', 'regex:/^\/[۰-۹آا-یa-z0-9\/\:_\-\*\[\]\+\?\|\.\^\\\$]*$/iu'];
        });


        //edit blog url validation rule
        if (PluginManager::instance()->exists('rainlab.blog')) {
            \RainLab\Blog\Models\Post::extend(function ($post) {
                $post->rules['slug'] = ['required', 'regex:/^[۰-۹آا-یa-z0-9\/\:_\-\*\[\]\+\?\|]*$/iu', 'unique:rainlab_blog_posts'];
            });
        }
        //extending rainlab.pages
        if (PluginManager::instance()->exists('rainlab.pages')) {
            //edit rainlab page url validation rule
            \RainLab\Pages\Classes\Page::extend(function ($page) {
                $page->rules['url'] = ['required', 'regex:/^\/[۰-۹آا-یa-z0-9\/_\-]*$/iu', 'uniqueUrl'];
            });

            //edit rainlab page filename in crating
            \RainLab\Pages\Classes\Page::creating(function ($page) {
                $page->fileName = \Str::ascii($page->fileName);
            }, -1);
        }
    }

    public function fixJs()
    {
        BackendController::extend(function ($controller) {
//            dd(LanguageDetector::isRtl());
            if (!Request::ajax()) {
                $controller->addJs(Config::get('cms.pluginsPath') . '/rtlweb/persian/assets/js/fix.inputpreset.js');
            }
        });
    }

    public function addAssets()
    {
        Event::listen('backend.page.beforeDisplay', function ($controller, $action, $params) {
            if (!Request::ajax()) {
                $controller->addJs(Config::get('cms.pluginsPath') . ('/rtlweb/persian/assets/js/persian-min.js'));
                $controller->addCss(Config::get('cms.pluginsPath') . '/rtlweb/persian/assets/css/persian.css');
            }
        });
    }

    public function replaceClasses()
    {
        AliasLoader::getInstance()->alias('System\Helpers\DateTime', 'RTlWeb\Persian\Classes\DateTime');
        AliasLoader::getInstance()->alias('October\Rain\Database\Traits\Sluggable', 'RtlWeb\Persian\Database\Traits\Sluggable');
        AliasLoader::getInstance()->alias('October\Rain\Argon\Argon', 'RtlWeb\Persian\Classes\Argon');
    }
}
