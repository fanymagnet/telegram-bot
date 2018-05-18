<?php
    namespace classes;

    class GitLabTelegramBot extends TelegramBot
    {
        public function createMessage($dataMessage)
        {
            if(empty($dataMessage['phpInput']) == TRUE || empty($dataMessage['gitlabEvent']) == TRUE)
            {
                self::writeLog('Ошибка! В программу не переданы входящие данные!', TRUE);
            }

            $dataMessage['phpInput'] = json_decode($dataMessage['phpInput'], TRUE);

            switch($dataMessage['gitlabEvent'])
            {
                case 'Push Hook':
                    $this->createPushHookMessage($dataMessage['phpInput']);
                break;

                case 'Merge Request Hook':
                    $this->createMergeRequestHookMessage($dataMessage['phpInput']);
                break;

                default:
                    self::writeLog('Ошибка! Неизвестный тип события GitLab!', TRUE);
                break;
            }

            return $this;
        }

        private function createPushHookMessage($dataMessage)
        {
            $messages = array();

            if ($dataMessage["total_commits_count"] != 0)
            {
                $messages[] = 'Новый Commit!';

                $commits = $dataMessage["commits"];
                $branch = preg_match('/.*\/(.*)$/', $dataMessage["ref"], $branch) == 0
                    ? $dataMessage["ref"]
                    : $branch[1];

                for ($i = 0; $i < count($commits); $i++)
                {
                    $messages[] = '<b>Автор:</b> ' . trim($commits[$i]["author"]["name"]) . '<br>' .
                        '<b>Ветка:</b> ' . trim($branch) . '<br>' .
                        '<b>Описание:</b> ' . trim($commits[$i]["message"]) . '<br>' .
                        '<a href="' . trim($commits[$i]["url"]) . '">Просмотреть изменения</a>';
                }
            }

            $message = implode('<br><br>', $messages);

            $this->setMessage($message);
        }

        private function createMergeRequestHookMessage($dataMessage)
        {
            $message = NULL;

            if ($dataMessage["object_attributes"]["state"] != "merged" && $dataMessage["object_attributes"]["action"] == "open")
            {
                $merge = $dataMessage["object_attributes"];

                $message = '<b>Новый Merge Request!</b><br>' .
                    '<b>Автор:</b> ' . trim($merge["last_commit"]["author"]["name"]) . '<br>' .
                    '<b>Ветки:</b> ' . trim($dataMessage["object_attributes"]["source_branch"]) . ' <b>в</b> ' . trim($dataMessage["object_attributes"]["target_branch"]) . '<br>' .
                    '<b>Описание:</b> ' . trim($merge["title"]) . '<br>' .
                    '<a href="' . trim($dataMessage["repository"]["homepage"]) . '/merge_requests/' . trim($merge["iid"]) . '">Просмотреть изменения</a>';
            }

            $this->setMessage($message);
        }
    }
?>