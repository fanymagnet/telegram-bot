<?php
    spl_autoload_register(function($className) {
        include __DIR__. DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.class.php';
    });

    switch(@$_GET['typeEvent'])
    {
        case 'TestBot':
            (new classes\TelegramBot(@$_GET['secretToken']))
                ->createMessage(file_get_contents('php://input'))
                ->createRequest(@$_GET['chatId'])
                ->sendMessage();
        break;

        case 'GitLab':
            (new classes\GitLabTelegramBot(@$_SERVER['HTTP_X_GITLAB_TOKEN']))
                ->createMessage([
                    'phpInput' => file_get_contents('php://input'),
                    'gitlabEvent' => @$_SERVER['HTTP_X_GITLAB_EVENT']
                ])
                ->createRequest(@$_GET['chatId'])
                ->sendMessage();
        break;

        case 'RandomPhoto':
            (new classes\RandomPhotoTelegramBot(@$_GET['secretToken']))
                ->createRequest(@$_GET['chatId'])
                ->sendPhoto();
        break;

        case 'RandomAnekdot':
            (new classes\RandomAnekdotTelegramBot(@$_GET['secretToken']))
                ->createMessage(file_get_contents('php://input'))
                ->createRequest(@$_GET['chatId'])
                ->sendMessage();
        break;

        default:
            classes\TelegramBot::writeLog('Ошибка! Неизвестный тип события!', TRUE);
        break;
    }
?>