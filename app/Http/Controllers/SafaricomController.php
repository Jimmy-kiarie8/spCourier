<?php

namespace App\Http\Controllers;

use App\Safaricom;
use Illuminate\Http\Request;

class SafaricomController extends Controller
{
    public function confirmation(Request $request)
    {
        // $array = json_decode($request, true);
        $TransID = $request->TransID;
        $TransactionType = $request->TransactionType;
        $TransTime = $request->TransTime;
        $TransAmount = $request->TransAmount;
        $BusinessShortCode = $request->BusinessShortCode;
        $BillRefNumber = $request->BillRefNumber;
        $InvoiceNumber = $request->InvoiceNumber;
        $MSISDN = $request->MSISDN;
        $First_Name = $request->First_Name;
        $Middle_Name = $request->Middle_Name;
        $Last_Name = $request->Last_Name;
        $OrgAccountBalance = $request->OrgAccountBalance;
        $Request = $request->Request;

        $safaricom = new Safaricom;
        $safaricom->TransID = $TransID;
        $safaricom->TransactionType = $TransactionType;
        $safaricom->TransTime = $TransTime;
        $safaricom->TransAmount = $TransAmount;
        $safaricom->BusinessShortCode = $BusinessShortCode;
        $safaricom->BillRefNumber = $BillRefNumber;
        $safaricom->InvoiceNumber = $InvoiceNumber;
        $safaricom->MSISDN = $MSISDN;
        $safaricom->First_Name = $First_Name;
        $safaricom->Middle_Name = $Middle_Name;
        $safaricom->Last_Name = $Last_Name;
        $safaricom->OrgAccountBalance = $OrgAccountBalance;
        $safaricom->Request = $Request;
        $safaricom->save();
        return $safaricom;
    }
    public function register_url()
    {
        $url = 'https://api.safaricom.co.ke/mpesa/c2b/v1/registerurl';

        $shortcode = '877838';
        $confirmation_url = 'https://speedball-215310.appspot.com/confirmation';
        $validation_url = 'https://speedball-215310.appspot.com/validation';

        $token = $this->token();
        
        $data = array(
            'ShortCode' => $shortcode,
            'ResponseType' => 'Cancelled',
            'ConfirmationURL' => $confirmation_url,
            'ValidationURL' => $validation_url,
        );
        
        $data_string = json_encode($data);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$token)); 
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

        $curl_response = curl_exec($curl);

        return $curl_response;
    }
    
    public function base_64()
    {
        $key = 'vc4SsAN8ko64jNTY71lOZWvGThoGXASL';
        $secret = 'MVMcOQi4uAOQH73s';
        
        $base64 = base64_encode($key.':'.$secret);

        return $base64;
    }

    public function token()
    {
        $url = 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

        $base64 = $this->base_64();

        $request_headers = array();
        $request_headers[] = 'Authorization: Basic ' . $base64;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $data = curl_exec($ch);

        if (curl_errno($ch))
        {
            $token = '';
        }
        else
        {
            $transaction = json_decode($data, TRUE);
            $token = $transaction['access_token'];            
        }
        
        return $token;
    }
}
