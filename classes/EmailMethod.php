<?php namespace IDesigning\Feedback\Classes;


use Backend\Models\User;
use Backend\Widgets\Form;
use IDesigning\Feedback\Controllers\Channels;
use IDesigning\Feedback\Models\Channel;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;

class EmailMethod implements Method
{

    public function boot()
    {
        Channels::extendFormFields(function (Form $form, $model) {
            $validatorConfig = config()->get('idesigning.feedback::validation');
            unset($validatorConfig['channel_id']);

            foreach($validatorConfig as $key=>$value){
                if(preg_match('/extra./', $key)) {
                    $newKey = str_replace('extra.', '', $key);
                    $validatorConfig[$newKey] = $value;
                    unset($validatorConfig[$key]);
                }
            }

            $messageVariables = array_keys($validatorConfig);
            $form->addFields([
                'method_data[email_destination]' => [
                    'label' => "idesigning.feedback::lang.channel.emailDestination",
                    'commentAbove' => "idesigning.feedback::lang.backend.settings.channel.emailDestinationComment",
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
                    'type' => 'richeditor',
                    //'language' => 'twig',
                    'label' => "Шаблон",
                    'commentAbove' => 'Можно использовать переменные из формы - например {{ name }} для вставки имени отправителя. Так же доступны '.implode(', ', $messageVariables),
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
                        'field' => 'method',
                        'condition' => 'value[email]',
                    ]
                ],

                'method_data[backmail_subject]' => [
                    'label' => 'Тема ответного письма',
                    'trigger' => [
                        'action' => 'show',
                        'field' => 'method_data[backmail]',
                        'condition' => 'checked',
                    ],
                ],

                'method_data[backmail_template]' => [
                    'label' => 'Шаблон ответного письма',
                    //'language' => 'twig',
                    'type' => 'richeditor',
                    'trigger' => [
                        'action' => 'show',
                        'field' => 'method_data[backmail]',
                        'condition' => 'checked',
                    ],
                ],

                'files' => [
                    'label' => 'Вложения ответного письма',
                    'type' => 'fileupload',
                    'trigger' => [
                        'action' => 'show',
                        'field' => 'method_data[backmail]',
                        'condition' => 'checked',
                    ],
                ],

            ]);
        });

        Channel::extend(function (Channel $model) {
            $model->rules[ 'method_data.email_destination' ] = "emails";
            $model->attributeNames[ 'method_data.email_destination' ] =
                'idesigning.feedback::lang.channel.emailDestination';
        });
    }


    public function send($methodData, $data, Channel $channel)
    {
        $sendTo = $methodData[ 'email_destination' ];
        if ($sendTo == null) {
            // find the first admin user on the system
            $sendTo = $this->findAdminEmail();
        }

        $loader = new \Twig_Loader_Array([
            'subject' => $methodData[ 'subject' ],
            'main' => $methodData[ 'template' ]
        ]);
        $twig = new \Twig_Environment($loader);

        $subject = $twig->render('subject', $data);
        $files = $data[ 'files' ];
        $data[ 'files' ] = [ ];
        foreach ($files as $file) {
            $data[ 'files' ][] = [ 'name' => $file->getClientOriginalName(), 'path' => $file->getRealPath() ];
        }

        Mail::queue('idesigning.feedback::base-email', [ 'content' => $twig->render('main', $data) ],
            function (Message $message) use ($sendTo, $subject, $data) {

                foreach ($data[ 'files' ] as $file) {
                    $message->attach($file[ 'path' ], [ 'as' => $file[ 'name' ] ]);
                }

                $message->subject($subject);
                $message->to(array_map('trim', explode(',', $sendTo)));
                $replyTo = isset($data[ 'email' ]) ? $data[ 'email' ] : null;
                $replyToName = isset($data[ 'name' ]) ? $data[ 'name' ] : 'Аноним';
                if ($replyTo) {
                    $message->replyTo($replyTo, $replyToName);
                }
            });

        if ($methodData[ 'backmail' ] == 1 && isset($data[ 'email' ])) {
            $sendTo = $data[ 'email' ];
            $backLoader = new \Twig_Loader_Array([
                'subject' => $methodData[ 'backmail_subject' ],
                'main' => $methodData[ 'backmail_template' ]
            ]);
            $backTwig = new \Twig_Environment($backLoader);
            $subject = $backTwig->render('subject', $data);
            $data[ 'files' ] = [ ];
            $files = $channel->files()->get();
            foreach ($files as $file) {
                $data[ 'files' ][] = [ 'name' => $file->getFilename(), 'path' => $file->getLocalPath() ];
            }

            Mail::queue('idesigning.feedback::back-email', [ 'content' => $backTwig->render('main', $data) ],
                function (Message $message) use ($sendTo, $subject, $data) {
                    $message->to($sendTo)->subject($subject);
                    foreach ($data[ 'files' ] as $file) {
                        $message->attach($file[ 'path' ], [ 'as' => $file[ 'name' ] ]);
                    }
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