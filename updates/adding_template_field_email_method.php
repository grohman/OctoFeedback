<?php namespace IDesigning\Feedback\Updates;

class AddingTemplateFieldEmailMethod extends \Seeder
{
    public function run()
    {

        \DB::table('grohman_feedback_channels')->where('code', '=', 'default')->update([
            'method_data' => json_encode("'template' => '<h1>You have just received a feedback from your site!</h1>
<p>
    Here is the contact information: {{ name }} &lt;<a href=\"mailto:{{ email }}\">{{ email }}</a>&gt; <br>
</p>

<h2>The message:</h2>
<p>{{ message }}</p>'")
        ]);

    }
}