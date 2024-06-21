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


class SmsController extends Controller
{
    public function VueMessage(Request $request)
    {

        try {
            $message = file_get_contents('php://input');
            $decode_message = json_decode($message, true);
            $message_decode = $decode_message['message'];
            $phoneNumber_decode = $decode_message['PhoneNumber'];
            // Process the message or perform any necessary actions
            // Send a reply back to the sender using Twilio
            $sid = getenv("TWILIO_ACCOUNT_SID");
            $token = getenv("TWILIO_AUTH_TOKEN");
            $twilio = new Client($sid, $token);

            $twilio->messages->create("whatsapp:$phoneNumber_decode", [
                "from" => "whatsapp:+14155238886",
                "body" => $message_decode
            ]);

            $timezone = new DateTimeZone('America/New_York');
            $now = new DateTime('now', $timezone);
            $currentTime = $now->format('h:i A');

            $newResult = new UpdateModel();
            $newResult->user = "1";
            $newResult->type = "message";
            $newResult->value = $message_decode;
            $newResult->phonnumber = $phoneNumber_decode;
            $newResult->time = $currentTime;
            $newResult->save();

            event(new MessageSent($newResult));

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function MediaMessage(Request $request)
    {
        try {
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $extension = $file->getClientOriginalExtension(); // Get the file extension
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('uploads', $fileName, 'public'); // Storing the file

                $fileUrl = url('/storage/' . $filePath); // Generate URL to access file

                // Determine file type based on the extension
                $type = $this->determineFileType($extension);

                // Send the file URL using Twilio
                $sid = env("TWILIO_ACCOUNT_SID");
                $token = env("TWILIO_AUTH_TOKEN");
                $twilio = new Client($sid, $token);

                $twilio->messages->create("whatsapp:" . $request->input('PhoneNumber'), [
                    "from" => "whatsapp:+14155238886",
                    "body" => $fileUrl,
                ]);

                $timezone = new DateTimeZone('America/New_York');
                $now = new DateTime('now', $timezone);
                $currentTime = $now->format('h:i A');

                $newResult = new UpdateModel();
                $newResult->user = "1";
                $newResult->type = $type;
                $newResult->value = $fileUrl;
                $newResult->phonnumber = $request->input('PhoneNumber');
                $newResult->time = $currentTime;
                $newResult->save();

                event(new MessageSent($newResult));

                return response()->json(['message' => 'File uploaded successfully', 'fileUrl' => $fileUrl]);

            } else {
                return response()->json(['error' => 'File not provided.'], 422);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Helper function to determine file type based on the file extension
    private function determineFileType($extension)
    {
        $imageExtensions = ['png', 'jpg', 'jpeg', 'gif'];
        $videoExtensions = ['mp4', 'avi', 'mov'];
        $audioExtensions = ['mp3', 'wav', 'aac'];
        $documentExtensions = ['doc', 'docx', 'pdf', 'txt'];

        if (in_array(strtolower($extension), $imageExtensions)) {
            return 'image';
        } elseif (in_array(strtolower($extension), $videoExtensions)) {
            return 'video';
        } elseif (in_array(strtolower($extension), $audioExtensions)) {
            return 'audio';
        } elseif (in_array(strtolower($extension), $documentExtensions)) {
            return 'document';
        } else {
            return 'file';
            // Default to document for other types
        }
    }

    public function receiveMessage(Request $request)
    {
        // Validate the incoming request data

        $validatedData = $request->validate([
            'WaId' => 'required|string|max:255',
            'Body' => 'nullable|string|max:65535',
            'NumMedia' => 'nullable|integer|min:0|max:10',
        ]);

        $phoneNumber = '+' . $validatedData['WaId'];
        $body = $validatedData['Body'] ?? '';
        $numMedia = $validatedData['NumMedia'] ?? 0;

        if ($numMedia > 0) {
            for ($i = 0; $i < $numMedia; $i++) {
                $mediaUrl = $request->input("MediaUrl$i");
                // Download and store the media
                $publicUrl = $this->downloadAndStoreMedia($phoneNumber, $mediaUrl, $i);
                // event(new MessageSent($publicUrl));
            }
        }

        if (!empty($body)) {
            $this->saveMessage($phoneNumber, $body);
        }

        return response()->json(['message' => 'Media processed']);

    }

    private function downloadAndStoreMedia($phoneNumber, $mediaUrl, $index)
    {

        $client = new Client(getenv('TWILIO_ACCOUNT_SID'), getenv('TWILIO_AUTH_TOKEN'));
        $response = Http::withBasicAuth(getenv('TWILIO_ACCOUNT_SID'), getenv('TWILIO_AUTH_TOKEN'))->get($mediaUrl);

        if ($response->successful()) {
            // event(new MessageSent($response));
            $content = $response->body();
            $extension = $this->getExtensionFromContentType($response->header('Content-Type'));
            $filename = 'whatsapp-media-' . time() . "-{$index}." . $extension;
            // $path = "D:/New/" . $filename;
            // Store the media in the public/uploads directory
            $storagePath = 'uploads/' . $filename; // Path inside the public directory
            $path = public_path($storagePath);
            // Ensure the directory exists and is writable
            if (!file_exists(dirname($path))) {
                mkdir(dirname($path), 0777, true);
            }

            // Save the media file to the specified directory
            file_put_contents($path, $content);

            // Return the full URL to the stored media file
            $url = asset($storagePath);

            if ($extension == 'jpg' || $extension == 'png' || $extension == 'gif' || $extension == 'webp') {
                $type = 'image';
            } elseif ($extension == 'mp4' || $extension == 'mov' || $extension == 'avi' || $extension == '3gp') {
                $type = 'video';
            } elseif ($extension == 'pdf' || $extension == 'doc' || $extension == 'docx') {
                $type = 'document';
            } elseif ($extension == 'mp3' || $extension == 'wav') {
                $type = 'audio';
            } else {
                $type = 'file';
            }

            $timezone = new DateTimeZone('America/New_York');
            $now = new DateTime('now', $timezone);
            $currentTime = $now->format('h:i A');

            $newResult = new UpdateModel();
            $newResult->user = "2";
            $newResult->type = $type;
            $newResult->value = $url;
            $newResult->phonnumber = $phoneNumber;
            $newResult->time = $currentTime;
            $newResult->save();

            event(new MessageSent($newResult));
            // event(new MessageSent($url));
            // Optionally, you might want to return the path or URL, depending on your requirements
            return $url;
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
            'image/gif' => 'gif',
            'image/webp' => 'webp',
            'video/mp4' => 'mp4',
            'video/avi' => 'avi',
            'video/3gpp' => '3gp',
            'video/quicktime' => 'mov',
            'audio/mpeg' => 'mp3',
            'audio/ogg' => 'ogg',
            'audio/wav' => 'wav',
            'audio/webm' => 'webm',
            'application/pdf' => 'pdf',
            'application/msword' => 'doc',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
            'application/vnd.ms-powerpoint' => 'ppt',
            // Add more MIME types and extensions as needed
        ];

        return $mimeTypes[$contentType] ?? 'bin';
    }

    private function saveMessage($phoneNumber, $body)
    {
        $timezone = new DateTimeZone('America/New_York');
        $now = new DateTime('now', $timezone);
        $currentTime = $now->format('h:i A');

        $newResult = new UpdateModel();
        $newResult->user = "2";
        $newResult->type = 'message';
        $newResult->value = $body;
        $newResult->phonnumber = $phoneNumber;
        $newResult->time = $currentTime;
        $newResult->save();

        event(new MessageSent($newResult));
    }

}







