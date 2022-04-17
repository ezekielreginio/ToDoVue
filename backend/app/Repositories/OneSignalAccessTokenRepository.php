<?php 
    namespace App\Repositories;
    use App\Models\OneSignalAccessToken;

    use Ladumor\OneSignal\OneSignal;

    class OneSignalAccessTokenRepository{
        protected $oneSignalAccessToken;
        protected $output;
        public function __construct(OneSignalAccessToken $oneSignalAccessToken)
        {
            $this->output = new \Symfony\Component\Console\Output\ConsoleOutput();
            $this->oneSignalAccessToken = $oneSignalAccessToken;
        }

        public function save($data){
            $oneSignalAccessToken = new $this->oneSignalAccessToken;
            $oneSignalAccessToken->user_id = $data['user_id'];
            $oneSignalAccessToken->one_signal_token = $data['onesignal_token'];
            $oneSignalAccessToken->device_uuid = $data['device_uuid'];

            $oneSignalAccessToken->save();

            return $oneSignalAccessToken->fresh();
        }

        public function delete($token){
            $oneSignalAccessToken = $this->oneSignalAccessToken->where('one_signal_token', '=', $token);
            $oneSignalAccessToken->delete();

            return $oneSignalAccessToken;
        }

        public function pushNotif($data){
            $result = $this->oneSignalAccessToken->whereIn('user_id', $data['receivers'])->get(['one_signal_token']);
            $receivers = [];
            foreach($result as $r){
                array_push($receivers, $r->one_signal_token);
            }

            $fields['include_player_ids'] = $receivers;
            OneSignal::sendPush($fields, $data["message"]);
            return null;
        }
    }
?>