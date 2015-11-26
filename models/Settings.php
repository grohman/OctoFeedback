<?php namespace IDesigning\Feedback\Models;

use Model;

class Settings extends Model
{
    public $implement = ['System.Behaviors.SettingsModel'];

    // A unique code
    public $settingsCode = 'idesigning_feedback_settings';

    // Reference to field configuration
    public $settingsFields = 'fields.yaml';
}