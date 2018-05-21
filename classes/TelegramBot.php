<?php
    namespace classes;

    class TelegramBot
    {
        /**
         * @var $_message TelegramBot Сообщение которое будет отправлено.
         */
        private $_message;

        /**
         * @var TelegramBot Сформированный запрос к боту.
         */
        private $_request;

        /**
         * Путь к ЛОГ-файлу в который будут записываться сообщения об ошибках.
         */
        const LOG_FILE = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'log.txt';

        /**
         * Секретный токен для проверки доступа к выполнению действий.
         */
        const SECRET_TOKEN = '5d6d705343e910edace2db857d8de485';

        /**
         * Токен бота.
         */
        const TELEGRAM_TOKEN = '594202943:AAHNkao_EyNsUKzcqAq4ZQ3Wwu9106GUYV0';

        /**
         * Опредляет активность прокси при отправке запросов.
         */
        const CURL_PROXY_ENABLE = TRUE;

        /**
         * Тип прокси.
         */
        const CURL_PROXY_TYPE = CURLPROXY_SOCKS5;

        /**
         * URL прокси.
         */
        const CURL_PROXY_URL = 'socks5://10.101.1.243:9050';

        public function __construct($data = [])
        {
            if (empty($data['secretToken']) == TRUE)
            {
                self::writeLog('Ошибка! В программу не передан токен!', TRUE);
            }

            if($data['secretToken'] != self::SECRET_TOKEN)
            {
                self::writeLog('Ошибка! Не удалось пройти проверку токена!', TRUE);
            }
        }

        public function sendMessage()
        {
            return $this->sendRequest($this->getRequest(), 'sendMessage');
        }

        public function sendPhoto()
        {
            return $this->sendRequest($this->getRequest(), 'sendPhoto');
        }

        public function createMessage($data = [])
        {
            if(empty($data['text']) == TRUE)
            {
                self::writeLog('Ошибка! В программу не переданы входящие данные!', TRUE);
            }

            return $this->setMessage($data['text']);
        }

        public function setMessage($value)
        {
            $this->_message = $value;

            return $this;
        }

        public function getMessage()
        {
            if(empty($this->_message) == TRUE)
            {
                self::writeLog('Ошибка! Сообщение еще не сформировано!', TRUE);
            }

            return $this->_message;
        }

        public function createRequest($data = [])
        {
            if(empty($data['chatId']) == TRUE)
            {
                self::writeLog('Ошибка! В программу не передан идентификатор чата!', TRUE);
            }

            $request = array(
                'chat_id' => $data['chatId'],
                'text' => $this->getMessage(),
                'parse_mode' => 'HTML',
                'disable_web_page_preview' => TRUE,
                'disable_notification' => FALSE
            );

            $request = json_encode($request);
            $request = str_replace('<br>', '\n', $request);

            return $this->setRequest($request);
        }

        public function setRequest($value)
        {
            $this->_request = $value;

            return $this;
        }

        public function getRequest()
        {
            if(empty($this->_request) == TRUE)
            {
                self::writeLog('Ошибка! Запрос еще не сформирован!', TRUE);
            }

            return $this->_request;
        }

        public function sendRequest($request, $method)
        {
            // В hosts-файле обязательно прописать соответсвие api.telegram.org IP адресу
            $curlHandle = curl_init('https://api.telegram.org/bot' . self::TELEGRAM_TOKEN . '/' . $method);

            if(self::CURL_PROXY_ENABLE == TRUE)
            {
                curl_setopt($curlHandle, CURLOPT_PROXYTYPE, self::CURL_PROXY_TYPE);
                curl_setopt($curlHandle, CURLOPT_PROXY, self::CURL_PROXY_URL);
            }

            curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($curlHandle, CURLOPT_CONNECTTIMEOUT, 60);
            curl_setopt($curlHandle, CURLOPT_TIMEOUT, 60);
            curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $request);
            curl_setopt($curlHandle, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

            $exec = curl_exec($curlHandle);
            $error = curl_error($curlHandle);

            curl_close($curlHandle);

            if($exec === FALSE)
            {
                self::writeLog($error);
            }

            return $exec;
        }

        public static function writeLog($message, $exit = FALSE)
        {
            $message = date('Y-m-d H:i:s') . ' - ' . trim($message) . "\n";

            file_put_contents(self::LOG_FILE, $message, FILE_APPEND);

            if ($exit == TRUE)
            {
                exit($message);
            }
        }
    }
?>