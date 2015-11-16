<?php namespace Grohman\Feedback;

use Illuminate\Support\Facades\Lang;
use System\Classes\PluginBase;
use App;
use Grohman\Feedback\Models\Feedback as FeedbackModel;

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
            'name'        => 'Обратная связь',
            'description' => 'Управление формами обратной связи',
            'author'      => 'Daniel Podrabinek',
            'icon'        => 'icon-comments-o'
        ];
    }

    public function boot()
    {
        $isBackend = $this->app->runningInBackend();
        FeedbackModel::extend(function (FeedbackModel $model) use ($isBackend) {
            if (class_exists('\Grohman\Tattler\Lib\Inject')) {
                if ($model->isClassExtendedWith('\Grohman\Tattler\Lib\Inject') == false && $isBackend == false) {
                    $model->extendClassWith('\Grohman\Tattler\Lib\Inject');
                }
            }
        });
    }

    /**
     * Register method, called when the plugin is first registered.
     */
    public function register()
    {
        \Validator::extend("emails", function($attribute, $value, $parameters) {
            $rules = [
                'email' => 'required|email',
            ];

            $emails = [];
            if (!is_array($value)) {
                $emails = explode(',', $value);
            }
            else {
                $emails = [$value];
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
            '\Grohman\Feedback\Components\Feedback' => 'feedback'
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     */
    public function registerPermissions()
    {
        return [
            'grohman.feedback.manage' => ['label' => 'grohman.feedback::lang.permissions.feedback.manage', 'tab' => 'cms::lang.permissions.name'],
            'grohman.feedback.settings.channel' => ['label' => 'grohman.feedback::lang.permissions.settings.channel', 'tab' => 'system::lang.permissions.name']
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
            'grohman.feedback::base-email' => Lang::get('grohman.feedback::lang.mail_template.description')
        ];
    }

    public function registerNavigation()
    {
        return [
            'feedback' => [
                'label'       => 'Обратная связь',
                'url'         => \Backend::url('grohman/feedback/feedbacks'),
                'icon'        => 'icon-comments-o',
                'permissions' => ['grohman.feedback.manage'],

                'sideMenu' => [
                    'feedbacks' => [
                        'label'       => 'Фидбеки',
                        'icon'        => 'icon-inbox',
                        'url'         => \Backend::url('grohman/feedback/feedbacks'),
                        'permissions' => ['grohman.feedback.manage'],
                    ],
                    'archived' => [
                        'label'       => 'Архив',
                        'icon'        => 'icon-archive',
                        'url'         => \Backend::url('grohman/feedback/feedbacks/archived'),
                        'permissions' => ['grohman.feedback.manage']
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
                'url' => \Backend::url('grohman/feedback/channels'),
                'order' => 500,
                'keywords' => 'feedback channel',
                'permissions' => ['grohman.feedback.settings.channel']
            ]
        ];
    }


}
