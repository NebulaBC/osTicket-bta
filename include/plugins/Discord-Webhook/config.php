<?php

require_once INCLUDE_DIR . 'class.plugin.php';

class DiscordPluginConfig extends PluginConfig {

    // Provide compatibility function for versions of osTicket prior to
    // translation support (v1.9.4)
    function translate() {
        if (!method_exists('Plugin', 'translate')) {
            return array(
                function ($x) {
                    return $x;
                },
                function ($x, $y, $n) {
                    return $n != 1 ? $y : $x;
                }
            );
        }
        return Plugin::translate('teams');
    }

    function pre_save(&$config, &$errors) {
        if ($config['slack-regex-subject-ignore'] && false === @preg_match("/{$config['slack-regex-subject-ignore']}/i", null)) {
            $errors['err'] = 'Your regex was invalid, try something like "spam", it will become: "/spam/i" when we use it.';
            return FALSE;
        }
        return TRUE;
    }

    function getOptions() {
        list ($__, $_N) = self::translate();

        return array(
            'discord'                      => new SectionBreakField(array(
                'label' => $__('Discord Webhook notifier'),
                'hint'  => $__('Forked by Mak from: https://github.com/ipavlovi/osTicket-Microsoft-Teams-plugin')
            )),
            'discord-webhook-url'          => new TextboxField(array(
                'label'         => $__('Webhook URL'),
                'required' => true,
                'configuration' => array(
                    'size'   => 100,
                    'length' => 700
                ),
            )),
            'discord-topic-id' => new ChoiceField(array(
                'default' => 0,
                'required' => true,
                'label' => 'Help Desk topic to listen to',
                'hint' => 'i.e. Support, Bug reports, etc..',
                'choices' =>
                    array(0 => '— '.'Topic to use'.' —')
                    + Topic::getHelpTopics(),
            )),
        );
    }

}
