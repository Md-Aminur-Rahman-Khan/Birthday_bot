<?php
require 'vendor/autoload.php';
use PhpSlackBot\Bot;




class BirthdayBot extends \PhpSlackBot\Command\BaseCommand {


    protected function configure() {
        // We don't have to configure a command name in this case
    }

    protected function execute($data, $context)
    {
        if ($data['text'] == '!birthday') {

            $file = new SplFileObject("geburtstage.csv");
            $file->setFlags(SplFileObject::READ_CSV);
            $file->setCsvControl(';');
            foreach ($file as $row) {
                list($date, $name) = $row;

                $bday = new DateTime($date);
                $today = new DateTime();
                $t_y = $today->format('Y');
                $b_y = $bday->format('Y');
                $b_d = $bday->format('d');
                $b_m = $bday->format('m');

                if ((bool)$bday->format('L') && $b_d == 29 && $b_m == 2) {
                    if ((bool)$today->format('L')) {
                        $bday_obj = new DateTime(date('Y') . '-' . $b_m . '-' . $b_d);
                        $diff = $bday_obj->diff($today);
                    } else {
                        for ($i = 1; $i++; $i <= 3) {
                            $today->add(new DateInterval(' '));
                            if ((bool)$today->format('L')) {
                                $bday_obj = new DateTime(date('Y') . '-' . $b_m . '-' . $b_d);
                                $diff = $bday_obj->diff($today);
                                break;
                            }
                        }
                    }
                } else {
                    $bday_obj = new DateTime(date('Y') . '-' . $b_m . '-' . $b_d);
                    $diff = $bday_obj->diff($today);
                }


                $now = new DateTime();
                $now_plus_7_days = new DateTime();
                $now_plus_7_days->setTimestamp(strtotime('+7 day'));



                if ($now<$bday_obj && $bday_obj<$now_plus_7_days) {
                    $this->send($this->getCurrentChannel(), null,('yuhhuuu')." Be ready for Party!!!! ".$name. " has birthday after ".$diff->format(' %a day').  " and  will " . ($t_y - $b_y) . " years old");
                }

            }


        }
    }}
            $bot = new Bot();
$bot->setToken('xoxb-465136225858-474221010033-CkddhKaMQQllamJiIIxt7sUG'); // Get your token here https://my.slack.com/services/new/bot
$bot->loadCommand(new BirthdayBot());
$bot->loadInternalCommands(); // This loads example commands
$bot->enableWebserver(8080, 'secret');
$bot->run();
?>