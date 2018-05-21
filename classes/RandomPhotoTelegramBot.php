<?php
    namespace classes;

    class RandomPhotoTelegramBot extends TelegramBot
    {
        public function createRequest($data)
        {
            $request = json_encode([
                'chat_id' => $data['chatId'],
                // Для того чтобы фото было разное надо прикручивать к ссылке случайный хэш. Иначе фото шлется одно и то же.
                'photo' => 'http://thecatapi.com/api/images/get?api_key=MzA3NzE0&type=jpg,png&random_hash=' . sha1(uniqid(microtime(TRUE), TRUE))
            ]);

            $this->setRequest($request);

            return $this;
        }
    }
?>