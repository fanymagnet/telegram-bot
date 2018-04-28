<?php
    class RandomPhotoTelegramBot extends TelegramBot
    {
        public function createRequest($chatId)
        {
            $request = array(
                'chat_id' => $chatId,
                // Для того чтобы фото быо разное надо прикручивать к ссылке случайный хэш. Иначе фото шлется одно и то же.
                'photo' => 'https://source.unsplash.com/random?uniqid=' . sha1(uniqid(microtime(TRUE), TRUE))
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