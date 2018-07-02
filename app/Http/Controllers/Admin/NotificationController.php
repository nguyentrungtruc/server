<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
	public function __construct() {
		$this->middleware('auth:api');
	}
	// READ NOTIFICATION
	public function readNotification(Request $request) {
		$notification = auth()->user()->unreadNotifications->where('id', '=', $request->id);
		$notification->markAsRead();
		$res = [
			'type'    => 'success',
			'message' => 'Read notification successfully.',
			'data'    => $notification
		];
		return response($res, 200);
	}
	// GET NOTIFICATION
	public function getNotification(Request $request) {
		if($request->confirm) {
			$array = [];
			foreach(auth()->user()->notifications as $notification) {
				array_push($array, $notification->data);
			}

			$res = [
				'type'    => 'success',
				'message' => 'Get notification successfully.',
				'data'    => auth()->user()->notifications
			];
			return response($res, 200);
		}		
	}
	// GET UNREAD NOTIFICATION
	public function getUnreadNotification(Request $request) {
		if($request->confirm) {
			
		}
		$notifications = auth()->user()->unreadNotifications;
		$res = [
			'type'    => 'success',
			'message' => 'Get notification successfully.',
			'data'    => $notifications
		];
		return response($res, 200);
	}
}
