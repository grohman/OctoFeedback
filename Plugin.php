<?php namespace IDesigning\Feedback;

use App;
use Event;
use IDesigning\Feedback\Models\Feedback as FeedbackModel;
use System\Classes\PluginBase;

/**
 * feedback Plugin Information File
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
            'name' => 'Обратная связь',
            'description' => 'Управление формами обратной связи',
            'author' => 'Daniel Podrabinek',
            'icon' => 'icon-comments-o'
        ];
    }

    public function boot()
    {
        $isBackend = $this->app->runningInBackend();
        FeedbackModel::extend(function (FeedbackModel $model) use ($isBackend) {
            if (class_exists('\IDesigning\Tattler\Lib\Inject')) {
                if ($model->isClassExtendedWith('\IDesigning\Tattler\Lib\Inject') == false && $isBackend == false) {
                    $model->extendClassWith('\IDesigning\Tattler\Lib\Inject');
                }
            }

            $model->rules = config()->get('idesigning.feedback::validation');
        });

        Event::listen('backend.form.extendFields', function ($widget) {
            if (!$widget->getController() instanceof \IDesigning\Feedback\Controllers\Feedbacks) {
                return;
            }
            $oldFields = $widget->getFields();
            foreach ($oldFields as $key => $value) {
                $widget->removeField($key);
            }
            $widget->addFields(config()->get('idesigning.feedback::fields'));
        });

        Event::listen('backend.list.extendColumns', function ($list) {
            if (!$list->model instanceof \IDesigning\Feedback\Models\Feedback) {
                return;
            }
            $oldColumns = $list->getColumns();
            foreach ($oldColumns as $key => $value) {
                $list->removeColumn($key);
            }
            $list->addColumns(config()->get('idesigning.feedback::columns'));
        });
    }

    /**
     * Register method, called when the plugin is first registered.
     */
    public function register()
    {
        \Validator::extend("emails", function ($attribute, $value, $parameters) {
            $rules = [
                'email' => 'required|email',
            ];

            $emails = [ ];
            if (!is_array($value)) {
                $emails = explode(',', $value);
            } else {
                $emails = [ $value ];
            }

            foreach ($emails as $email) {
                $data = [
                    'email' => trim($email)
                ];
                $validator = \Validator::make($data, $rules);
                if ($validator->fails()) {
                    return false;
                }
            }

            return true;
        });
    }

    public function registerComponents()
    {
        return [
            '\IDesigning\Feedback\Components\Feedback' => 'feedback'
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     */
    public function registerPermissions()
    {
        return [
            'idesigning.feedback.manage' => [
                'label' => 'idesigning.feedback::lang.permissions.feedback.manage',
                'tab' => 'cms::lang.permissions.name'
            ],
            'idesigning.feedback.settings.channel' => [
                'label' => 'idesigning.feedback::lang.permissions.settings.channel',
                'tab' => 'system::lang.permissions.name'
            ]
        ];
    }

    /**
     * Registers any mail templates implemented by this plugin.
     * The templates must be returned in the following format:
     * ['acme.blog::mail.welcome' => 'This is a description of the welcome template'],
     * ['acme.blog::mail.forgot_password' => 'This is a description of the forgot password template'],
     */
    public function registerMailTemplates()
    {
        return [
            //'idesigning.feedback::base-email' => Lang::get('idesigning.feedback::lang.mail_template.description'),
            //'idesigning.feedback::back-email' => Lang::get('idesigning.feedback::lang.backmail_template.description')
        ];
    }

    public function registerNavigation()
    {
        return [
            'feedback' => [
                'label' => 'Обратная связь',
                'url' => \Backend::url('idesigning/feedback/feedbacks'),
                'icon' => 'icon-comments-o',
                'permissions' => [ 'idesigning.feedback.manage' ],

                'sideMenu' => [
                    'feedbacks' => [
                        'label' => 'Записи',
                        'icon' => 'icon-inbox',
                        'url' => \Backend::url('idesigning/feedback/feedbacks'),
                        'permissions' => [ 'idesigning.feedback.manage' ],
                    ],
                    'archived' => [
                        'label' => 'Архив',
                        'icon' => 'icon-archive',
                        'url' => \Backend::url('idesigning/feedback/feedbacks/archived'),
                        'permissions' => [ 'idesigning.feedback.manage' ]
                    ],
                ]

            ]
        ];
    }

    public function registerSettings()
    {
        return [
            'channels' => [
                'label' => 'Каналы',
                'description' => 'Управление каналами',
                'category' => 'Обратная связь',
                'icon' => 'icon-arrows',
                'url' => \Backend::url('idesigning/feedback/channels'),
                'order' => 500,
                'keywords' => 'feedback channel',
                'permissions' => [ 'idesigning.feedback.settings.channel' ]
            ]
        ];
    }


}
