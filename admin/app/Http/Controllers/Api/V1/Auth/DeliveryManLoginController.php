<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Models\Admin;
use App\Models\DeliveryMan;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class DeliveryManLoginController extends Controller
{
    public function login(Request $request)
    {
        $request->merge([
            'phone' => $this->canonicalPhone((string) $request->input('phone', '')),
        ]);

        $validator = Validator::make($request->all(), [
            'phone' => 'required|exists:delivery_men,phone',
            'password' => 'required|min:6'
        ],[
            'phone.exists' => translate('This number does not exists.'),
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $data = [
            'phone' => $request->phone,
            'password' => $request->password
        ];

        if (auth('delivery_men')->attempt($data)) {
            $token = Str::random(120);

            if(auth('delivery_men')?->user()?->application_status != 'approved')
            {
                return response()->json([
                    'errors' => [
                        ['code' => 'auth-003', 'message' => translate('messages.your_application_is_not_approved_yet')]
                    ]
                ], 401);
            }
            else if(!auth('delivery_men')?->user()?->status)
            {
                $errors = [];
                array_push($errors, ['code' => 'auth-003', 'message' => translate('messages.your_account_has_been_suspended')]);
                return response()->json([
                    'errors' => $errors
                ], 401);
            }

            $delivery_man =  DeliveryMan::where(['phone' => $request['phone']])->with(['shifts','zone'])->first();
            $delivery_man->auth_token = $token;
            $delivery_man?->save();

            $topic= Helpers::getDeliveryManTopics($delivery_man);
            return response()->json(['token' => $token, 'topic'=> $topic], 200);
        } else {
            $errors = [];
            array_push($errors, ['code' => 'auth-001', 'message' => translate('User credentials does not match.')]);
            return response()->json([
                'errors' => $errors
            ], 401);
        }
    }



    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'f_name' => 'required',
            'identity_type' => 'required|in:passport,driving_license,nid',
            'identity_number' => 'required',
            'email' => 'required|unique:delivery_men',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:9|unique:delivery_men',
            'password' => ['required', Password::min(8)->mixedCase()->letters()->numbers()->symbols()->uncompromised()],
            'zone_id' => 'required',
            'vehicle_id' => 'required',
            'earning' => 'required|in:0,1',
            'shifts' => 'required_if:earning,1',
            'image' => 'nullable|max:2048',
            'identity_image.*' => 'nullable|max:2048',
            // 'additional_documents' => 'nullable|array|max:5',
            // 'additional_documents.*' => 'nullable|max:2048',

        ], [
            'f_name.required' => translate('messages.first_name_is_required'),
            'zone_id.required' => translate('messages.select_a_zone'),
            'earning.required' => translate('messages.select_dm_type'),
            'password.min_length' => translate('The password must be at least :min characters long'),
            'password.mixed' => translate('The password must contain both uppercase and lowercase letters'),
            'password.letters' => translate('The password must contain letters'),
            'password.numbers' => translate('The password must contain numbers'),
            'password.symbols' => translate('The password must contain symbols'),
            'password.uncompromised' => translate('The password is compromised. Please choose a different one'),
            'password.custom' => translate('The password cannot contain white spaces.'),
            'shifts.required_if' => translate('messages.shift_is_required'),
            // 'additional_documents.max' => translate('You_can_chose_max_5_files_only'),
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)],403);
        }

        if ($request->has('image')) {
            $image_name = Helpers::upload(dir:'delivery-man/', format:'png',image: $request->file('image'));
        } else {
            $image_name = 'def.png';
        }

        $id_img_names = [];
        if (!empty($request->file('identity_image'))) {
            foreach ($request->identity_image as $img) {
                $identity_image = Helpers::upload(dir:'delivery-man/',format: 'png',image: $img);
                array_push($id_img_names, ['img'=>$identity_image, 'storage'=> Helpers::getDisk()]);
            }
            $identity_image = json_encode($id_img_names);
        } else {
            $identity_image = json_encode([]);
        }

        $dm = New DeliveryMan();
        $dm->f_name = $request->f_name;
        $dm->l_name = $request->l_name;
        $dm->email = $request->email;
        $dm->phone = $request->phone;
        $dm->identity_number = $request->identity_number;
        $dm->identity_type = $request->identity_type;
        $dm->identity_image = $identity_image;
        $dm->image = $image_name;
        $dm->active = 0;
        $dm->zone_id = $request->zone_id;
        $dm->vehicle_id = $request->vehicle_id;
        $dm->earning = $request->earning;
        $dm->password = bcrypt($request->password);
        $dm->application_status= 'pending';


        if(isset($request->additional_data)  && count(json_decode($request->additional_data,true)) > 0){
            $dm->additional_data = $request->additional_data ;
        }

        $additional_documents = [];
        if ($request->additional_documents) {
            foreach ($request->additional_documents as $key => $data) {
                $additional = [];
                foreach($data as $file){
                    if(is_file($file)){
                        $file_name = Helpers::upload('additional_documents/dm/', $file->getClientOriginalExtension(), $file);
                        $additional[] = ['file'=>$file_name, 'storage'=> Helpers::getDisk()];
                    }
                    $additional_documents[$key] = $additional;
                }
            }
            $dm->additional_documents = json_encode($additional_documents);
        }
        $dm->save();
        if ($request->has('shifts') && count(json_decode($request->shifts, true)) > 0) {
            $dm->shifts()->sync(json_decode($request->shifts, true));
        }
        try{
            $admin= Admin::where('role_id', 1)->first();

            $notification_status= Helpers::getNotificationStatusData('deliveryman','deliveryman_registration');
            if($notification_status?->mail_status == 'active' && config('mail.status') && Helpers::get_mail_status('registration_mail_status_dm') == '1'){
                Mail::to($request->email)->send(new \App\Mail\DmSelfRegistration('pending', $dm->f_name.' '.$dm->l_name));
                }
                $notification_status= null ;
                $notification_status= Helpers::getNotificationStatusData('admin','deliveryman_self_registration');
            if($notification_status?->mail_status == 'active' && config('mail.status') && Helpers::get_mail_status('dm_registration_mail_status_admin') == '1'){
                Mail::to($admin?->getRawOriginal('email'))->send(new \App\Mail\DmRegistration('pending', $dm->f_name.' '.$dm->l_name));
            }
        }catch(\Exception $ex){
            info($ex);
        }
        return response()->json(['message' => translate('messages.deliveryman_added_successfully')], 200);
    }

    private function canonicalPhone(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone) ?? '';
        if ($digits === '') {
            return $phone;
        }
        if (str_starts_with($digits, '580') && strlen($digits) > 12) {
            $digits = '58'.substr($digits, 3);
        }
        if (str_starts_with($digits, '0')) {
            $digits = '58'.substr($digits, 1);
        }
        if (!str_starts_with($digits, '58')) {
            $digits = '58'.$digits;
        }

        return '+'.$digits;
    }
}
