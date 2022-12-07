<?php

namespace App\Http\Controllers;
use App\Models\Notifications;
use App\Models\StaffManagement;
use DateTime;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function getNotification(Request $request)
    {
        if($request->role=='Admin/Clerk' || $request->role=='Triage Personnel'){
        $list = Notifications::select('*')
            ->where('branch_id', '=', $request->branch_id)
            ->where('role', '=', $request->role)
            ->orderBy('id', 'DESC')
            ->get()->toArray();

        $count = count($list);
         
        $ab = [];
        if (count($list) > 0) {
            foreach ($list as $key => $value) {

                $datetime1 = new DateTime();
                $datetime12 = new DateTime($value['created_at']);

                if (DATE_FORMAT($datetime12, 'Y-m-d') == date('Y-m-d')) {
                    $ab[$key]['time']  = $datetime1->diff(new DateTime($value['created_at']))->format('%h hours %i minutes');
                } else {
                    $ab[$key]['time']  = $datetime1->diff(new DateTime($value['created_at']))->format('%a days %h hours %i minutes');
                }
                $ab[$key]['id']  = $value['id'];
                $ab[$key]['message']  = $value['message'];
                $ab[$key]['patient_mrn']  = $value['patient_mrn'];
                $ab[$key]['url_route']  = $value['url_route'];

            }
        }
        return response()->json(["message" => "Notifications List", 'list' => $ab, 'notification_count' => $count, "code" => 200]);
    }else{
            $staff_id = StaffManagement::select('id')->where('email',$request->email)->first();
            $list = Notifications::select('*')
                ->where('branch_id', '=', $request->branch_id)
                ->where('staff_id', '=', $staff_id['id'])
                ->orderBy('id', 'DESC')
                ->get()->toArray();
    
            $count = count($list);
             
            $ab = [];
            if (count($list) > 0) {
                foreach ($list as $key => $value) {
    
                    $datetime1 = new DateTime();
                    $datetime12 = new DateTime($value['created_at']);
    
                    if (DATE_FORMAT($datetime12, 'Y-m-d') == date('Y-m-d')) {
                        $ab[$key]['time']  = $datetime1->diff(new DateTime($value['created_at']))->format('%h hours %i minutes');
                    } else {
                        $ab[$key]['time']  = $datetime1->diff(new DateTime($value['created_at']))->format('%a days %h hours %i minutes');
                    }
                    $ab[$key]['id']  = $value['id'];
                    $ab[$key]['message']  = $value['message'];
                    $ab[$key]['patient_mrn']  = $value['patient_mrn'];
                    $ab[$key]['url_route']  = $value['url_route'];
    
                }
            
            return response()->json(["message" => "Notifications List", 'list' => $ab, 'notification_count' => $count, "code" => 200]);

            }
        }
    }


    public function deleteNotification(Request $request)
    {
        $list = Notifications::where('id',$request->notifi_id)->delete();

        return response()->json(["message" => "Notifications Deleted", "code" => 200]);
    }
}
