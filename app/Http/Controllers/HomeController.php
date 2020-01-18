<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Instruments;
use App\FTicket;
use App\ETicket;

class HomeController extends Controller
{
    public $defaultAction = 'home';
    public $defaultTitle  = '首頁';

    public function home()
    {
        $params = ['title' => $this->defaultTitle];

        if (Auth::check()) {
            $params['user_name'] = Auth::user()->name;
        } else {
            return redirect()->to('/login');
        }

        return view('index', $params);
    }

    public function dashboard()
    {
        $params = ['title' => 'Dashboard'];
        $instrument_list = [];

        if (Auth::check()) {
            $params['user_name'] = Auth::user()->name;
        } else {
            return redirect()->to('/login');
        }
        $instru = Instruments::where('event_code', '20_themovements')->orderBy('instru_no')->get();

        foreach ($instru as $value) {
            $instrument_list[$value->instru_no] = $value->instru_name;
        }
        ksort($instrument_list);
        $params['instru'] = $instrument_list;

        return view('home.dashboard', $params);
    }

    public function checkoutTicket(Request $request)
    {
        if ($request->ajax() && $request->isMethod('get')) {
            $code = $request->input('code');

            switch (substr($code, 0, 1)) {
                case 'F':
                    return response()->json($this->updateFticket($code));
                    break;
                case 'E':
                    return response()->json($this->updateETicket($code));
                    break;
            }
        }
    }

    public function updateFticket($code)
    {
        $result = FTicket::where('event_code', '20_themovements')->where('get_tickets_code', $code)->where('status', 0)->update(['status' => 1, 'get_time' => date('Y-m-d H:i:s', time())]);

        if ($result) {
            return ['status' => true];
        } else {
            return ['status' => false, 'error' => $result];
        }
    }

    public function updateETicket($code)
    {
        $result = ETicket::where('event_code', '20_themovements')->where('code', $code)->where('status', 0)->update(['status' => 1, 'get_time' => date('Y-m-d H:i:s', time())]);

        if ($result) {
            return ['status' => true];
        } else {
            return ['status' => false, 'error' => $result];
        }
    }

    public function geteTicketByCode(Request $request)
    {
        if ($request->ajax() && $request->isMethod('post')) {
            $qrcode = $request->input('eticket_qrcode');
            $code = base64_decode($qrcode);

            $result = ETicket::where('event_code', '20_themovements')->where('code', $code)->get();

            if (count($result)) {
                $result = $result[0];
                return ['data' => [
                    'code' => $code,
                    'name' => $result->name,
                    'status' => $result->status,
                    'get_time' => $result->get_time,
                    'email' => $result->email,
                    'phone' => $result->phone,
                    'amount' => $result->number
                ]];
            }

            return ['error' => true];
        }
    }

    public function getfTicketByCode(Request $request)
    {
        if ($request->ajax() && $request->isMethod('post')) {
            $code = $request->input('codeQuery');

            $result = FTicket::where('event_code', '20_themovements')->where('get_tickets_code', $code)->get();

            if (count($result)) {
                return ['data' => [
                    'code'        => $code,
                    'ticketsName' => $result[0]->tickets_name,
                    'amount'      => $result[0]->number,
                    'get_time'    => $result[0]->get_time,
                    'contact'     => $result[0]->contact,
                    'status'      => $result[0]->status
                ]];
            }

            return ['error' => true];
        }
    }

    public function geteTicket(Request $request)
    {
        if ($request->ajax() && $request->isMethod('post')) {
            $search_mail = $request->input('advancedTicketEmail');
            $search_phone = $request->input('advancedTicketPhone');

            $response = [];

            $result = ETicket::where('event_code', '20_themovements')->where('email', $search_mail)->orWhere('phone', $search_phone)->orderBy('email')->get();

            foreach ($result as $tickets) {
                $response['data'][] = [
                    'code' => $tickets->code,
                    'email' => $tickets->email,
                    'phone' => $tickets->phone,
                    'status' => $tickets->status,
                    'get_time' => $tickets->get_time,
                    'amount' => $tickets->number,
                    'name' => $tickets->name
                ];
            }

            return response()->json($response);
        }
    }

    public function getfTicket(Request $request)
    {
        if ($request->ajax() && $request->isMethod('post')) {
            $instrument = $request->input('instrument');
            $memberName = ($instrument > 13) ? $request->input('otherName', -1) : $request->input('memberName', -1);

            $response = [];

            if ($memberName > 0) {
                $result = FTicket::where('event_code', '20_themovements')->where('instrument_category', $instrument)->where('member_name', $this->getMembers($instrument, $memberName))->orderBy('id')->get();
            } else {
                $result = FTicket::where('event_code', '20_themovements')->where('instrument_category', $instrument)->orderBy('get_tickets_code')->get();
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
                    'amount'       => $tickets->number,
                    'status'       => (int)$tickets->status,
                    'get_time'     => $tickets->get_time,
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
            return view('home.f-ticket-create', $params + ($instrument && $memberName ? [] : ['message' => '新增寄票需要選擇樂器與團員姓名']));
        } else if ($request->ajax() && $request->isMethod('post')) {
            $instrument = $request->input('fTicket.instrument');
            $memberNum = $request->input('fTicket.memberName', -1);

            if ($memberNum > 0) {
                $memberName = $this->getMembers($instrument, $memberNum);
                $contact = $request->input('fTicket.contact');
                $ticketsName = $request->input('fTicket.ticketsName');
                $amount = $request->input('fTicket.amount');
                $fTicketsByUser = FTicket::where('event_code', '20_themovements')->where('instrument_category', $instrument)->where('member_name', $memberName)->orderBy('get_tickets_code', 'desc')->get();
                if (count($fTicketsByUser)) {
                    $fTicketCountOfMember = (int)substr($fTicketsByUser[0]->get_tickets_code, -2)+1;
                } else {
                    $fTicketCountOfMember = 1;
                }
                $getTicketCode = 'F' . str_pad($instrument, 2, '0', STR_PAD_LEFT) . str_pad($request->input('fTicket.memberName'), 2, '0', STR_PAD_LEFT) . str_pad($fTicketCountOfMember, 2, '0', STR_PAD_LEFT);
                DB::table('fticket')->insert([
                    'event_code' => '20_themovements',
                    'instrument_category' => $instrument,
                    'member_name' => $memberName,
                    'tickets_name' => $ticketsName,
                    'contact' => $contact,
                    'number' => $amount,
                    'status' => 0,
                    'get_tickets_code' => $getTicketCode
                ]);

                $message = '新增成功: ' . $getTicketCode;
            } else {
                $message = '新增寄票需要選擇團員姓名';
            }
            return view('home.f-ticket-create', ['action' => 'post', 'message' => $message]);
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
