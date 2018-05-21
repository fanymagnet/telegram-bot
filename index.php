<?php
    use classes\TelegramBot;
    use classes\GitLabTelegramBot;
    use classes\RandomPhotoTelegramBot;
    use classes\RandomAnekdotTelegramBot;

    // Автозагрузчик классов
    spl_autoload_register(function($className) {
        require_once __DIR__ . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
    });

    switch(@$_GET['typeEvent'])
    {
        case 'TestBot':
            (
                new TelegramBot([
                    'secretToken' => @$_GET['secretToken']
                ])
            )
                ->createMessage([
                    'phpInput' => file_get_contents('php://input')
                ])
                ->createRequest([
                    'chatId' => @$_GET['chatId']
                ])
                ->sendMessage();
        break;

        case 'GitLab':
            (
                new GitLabTelegramBot([
                    'secretToken' => @$_SERVER['HTTP_X_GITLAB_TOKEN']
                ])
            )
                ->createMessage([
                    'phpInput' => file_get_contents('php://input'),
                    'gitlabEvent' => @$_SERVER['HTTP_X_GITLAB_EVENT']
                ])
                ->createRequest([
                    'chatId' => @$_GET['chatId']
                ])
                ->sendMessage();
        break;

        case 'RandomPhoto':
            (
                new RandomPhotoTelegramBot([
                    'secretToken' => @$_GET['secretToken']
                ])
            )
                ->createRequest([
                    'chatId' => @$_GET['chatId']
                ])
                ->sendPhoto();
        break;

        case 'RandomAnekdot':
            (
                new RandomAnekdotTelegramBot([
                    'secretToken' => @$_GET['secretToken']
                ])
            )
                ->createMessage([
                    'phpInput' => file_get_contents('php://input')
                ])
                ->createRequest([
                    'chatId' => @$_GET['chatId']
                ])
                ->sendMessage();
        break;

        default:
            TelegramBot::writeLog('Ошибка! Неизвестный тип события!', TRUE);
        break;
    }
?>