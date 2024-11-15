<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Coworking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ReservationController extends Controller
{
    /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct() {
    $this->middleware('auth');
    date_default_timezone_set('America/Lima');
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index() {
    $coworkings = Coworking::get();
    return view('pages.reservations.index', compact('coworkings'));
  }

  /**
  * Get data to show in datatables
  *
  * @return json
  */
  public function getData(Request $request) {
    $idCoworking = intval($request->input('idCoworking'));
    $userRole = auth()->user()->role;

    $query = DB::table('reservations as r')
                  ->join('users as u', 'r.id_user', 'u.id')
                  ->join('coworkings as c', 'r.id_coworking', 'c.id')
                  ->select('r.id', 'r.id_user', 'r.id_coworking', 'r.start_date', 'r.end_date', 'r.state', 'u.name as user_name', 'c.name as coworking_name')
                  ->when($userRole !== config('constants.USER_ROLE_ADMIN'), function($query) {
                    return $query->where('r.id_user', auth()->user()->id);
                  })
                  ->when($idCoworking !== 0, function($query) use ($idCoworking) {
                    return $query->where('r.id_coworking', $idCoworking);
                  })
                  ->orderBy('r.id', 'desc');

    return datatables()
      ->queryBuilder($query)
      ->addColumn('col-state', 'pages.reservations.columns.state')
      //->addColumn('col-actions', 'pages.reservations.columns.actions')
      ->addColumn('col-action-change-state', 'pages.reservations.columns.action-change-state')
      ->rawColumns(['col-state', 'col-action-change-state'])
      ->toJson();
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create() {
    $coworkings = Coworking::get();
    return view('pages.reservations.create', compact('coworkings'));
  }

  /**
   * Function to check is a coworking is available
   * 
   */
  public function checkAvailability($idCoworking, $startDate, $endDate) {
    $exists = Reservation::where('id_coworking', $idCoworking)
                          ->where(function ($query) use ($startDate, $endDate) {
                            $query->where(function ($q) use ($startDate, $endDate) {
                                $q->where('start_date', '<', $endDate)
                                  ->where('end_date', '>', $startDate);
                            })
                            ->orWhere(function ($q) use ($startDate, $endDate) {
                                $q->where('start_date', '<=', $startDate)
                                  ->where('end_date', '>=', $endDate);
                            });
                          })
                          ->exists();

    return $exists;
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request) {
    if (auth()->user()->role !== config('constants.USER_ROLE_CUSTOMER')) {
      $response = [
        'status' => false,
        'message' => 'Rol no permitido para generar esta acción.',
      ];
      return json_encode($response);
    }
    $idCoworking = $request->input('idCoworking');
    $startDate = $request->input('startDate');
    $endDate = $request->input('endDate');
    $data = array();
    $data['id_coworking'] = $idCoworking;
    $data['id_user'] = auth()->user()->id;
    $data['start_date'] = $startDate;
    $data['end_date'] = $endDate;

    $checkReservation  = $this->checkAvailability($idCoworking, $startDate, $endDate);
    if ($checkReservation) {
      $response = [
        'status' => false,
        'message' => 'La sala ya se encuentra reservada en ese horario.',
      ];
      return json_encode($response);
    }

    try {
      $reservation = Reservation::create($data);

      $response = [
        'status' => true,
        'message' => 'Registro correcto.',
      ];

    } catch (Exception $e) {
      $response = ['status' => false, 'message' => $e->getMessage()];
    }

    return json_encode($response);
  }

  /**
   * Change state to a reservation.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function changeState(Request $request){
    if (auth()->user()->role !== config('constants.USER_ROLE_ADMIN')) {
      $response = [
        'status' => false,
        'message' => 'Rol no permitido para generar esta acción.',
      ];
      return json_encode($response);
    }

    $id = $request->input('id');
    $data = array();
    $data['state'] = $request->input('state');

    try {
      $reservation = Reservation::find($id);
      $reservation->update($data);
      $response = ['status' => true, 'message' => 'Estado cambiado forma correcta.'];

    } catch (Exception $e) {
      $response = ['status' => false, 'message' => $e->getMessage()];
    }

    return json_encode($response);
  }
}
