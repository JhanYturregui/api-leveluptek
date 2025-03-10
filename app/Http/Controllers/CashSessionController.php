<?php

namespace App\Http\Controllers;

use App\Models\CashSession;
use Illuminate\Http\Request;

class CashSessionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        date_default_timezone_set('America/Lima');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = array();
        $data['date'] = $request->input('date');
        $data['opening_amount'] = $request->input('openingAmount');
        $data['comment'] = mb_strtoupper($request->input('comment'));
        $data['user_id'] = auth()->user()->id;


        try {
            CashSession::create($data);

            $response = [
                'status' => true,
                'message' => 'Registro correcto.',
            ];
        } catch (\Exception $e) {
            $response = ['status' => false, 'message' => $e->getMessage()];
        }

        return json_encode($response);
    }

    /**
     * Close cash session from one user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function close(Request $request)
    {
        $data = array();
        $data['closing_amount'] = $request->input('totalInRegiser');
        $data['real_closing_amount'] = $request->input('totalInRegiser');
        $data['open'] = 0;


        try {
            $cashSession = CashSession::getOpenCashSessionByRole();
            $cashSession->update($data);

            $response = [
                'status' => true,
                'message' => 'Cierre correcto.',
            ];
        } catch (\Exception $e) {
            $response = ['status' => false, 'message' => $e->getMessage()];
        }

        return json_encode($response);
    }
}
