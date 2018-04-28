<?php
    class RandomAnegdotTelegramBot extends TelegramBot
    {
        public function createMessage()
        {
            $message = file_get_contents('http://www.anekdot.ru/rss/random.html');



            $this->setMessage($message);

            return $this;
        }
    }
?>