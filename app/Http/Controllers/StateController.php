<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use DataTables;


class StateController extends Controller
{
    public function index()
    {
        return view("state");
    }


    public function get_Country()
    {
        $Country_data = Country::whereNotIn("country_status", [-1])->get()->toArray();
        return   $Country_data;
    }

    public function add_state(Request $request)
    {
        $requestData = $request->all();
        if (empty($requestData['hid'])) {

            $validation = Validator::make($request->all(), [
                'state_name' => 'required|max:15',
                'country_id' => 'required',
                'state_status' => 'required',
            ]);

            if ($validation->fails()) {
                $responce['status'] = 0;
                $responce['msg'] = "plz fill all feild Some Error";
                return $responce;
            }

            if (State::create([
                'country_id' => $requestData['country_id'],
                'state' => $requestData['state_name'],
                'state_status' => $requestData['state_status']
            ])) {
                $responce['status'] = 1;
                $responce['msg'] = "State Added Succsesfully";
                return $responce;
            } else {
                $responce['status'] = 0;
                $responce['msg'] = "Finding Some Error";
                return $responce;
            }
        } else {
            $country_data = State::where("id", $requestData['hid']);
            if ($country_data->update([
                'country_id' => $requestData['country_id'],
                'state' => $requestData['state_name'],
                'state_status' => $requestData['state_status']

            ])) {
                $responce['status'] = 1;
                $responce['msg'] = " Updated Succsesfully";
                return $responce;
            } else {
                $responce['status'] = 0;
                $responce['msg'] = "Finding Some Error";
                return $responce;
            }
        }
    }



    public function state_list(Request $request)
    {

        if ($request->ajax()) {
            $states = DB::table('states')
                ->join('countries', 'states.country_id', '=', 'countries.id')
                ->select('states.*', 'countries.country')
                ->whereNotIn('states.state_status', [-1])
                ->get()->toArray();

            return DataTables::of($states)
                ->addIndexColumn()
                ->addColumn('state_status', function ($row) {

                    $state_status =    ($row->state_status == 0) ? "Active" : "In-Active";
                    return $state_status;
                })
                ->addColumn('action', function ($row) {

                    $btn = "<button class='btn btn-outline-danger' id='del' data-id='" . $row->id . "'><i class='bi bi-trash'></i></button>  <button class='btn btn-outline-success' id='edit' data-id='" . $row->id . "'><i class='bi bi-pencil'></i></button>";

                    return $btn;
                })
                ->rawColumns(['state_status', 'action'])
                ->make(true);
        }

    }



    public function delete_state(Request $request)
    {
        $post =   $request->post();
        $data = State::find($post['id']);
        $data->update([
            "state_status" => -1
        ]);

        $responce['status'] = 0;
        if ($data) {
            $responce['status'] = 1;
            if ($responce['status'] = 1) {
                $responce['mesege'] = "State Deleted ";
            } else {
                $responce['mesege'] = "Data not Deleted";
            }
        } else {
        }

        return $responce;
    }


    public function edit_state(Request $request)
    {
        $post =   $request->post();
        $responce['status'] = 0;
        if ($post['id'] > 0) {
            $states = DB::table('states')
                ->join('countries', 'states.country_id', '=', 'countries.id')
                ->select('states.*', 'countries.country')
                ->where('states.id', '=', $post['id'])
                ->get()->toArray();
            if (!empty($states)) {
                $responce['data'] = $states;
            } else {
                $responce['messege'] = "data not found";
                $responce['status'] = 1;
            }
        } else {
            $responce['messege'] = "somthing gone wrong";
        }
        return $responce;
    }
}
