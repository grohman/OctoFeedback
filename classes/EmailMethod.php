<?php
/**
 * Created by PhpStorm.
 * User: Leonardo Shinagawa
 * Date: 28/06/15
 * Time: 10:22
 */

namespace Grohman\Feedback\Classes;


use Backend\Models\User;
use Backend\Widgets\Form;
use Grohman\Feedback\Controllers\Channels;
use Grohman\Feedback\Models\Channel;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;

class EmailMethod implements Method
{

    public function boot()
    {
        Channels::extendFormFields(function (Form $form, $model) {
            $form->addFields([
                    'method_data[email_destination]' => [
                        'label' => "grohman.feedback::lang.channel.emailDestination",
                        'commentAbove' => "grohman.feedback::lang.backend.settings.channel.emailDestinationComment",
                        'required' => true,
                        'trigger' => [
                            'action' => "show",
                            'field' => "method",
                            'condition' => "value[email]"
                        ]
                    ],
                    'method_data[subject]' => [
                        'label' => "Тема письма",
                        'required' => true,
                        'trigger' => [
                            'action' => "show",
                            'field' => "method",
                            'condition' => "value[email]"
                        ]
                    ],
                    'method_data[template]' => [
                        'type' => 'codeeditor',
                        'language' => 'twig',
                        'label' => "Шаблон",
                        'commentAbove' => 'Можно использовать переменные из формы - например {{ name }} для вставки имени отправителя. Так же доступны phone, email, message',
                        'required' => true,
                        'trigger' => [
                            'action' => "show",
                            'field' => "method",
                            'condition' => "value[email]"
                        ]
                    ],

                    'method_data[backmail]' => [
                        'label' => 'Автоответчик',
                        'type' => 'switch',
                        'trigger' => [
                            'action' => 'show',
                            'field'=>'method',
                            'condition' => 'value[email]',
                        ]
                    ],

                    'method_data[backmail_subject]' => [
                        'label' => 'Тема ответного письма',
                        'trigger' => [
                            'action' => 'enable',
                            'field' => 'method_data[backmail]',
                            'condition' => 'checked',
                        ],
                    ],

                    'method_data[backmail_template]' => [
                        'label' => 'Шаблон ответного письма',
                        'language' => 'twig',
                        'type' => 'codeeditor',
                        'trigger' => [
                            'action' => 'enable',
                            'field' => 'method_data[backmail]',
                            'condition' => 'checked',
                        ],
                    ],
                ]
            );
        });

        Channel::extend(function(Channel $model) {
            $model->rules['method_data.email_destination'] = "emails";
            $model->attributeNames['method_data.email_destination'] = 'grohman.feedback::lang.channel.emailDestination';
        });
    }

    public function send($methodData, $data)
    {
        $sendTo = $methodData['email_destination'];
        if ($sendTo == null) {
            // find the first admin user on the system
            $sendTo = $this->findAdminEmail();
        }

        $loader = new \Twig_Loader_Array(array(
            'subject' => $methodData['subject'],
            'main' => $methodData['template']
        ));
        $twig = new \Twig_Environment($loader);

        $subject = $twig->render('subject', $data);
        Mail::queue('grohman.feedback::base-email', ['content' => $twig->render('main', $data)], function (Message $message) use ($sendTo, $subject, $data) {
            $message->subject($subject);
            $message->to(array_map('trim', explode(',', $sendTo)));
            if(isset($data['file'])) {
                $message->attach($data['file'], ['as' => $data['filename']]);
            }

            $replyTo = isset($data['email']) ? $data['email'] : null;
            $replyToName = isset($data['name']) ? $data['name'] : 'Аноним';
            if ($replyTo) {
                $message->replyTo($replyTo, $replyToName);
            }
        });

        if($methodData['backmail'] == 1 && isset($data['email'])) {
            $sendTo = $data['email'];
            $backLoader = new \Twig_Loader_Array(array(
                'subject' => $methodData['backmail_subject'],
                'main' => $methodData['backmail_template']
            ));
            $backTwig = new \Twig_Environment($backLoader);
            $subject = $backTwig->render('subject', $data);
            Mail::queue('grohman.feedback::base-email', ['content' => $backTwig->render('main', $data)], function (Message $message) use ($sendTo, $subject, $data) {
                $message->subject($subject);
                $message->to($sendTo);
            });
        }
    }

    /**
     * @return mixed
     * @throws \ErrorException
     */
    private function findAdminEmail()
    {
        $sendTo = false;

        $users = User::all();
        foreach ($users as $user) {
            if ($user->isSuperUser()) {
                $sendTo = $user->email;
                break;
            }
        }

        if ($sendTo === false) {
            throw new \ErrorException('None email registered neither exists an admin user on the system (!?)');
        }

        return $sendTo;
    }

}
