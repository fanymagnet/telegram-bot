<?php
    namespace classes;

    class RandomPhotoTelegramBot extends TelegramBot
    {
        public function createRequest($data = [])
        {
            if(empty($data['chatId']) == TRUE)
            {
                self::writeLog('Ошибка! В программу не передан идентификатор чата!', TRUE);
            }

            // Для того чтобы фото было каждый раз разное надо прикручивать к ссылке случайный хэш.
            $hash = sha1(uniqid(microtime(TRUE), TRUE));
            $photo = 'http://thecatapi.com/api/images/get?api_key=MzA3NzE0&type=jpg,png&hash=' . $hash;

            $request = json_encode([
                'chat_id' => $data['chatId'],
                'photo' => $photo
            ]);

            return $this->setRequest($request);
        }
    }
?>