<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use DataTables;


class CityController extends Controller
{
    public function index()
    {
        return view('city');
    }

    public function get_State(Request $request)
    {
        $State_data = State::whereNotIn("state_status", [-1])->where("country_id", $request->all()['country_id'])->get()->toArray();
        return   $State_data;
    }

    public function add_city(Request $request)
    {
        $requestData = $request->all();
        if (empty($requestData['hid'])) {

            $validation = Validator::make($request->all(), [
                'country_id' => 'required',
                'state_id' => 'required',
                'city_name' => 'required',
                'city_status' => 'required',
            ]);

            if ($validation->fails()) {
                $responce['status'] = 0;
                $responce['msg'] = "plz fill all feild Some Error";
                return $responce;
            }

            if (City::create([
                'country_id' => $requestData['country_id'],
                'state_id' => $requestData['state_id'],
                'city' => $requestData['city_name'],
                'city_status' => $requestData['city_status']
            ])) {
                $responce['status'] = 1;
                $responce['msg'] = "City Added Succsesfully";
                return $responce;
            } else {
                $responce['status'] = 0;
                $responce['msg'] = "Finding Some Error";
                return $responce;
            }
        } else {
            $city_data = City::where("id", $requestData['hid']);
            if ($city_data->update([
                'country_id' => $requestData['country_id'],
                'state_id' => $requestData['state_id'],
                'city' => $requestData['city_name'],
                'city_status' => $requestData['city_status']

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


    public function city_list(Request $request)
    {
        if ($request->ajax()) {
            $cityData = DB::table('cities')
            ->join('countries', 'cities.country_id', '=', 'countries.id')
            ->join('states', 'cities.state_id', '=', 'states.id')
            ->select('cities.*', 'countries.country', 'states.state')
            ->whereNotIn('cities.city_status', [-1])
            ->get();

            return DataTables::of($cityData)
                ->addIndexColumn()
                ->addColumn('city_status', function ($row) {

                    $city_status =    ($row->city_status == 0) ? "Active" : "In-Active";
                    return $city_status;
                })
                ->addColumn('action', function ($row) {

                    $btn = "<button class='btn btn-outline-danger' id='del' data-id='" . $row->id . "'><i class='bi bi-trash'></i></button>   <button class='btn btn-outline-success' id='edit' data-id='" . $row->id . "'><i class='bi bi-pencil'></i></button>";

                    return $btn;
                })
                ->rawColumns(['city_status', 'action'])
                ->make(true);
        }

    }


    public function delete_city(Request $request)
    {
        $post =   $request->post();
        $data = City::find($post['id']);
        $data->update([
            "city_status" => -1
        ]);
        $responce['status'] = 0;
        if ($data) {
            $responce['status'] = 1;
            if ($responce['status'] = 1) {
                $responce['mesege'] = "City Deleted ";
            } else {
                $responce['mesege'] = "Data not Deleted";
            }
        } else {
        }
        return $responce;
    }

    public function edit_city(Request $request)
    {
        $post =   $request->post();
        $responce['status'] = 0;
        if ($post['id'] > 0) {
            $states = DB::table('cities')
                ->join('countries', 'cities.country_id', '=', 'countries.id')
                ->join('states', 'cities.state_id', '=', 'states.id')
                ->select('cities.*', 'countries.country', 'states.state')
                ->where('cities.id', $post['id'])
                ->get();
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
