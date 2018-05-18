<?php
    namespace app;

    switch(@$_GET['typeEvent'])
    {
        case 'TestBot':
            (new TelegramBot(@$_GET['secretToken']))
                ->createMessage(file_get_contents('php://input'))
                ->createRequest(@$_GET['chatId'])
                ->sendMessage();
        break;

        case 'GitLab':
            (new GitLabTelegramBot(@$_SERVER['HTTP_X_GITLAB_TOKEN']))
                ->createMessage([
                    'phpInput' => file_get_contents('php://input'),
                    'gitlabEvent' => @$_SERVER['HTTP_X_GITLAB_EVENT']
                ])
                ->createRequest(@$_GET['chatId'])
                ->sendMessage();
        break;

        case 'RandomPhoto':
            (new RandomPhotoTelegramBot(@$_GET['secretToken']))
                ->createRequest(@$_GET['chatId'])
                ->sendPhoto();
        break;

        case 'RandomAnekdot':
            (new RandomAnekdotTelegramBot(@$_GET['secretToken']))
                ->createMessage(file_get_contents('php://input'))
                ->createRequest(@$_GET['chatId'])
                ->sendMessage();
        break;

        default:
            TelegramBot::writeLog('Ошибка! Неизвестный тип события!', TRUE);
        break;
    }
?>