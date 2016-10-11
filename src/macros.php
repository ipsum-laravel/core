<?php

/**
 * Display Error / Success / Warn / Info messages / Help
 *
 * These messages are set using Session::flash(<message_type>, <message>);
 */
HTML::macro("notifications", function($errors = null, $template = null) {

    if ($template === null) {
        $template = Config::has('view.notification') ? Config::get('view.notification') : 'IpsumCore::partials.notifications';
    }

    $views =  '';
    $alert_types = array(
        "error" => 'IpsumCore::notifications.title.error',
        "warning" => 'IpsumCore::notifications.title.warning',
        "success" => 'IpsumCore::notifications.title.success',
        "info" => 'IpsumCore::notifications.title.info',
        "help" => 'IpsumCore::notifications.title.help'
    );


    foreach ($alert_types as $type => $label) {
        $messages = array();
        if(Session::has($type)) {
            $flash = Session::get($type);
            $messages = is_array($flash) ? $flash : array($flash);
        }
        if ($errors != null and $type == 'error' and $errors->any()) {
            $messages = array_merge($messages, $errors->all());
        }

        if(!empty($messages)) {
            $views .= View::make($template, array('type' => $type, 'label' => Lang::get($label), 'messages' => $messages));
        }
    }
    return empty($views) ? null : $views;
});
