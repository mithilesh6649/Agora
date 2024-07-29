<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Classes\AgoraDynamicKey\RtcTokenBuilder;
use App\Models\MeetingEntry;
use App\Models\UserMeeting;
use Session;

class MeetingController extends Controller
{

    public function createMeeting(Request $request)
    {
        $appID = env('AGORA_APP_ID');
        $appCertificate = env('AGORA_APP_CERTIFICATE');
        $channelName = Self::channelName();


        $user = null;
        $role = RtcTokenBuilder::RolePublisher;
        $expireTimeInSeconds = 3600;
        $currentTimestamp = now()->getTimestamp();
        $privilegeExpiredTs = $currentTimestamp + $expireTimeInSeconds;
        $token = RtcTokenBuilder::buildTokenWithUserAccount($appID, $appCertificate, $channelName, $user, $role, $privilegeExpiredTs);


        //Store Meeting Details

        $UserMeeting = new UserMeeting();
        $UserMeeting->title = $request->title;
        $UserMeeting->token = $token;
        $UserMeeting->channel = $channelName;
        $UserMeeting->url = $channelName;
        $UserMeeting->created_by = auth()->user()->id;
        if ($UserMeeting->save()) {
            //Join Meeting.........
            Session::put('owner_id', auth()->user()->id);
            return redirect()->route('join.meeting', ['url' => $UserMeeting->url]);
        }
    }

    public function joinMeeting($url)
    {
        $meeting =  UserMeeting::where('url', $url)->first();
        return view('join_meeting', compact('meeting'));
    }










    function channelName()
    {
        $randCode  = (string)mt_rand(100000, 999999);
        $randChar  = rand(65, 90);
        $randInx   = rand(0, 3);
        $randCode[$randInx] = chr($randChar);
        return 'CH' . $randCode;
    }
}
