<?php
    class RandomPhotoTelegramBot extends TelegramBot
    {
        public function createRequest($chatId)
        {
            $request = array(
                'chat_id' => $chatId,
                'photo' => 'https://source.unsplash.com/random'
            );

            $request = json_encode($request);

            $this->setRequest($request);

            return $this;
        }

        public function sendPhoto()
        {
            $request = $this->getRequest();

            $this->sendRequest($request, 'sendPhoto');
        }
    }
?>