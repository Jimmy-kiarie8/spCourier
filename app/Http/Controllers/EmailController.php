<?php

namespace App\Http\Controllers;

// use App\Email;
use App\Http\Requests\SendRequest;
use App\Http\Requests\SubscribeRequest;
use App\Mail\CampaignReady;
use App\Notifications\Slack;
use App\Subscriber;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mail;
use App\E_mail;

class EmailController extends Controller
{

    public function verify($verifyToken)
    {
        $verifyToken = User::where('verifyToken', $verifyToken)->firstOrFail()
            ->update(['verifyToken' => null, 'status' => '1']);
        return redirect()->route('login')->with('success', 'Email verified please login');
        // $this->guard()->login($user);
        // Auth::login($verifyToken->verifyToken);
    }
    public function sendmail(SendRequest $request)
    {
        // return $request->all();
        $subscribers = Subscriber::all();

        foreach ($subscribers as $subscriber) {
            // Mail::to($subscriber->email)->queue(new CampaignReady($subscriber, $request->title, $request->content));
            Mail::to($subscriber->email)->queue(new CampaignReady($subscriber, $request->title, $request->content));
        }
        $email = new E_mail;
        $email->title = $request->title;
        $email->content = $request->content;
        return $subscribers;
    }

    public function subscribe(SubscribeRequest $request)
    {
        // return $request->all();
        $subscriber = new Subscriber;
        $subscriber->name = $request->name;
        $subscriber->email = $request->email;
        $subscriber->user_id = Auth::id();
        $subscriber->save();
        // $mail = Mail::send('emails.subscribed', ['name' => $subscriber->name], function ($message) use ($subscriber) {
        //     $message->from('courier@courier.com', 'Email Platform');
        //     $message->to($subscriber->email);
        // });

        return $subscriber;
    }

    public function updateSubscribers(Request $request, $id)
    {
        // return $request->all();
        $subscriber = Subscriber::find($id);
        $subscriber->name = $request->name;
        $subscriber->email = $request->email;
        $subscriber->save();
        return $subscriber;
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Email  $email
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subscriber $subscriber, $id)
    {
        Subscriber::find($id)->delete();
        // return $id;
    }

    public function refresh(Subscriber $subscriber, $id)
    {
       return Subscriber::where('id', $id)->restore();
        // return $id;
    }


    public function getsubscribers()
    {
        return Subscriber::all();
    }

    public function getunsubscribed()
    {
        // return Subscriber::all();
        $subscribers = Subscriber::onlyTrashed()->get();
        return $subscribers;
    }
}
