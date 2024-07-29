<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\SendNotification;

class SendNotificatoin extends Controller
{
    public function index()
    {
        event(new SendNotification('hello world'));
    }
}
