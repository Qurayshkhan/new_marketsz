<?php
namespace App\Traits;


use Illuminate\Support\Str;

trait CommonTrait
{
    public function sendSuccess($message, $data = '')
    {
        return response()->json([
            'status' => 200,
            'message' => $message,
            'data' => $data,
        ]);
    }
    public function sendError($error_message, $code = '', $data = null)
    {

        return response()->json([
            'status' => 400,
            'message' => $error_message,
            'data' => $data,
        ]);
    }

    public function sendWarning($status, $error_message, $data = '')
    {

        return response()->json([
            'status' => $status,
            'message' => $error_message,
            'data' => $data,
        ]);
    }

    public function addFile($file, $path)
    {

        if ($file) {
            if ($file->getClientOriginalExtension() != 'exe') {
                $type = $file->getClientMimeType();
                $destination_path = $path;
                $extension = $file->getClientOriginalExtension();
                $fileName = Str::random(15) . '.' . $extension;
                //$img=Image::make($file);
                $file->move($destination_path, $fileName);
                $file_path = $destination_path . $fileName;
                return $file_path;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    public function deleteFile($file)
    {
        if ($file && file_exists(public_path($file))) {
            unlink(public_path($file));
        }
    }

    public function sendPushNotification($message, $data, $emails, $custom_notification = false)
    {
        $content = [
            "en" => $message,
        ];

        $fields = [
            'app_id' => env("ONESIGNAL_APPID"),
            'include_external_user_ids' => $emails,
            'channel_for_external_user_ids' => 'push',
            'data' => $data,
            'contents' => $content,
        ];

        if ($custom_notification) {
            $fields["ios_sound"] = "notification.mp3";
            $fields["android_sound"] = "notification";
            $fields["android_channel_id"] = '6fec68b4-dd0c-45eb-acd9-84bacf99804f';
        }

        $fields = json_encode($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json; charset=utf-8',
            'Authorization: Basic ' . env("ONESIGNAL_APIKEY"),
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        curl_close($ch);

        info($response);
        return $response;
    }

    function generateRandomNumberFormat()
    {
        $part1 = rand(1, 9);
        $part2 = rand(1000, 9999);
        $part3 = rand(10, 99);
        return "{$part1}-{$part2}-{$part3}";
    }

    public function stringifyToArray($items)
    {
        return json_decode($items, true);
    }
}
