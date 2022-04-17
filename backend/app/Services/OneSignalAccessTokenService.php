<?php 
    namespace App\Services;

    use App\Repositories\OneSignalAccessTokenRepository;

    use Exception;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Log;
    use Illuminate\Support\Facades\Validator;
    use InvalidArgumentException;

    class OneSignalAccessTokenService{
        protected $oneSignalAccessTokenRepository;

        public function __construct(OneSignalAccessTokenRepository $oneSignalAccessTokenRepository)
        {
            $this->oneSignalAccessTokenRepository = $oneSignalAccessTokenRepository;
        }
        
        public function saveOneSignalAccessToken($data){
            $validator = Validator::make($data, [
                'user_id' => 'required',
                'onesignal_token' => 'required',
                'device_uuid' => 'required'
            ]);

            if($validator->fails()){
                throw new InvalidArgumentException($validator->errors()->first());
            }

            $result = $this->oneSignalAccessTokenRepository->save($data);

            return $result;
        }

        public function deleteByToken($token)
        {
            DB::beginTransaction();

            try{
                $oneSignalAccessTokenRepository = $this->oneSignalAccessTokenRepository->delete($token);
            }
            catch(Exception $e){
                DB::rollBack();
                Log::info($e->getMessage());

                throw new InvalidArgumentException('Unable to delete one signal data from service');
            }

            DB::commit();

            return $oneSignalAccessTokenRepository;
        }

        public function pushNotification($data){
            $result = $this->oneSignalAccessTokenRepository->pushNotif($data);
            return $result;
        }
    }

?>