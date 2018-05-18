<?php
    namespace app;

    class RandomPhotoTelegramBot extends TelegramBot
    {
        public function createRequest($chatId)
        {
            $request = array(
                'chat_id' => $chatId,
                // Для того чтобы фото было разное надо прикручивать к ссылке случайный хэш. Иначе фото шлется одно и то же.
                'photo' => 'http://thecatapi.com/api/images/get?api_key=MzA3NzE0&type=jpg,png&random_hash=' . sha1(uniqid(microtime(TRUE), TRUE))
            );

            $request = json_encode($request);

            $this->setRequest($request);

            return $this;
        }

        public function sendPhoto()
        {
            $request = $this->getRequest();

            if(empty($request) == TRUE)
            {
                self::writeLog('Ошибка! Запрос еще не сформирован!', TRUE);
            }

            return $this->sendRequest($request, 'sendPhoto');
        }
    }
?>