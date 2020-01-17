<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Instruments;
use App\FTicket;
use App\MTicket;
use App\ETicket;

class HomeController extends Controller
{
    public $defaultAction = 'home';
    public $defaultTitle  = '首頁';

    public function home()
    {
        $params = ['title' => $this->defaultTitle];

        if (Auth::id()) {
            $params['user_name'] = Auth::user()->name;
        }

        return view('index', $params);
    }

    public function dashboard()
    {
        $params = ['title' => 'Dashboard'];

        if (Auth::id()) {
            $params['user_name'] = Auth::user()->name;
        }
        $instru = Instruments::where('event_code', '20_themovements')->orderBy('id')->get();
        $params['instru'] = $instru;
        // $params
        return view('home.dashboard', $params);
    }

    public function getmTicket(Request $request)
    {
        if ($request->ajax() && $request->isMethod('post')) {
            $instrument = $request->input('instrument');
            $memberName = ($instrument > 13) ? $request->input('otherName', -1) : $request->input('memberName', -1);

            $response = [];

            if ($memberName > 0) {
                $result = FTicket::where('event_code', '20_themovements')->where('instrument_category', $instrument)->where('status', 1)->where('member_name', $this->getMembers($instrument, $memberName))->orderBy('id')->get();
            } else {
                $result = FTicket::where('event_code', '20_themovements')->where('instrument_category', $instrument)->where('status', 1)->orderBy('get_tickets_code')->get();
            }

            $response['num'] = count($result);
            $response['data'] = [];
            $count = 0;

            foreach ($result as $tickets) {
                $response['data'][] = [
                    'code'         => $tickets->get_tickets_code,
                    'member'       => $tickets->member_name,
                    'contact'      => $tickets->contact,
                    'tickets_name' => $tickets->tickets_name,
                    'amount'       => $tickets->number
                ];

                $count += (int)$tickets->number;
            }

            $response['count'] = $count;

            return response()->json($response);
        }
    }

    public function createForm(Request $request)
    {
        if ($request->ajax() && $request->isMethod('get')) {
            $instrument = $request->input('instrument', -1);
            $memberName = $request->input('memberName', -1);
            $params = ['action' => 'get', 'instrument' => $instrument, 'memberName' => $memberName];
            return view('home.m-ticket-create', $params + ($instrument && $memberName ? [] : ['message' => '新增寄票需要選擇樂器與團員姓名']));
        } else if ($request->ajax() && $request->isMethod('post')) {
            $instrument = $request->input('mTicket.instrument');
            $memberNum = $request->input('mTicket.memberName', -1);

            if ($memberNum > 0) {
                $memberName = $this->getMembers($instrument, $memberNum);
                $contact = $request->input('mTicket.contact');
                $ticketsName = $request->input('mTicket.ticketsName');
                $amount = $request->input('mTicket.amount');
                $fTicketCountOfMember = FTicket::where('event_code', '20_themovements')->where('instrument_category', $instrument)->where('member_name', $memberName)->count();
                $getTicketCode = 'F' . str_pad($instrument, 2, '0', STR_PAD_LEFT) . str_pad($request->input('mTicket.memberName')+1, 2, '0', STR_PAD_LEFT) . str_pad($fTicketCountOfMember+1, 2, '0', STR_PAD_LEFT);
                DB::table('fticket')->insert([
                    'event_code' => '20_themovements',
                    'instrument_category' => $instrument,
                    'member_name' => $memberName,
                    'tickets_name' => $ticketsName,
                    'contact' => $contact,
                    'number' => $amount,
                    'status' =>  1,
                    'get_tickets_code' => $getTicketCode
                ]);

                $message = '新增成功: ' . $getTicketCode;
            } else {
                $message = '新增寄票需要選擇團員姓名';
            }
            return view('home.m-ticket-create', ['action' => 'post', 'message' => $message]);
        }
    }

    public function getMembers($instru=null, $num=-1)
    {
        $members = [
            '1' => ['江銨畯', '魯汝慧', '姚俐安', '柯穎穎'],
            '2' => ['周芳如'],
            '3' => ['連姵璇', '連姵瑜', '彭泰之', '彭霽維', '林家寅', '趙偉陵'],
            '4' => ['塗永鈞'],
            '5' => ['洪銘陽'],
            '6' => ['許墨筑', '姚庭軒', '謝維漢', '林妤蒨'],
            '7' => ['黃雅琪', '黃華譽', '羅大晏'],
            '8' => ['黃思瑀', '陳宇聖', '王姵文', '邱詩容'],
            '9' => ['謝維藩', '許瀞文', '羅雅馨'],
            '10' => ['陳韋愷'],
            '11' => ['黃騰寬', '林承儒'],
            '12' => ['朱家恆', '李柏叡', '王敏安', '王郁文'],
            '13' => ['許芳寧']
        ];

        if ($instru) {
            return ($num <= 0) ? $members[(string)$instru] : $members[(string)$instru][(int)$num-1];
        } else {
            return $members;
        }
    }

    public function fTicketDel()
    {
        //
    }

    public function fTicketSave()
    {
        //
    }
}
