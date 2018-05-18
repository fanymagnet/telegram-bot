<?php
    require(__DIR__ . '/TelegramBot.class.php');

    $dataMessage = file_get_contents('php://input');

    switch(@$_GET['typeEvent'])
    {
        case 'TestBot':
            (new TelegramBot(@$_GET['secretToken']))
                ->createMessage($dataMessage)
                ->createRequest(@$_GET['chatId'])
                ->sendMessage();
        break;

        case 'GitLab':

            require(__DIR__ . '/GitLabTelegramBot.class.php');

            (new GitLabTelegramBot(@$_SERVER['HTTP_X_GITLAB_TOKEN']))
                ->createMessage($dataMessage, @$_SERVER['HTTP_X_GITLAB_EVENT'])
                ->createRequest(@$_GET['chatId'])
                ->sendMessage();

        break;

        case 'RandomPhoto':

            require(__DIR__ . '/RandomPhotoTelegramBot.class.php');

            (new RandomPhotoTelegramBot(@$_GET['secretToken']))
                ->createRequest(@$_GET['chatId'])
                ->sendPhoto();

        break;

        case 'RandomAnekdot':

            require(__DIR__ . '/RandomAnekdotTelegramBot.class.php');

            (new RandomAnekdotTelegramBot(@$_GET['secretToken']))
                ->createMessage()
                ->createRequest(@$_GET['chatId'])
                ->sendMessage();
            
        break;

        default:
            TelegramBot::writeLog('Ошибка! Неизвестный тип события!', TRUE);
        break;
    }
?>