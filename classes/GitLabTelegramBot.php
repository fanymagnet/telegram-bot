<?php
    namespace classes;

    class GitLabTelegramBot extends TelegramBot
    {
        public function createMessage($data)
        {
            if(empty($data['phpInput']) == TRUE || empty($data['gitlabEvent']) == TRUE)
            {
                self::writeLog('Ошибка! В программу не переданы входящие данные!', TRUE);
            }

            $data['phpInput'] = json_decode($data['phpInput'], TRUE);

            switch($data['gitlabEvent'])
            {
                case 'Push Hook':
                    $this->createPushHookMessage($data['phpInput']);
                break;

                case 'Merge Request Hook':
                    $this->createMergeRequestHookMessage($data['phpInput']);
                break;

                default:
                    self::writeLog('Ошибка! Неизвестный тип события GitLab!', TRUE);
                break;
            }

            return $this;
        }

        private function createPushHookMessage($data)
        {
            $messages = [];

            if ($data["total_commits_count"] != 0)
            {
                $messages[] = '<b>Новый Commit!</b>';

                $commits = $data["commits"];
                $branch = preg_match('/.*\/(.*)$/', $data["ref"], $branch) == 0
                    ? $data["ref"]
                    : $branch[1];

                for ($i = 0; $i < count($commits); $i++)
                {
                    $messages[] = '<b>Автор:</b> ' . trim($commits[$i]["author"]["name"]) . '<br>' .
                        '<b>Ветка:</b> ' . trim($branch) . '<br>' .
                        '<b>Описание:</b> ' . trim($commits[$i]["message"]) . '<br>' .
                        '<br><a href="' . trim($commits[$i]["url"]) . '">Просмотреть изменения</a>';
                }
            }

            $message = implode('<br><br>', $messages);

            $this->setMessage($message);
        }

        private function createMergeRequestHookMessage($data)
        {
            $message = NULL;

            if ($data["object_attributes"]["state"] != "merged" && $data["object_attributes"]["action"] == "open")
            {
                $merge = $data["object_attributes"];

                $message = '<b>Новый Merge Request!</b><br><br>' .
                    '<b>Автор:</b> ' . trim($merge["last_commit"]["author"]["name"]) . '<br>' .
                    '<b>Ветки:</b> ' . trim($data["object_attributes"]["source_branch"]) . ' <b>в</b> ' . trim($data["object_attributes"]["target_branch"]) . '<br>' .
                    '<b>Описание:</b> ' . trim($merge["title"]) . '<br>' .
                    '<br><a href="' . trim($data["repository"]["homepage"]) . '/merge_requests/' . trim($merge["iid"]) . '">Просмотреть изменения</a>';
            }

            $this->setMessage($message);
        }
    }
?>