<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Twilio\TwiML\MessagingResponse;
use App\Models\UpdateModel;
use App\Events\MessageSent;
use DateTime;
use DateTimeZone;


class TwilioController extends Controller
{
    public function receiveMedia(Request $request)
    {
        event(new MessageSent("Upload"));
        $validatedData = $request->validate([
            'WaId' => 'required|string|max:255',
            'Body' => 'nullable|string|max:65535',
            'NumMedia' => 'nullable|integer|min:0|max:10',
        ]);

        $phoneNumber = '+' . $validatedData['WaId'];
        $numMedia = $validatedData['NumMedia'] ?? 0;

        // Check if the message contains media
        $numMedia = $request->input('NumMedia');

        if ($numMedia > 0) {
            for ($i = 0; $i < $numMedia; $i++) {
                $mediaUrl = $request->input("MediaUrl$i");
                $mediaContentType = $request->input("MediaContentType$i");

                // Download and store the media
                $this->downloadAndStoreMedia($mediaUrl, $i);
            }
        }

        // You can add additional response logic or logging here
        return response()->json(['message' => 'Media processed']);
    }

    private function downloadAndStoreMedia($mediaUrl, $index)
    {
        $client = new Client(getenv('TWILIO_ACCOUNT_SID'), getenv('TWILIO_AUTH_TOKEN'));
        $response = Http::withBasicAuth(getenv('TWILIO_ACCOUNT_SID'), getenv('TWILIO_AUTH_TOKEN'))->get($mediaUrl);

        if ($response->successful()) {
            $content = $response->body();
            $extension = $this->getExtensionFromContentType($response->header('Content-Type'));
            $filename = 'whatsapp-media-' . time() . "-{$index}." . $extension;
            // $path = 'uploads/' . $filename;
            $path = "D:/New/" . $filename;


            // Ensure the directory exists and is writable
            if (!file_exists(dirname($path))) {
                mkdir(dirname($path), 0777, true);
            }

            // Save the media file to the specified directory
            file_put_contents($path, $content);

            // Optionally, you might want to return the path or URL, depending on your requirements
            return $path;

            // Save the media in local storage
            // Storage::disk('public')->put($path, $content);

            // return Storage::disk('public')->url($path);
        } else {
            throw new \Exception('Failed to download media from Twilio');
        }
    }

    private function getExtensionFromContentType($contentType)
    {
        $mimeTypes = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'video/mp4' => 'mp4',
            // Add more MIME types and extensions as needed
        ];

        return $mimeTypes[$contentType] ?? 'bin';
    }


    public function receiveMessage(Request $request)
    {
        try {
            $message = file_get_contents('php://input');

            preg_match('/&MediaUrl0=(.*?)&/', $message, $matches1);
            preg_match('/&Body=(.*?)&/', $message, $matches);
            preg_match('/&WaId=(.*?)&/', $message, $number);

            $phonenumber = '+' . urldecode($number[1]);

            if ($matches[1] != '') {
                $result = urldecode($matches[1]);
                $type = "message";
            } else {
                preg_match('/MediaContentType0=(.*?)%/', $message, $application);
                $resultType = urldecode($application[1]);
                $result = urldecode($matches1[1]);

                if ($resultType == 'video') {
                    $type = "video";
                } else if ($resultType == 'image') {
                    $type = "image";
                } else {
                    $type = "application";
                }
            }

            $newResult = new UpdateModel();
            $newResult->user = "2";
            $newResult->type = $type;
            $newResult->value = $result;
            $newResult->phonnumber = $phonenumber;
            $newResult->time = date('Y-m-d H:i:s');
            $newResult->save();

            event(new MessageSent($newResult));

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

        private function saveMedia($phoneNumber, $type, $mediaPath)
    {
        $newResult = new UpdateModel();
        $newResult->user = "2";
        $newResult->type = $type;
        $newResult->value = $mediaPath;
        $newResult->phonnumber = $phoneNumber;
        $newResult->time = now()->toDateTimeString();
        $newResult->save();

        event(new MessageSent($newResult));
    }

        private function determineMediaType($contentType)
    {
        if (strpos($contentType, 'image') !== false) {
            return 'image';
        } elseif (strpos($contentType, 'video') !== false) {
            return 'video';
        } elseif (strpos($contentType, 'audio') !== false) {
            return 'audio';
        } else {
            return 'application';
        }
    }

}
