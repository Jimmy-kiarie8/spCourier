<?php

namespace App\Http\Controllers;

use App\E_mail;
use App\Fileupload;
use App\Mail\CampaignReady;
use App\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class E_mailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $emails = E_mail::take(500)->latest()->get();
        // $emails->transform(function($subscriber) {
        //     $subscribers = unserialize($subscriber->email);
        //     return $subscriber;
        // });
        // return $emails;
    }

    public function fileUpload(Request $request)
    {
        // dd($request->all());
        // $upload = new Fileupload;
        // if ($request->hasFile('file')) {
        //     // return('test');
        //     // $filename = time() . $request->file->getClientOriginalName();
        //     // $request->file->storeAs('public/test', $filename);
        //     $file_store = $request->file;
        //     // $file_path = ;
        //     // $file_file_arr = explode('/', $upload->file);
        //     // $file_file = $file_file_arr[3];
        //     // $filename =  Storage::disk('uploads')->put('file', $file_store);
        //     $filename = Storage::disk('public')->put('/', $file_store);
        // }

        // // return('noop');
        // // $imgArr = explode('/', $filename);
        // // $file_name = $imgArr[1];
        // $upload->file = $filename;
        // // $upload->file = '/storage/file/'.$file_name;
        // $upload->save();
        // return $upload;
        $upload = new Fileupload;
        if ($request->hasFile('file')) {
            // return 'file';
            $fileName = time() . $request->file->getClientOriginalName();
            $mime = $request->file->getMimeType();
            $ext = $request->file->getClientOriginalExtension();
            // dd($mime);
            $request->file->storeAs('public/file', $fileName);
        }
        // return 'no';
        $file_name = $fileName;
        $upload->file = $file_name;
        $upload->mimetype = $mime;
        $upload->ext = $ext;
        $upload->save();
        return $upload;

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return ($request->all());
        // $file_path = [];
        // // $file_path = [];
        // foreach ($request->file_up as $file) {
        //     $file_path[] = $file['file'];
        //     $mime = $file['mimetype'];
        //     $ext = $file['ext'];
        // }
        // dd($request->file->getRealPath());
        // $pdf_data = base64_decode($request->filepath);
        $data = array(
            "title" => $request->title,
            "content" => $request->content,
            "content" => $request->content,
            "filepath" => $request->file_up,
            // "filepath" => public_path() . '/storage/file/' . $file_path,
            // "mime" => $mime,
            // "ext" => $ext,
        );
        // foreach ($data['filepath'] as $file) {
        //     dd($file['file']);// attach each file
        // }
        // dd($data);
        $subscribers = Subscriber::all();
        $id = $subscribers->map(function ($subscriber) {
            return $subscriber->only('id');
        });

        foreach ($subscribers as $subscriber) {
            // dispatch(new SubscribeJob($subscriber, $data))->delay(now()->addMinutes(1));
            Mail::to($subscriber->email)->queue(new CampaignReady($subscriber, $data));
        }
        $email = new E_mail;
        $email->title = $request->title;
        $email->content = $request->content;
        $email->subscribers = serialize($id);
        $email->user_id = Auth::id();
        $email->save();
        return $subscribers;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\E_mail  $e_mail
     * @return \Illuminate\Http\Response
     */
    public function show(E_mail $e_mail, $id)
    {
        $subscribers = E_mail::where('id', $id)->get();
        $subscribers->transform(function ($subscriber) {
            $subscriber->subscribers = unserialize($subscriber->subscribers);
            return $subscriber;
        });
        // return $subscribers;
        $emails_id = [];
        foreach ($subscribers as $mail) {
            $emails_id[] = $mail->subscribers;
        }
        // $subs_id = $subscribers->map(function($subscriber) {
        //     return $subscriber->only('id');
        // });
        $emails_id = array_flatten($emails_id);
        // return $emails_id;
        return Subscriber::whereIn('id', $emails_id)->get();

    }

}
