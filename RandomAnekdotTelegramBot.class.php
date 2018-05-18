<?php
    namespace app;

    class RandomAnekdotTelegramBot extends TelegramBot
    {
        public function createMessage($dataMessage)
        {
            $anekdotHtml = file_get_contents('http://www.anekdot.ru/rss/randomu.html');

            preg_match("#anekdot_texts = \[(.*)\];#U", $anekdotHtml, $anekdotString);

            preg_match_all("#'(.*)'#U", $anekdotString[1], $anekdotList);

            $this->setMessage($anekdotList[1][mt_rand(0, (count($anekdotList[1]) - 1))]);

            return $this;
        }
    }
?>