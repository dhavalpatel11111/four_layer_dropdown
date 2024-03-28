<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use DataTables;


class AreaController extends Controller
{
    public function index()
    {
        return view("area");
    }

    public function get_City(Request $request)
    {
        $City_data = City::whereNotIn("city_status", [-1])->where("state_id", $request->all()['state_id'])->get()->toArray();
        return   $City_data;
    }


    public function add_area(Request $request)
    {
        $requestData = $request->all();

        if (empty($requestData['hid'])) {

            $validation = Validator::make($request->all(), [
                'country_id' => 'required',
                'state_id' => 'required',
                'city_id' => 'required',
                'area_name' => 'required',
                'area_status' => 'required',
            ]);

            if ($validation->fails()) {
                $responce['status'] = 0;
                $responce['msg'] = "plz fill all feild Some Error";
                return $responce;
            }

            if (Area::create([
                'country_id' => $requestData['country_id'],
                'state_id' => $requestData['state_id'],
                'city_id' => $requestData['city_id'],
                'area' => $requestData['area_name'],
                'area_status' => $requestData['area_status']
            ])) {
                $responce['status'] = 1;
                $responce['msg'] = "Area Added Succsesfully";
                return $responce;
            } else {
                $responce['status'] = 0;
                $responce['msg'] = "Finding Some Error";
                return $responce;
            }
        } else {
            $city_data = Area::where("id", $requestData['hid']);
            if ($city_data->update([
                'country_id' => $requestData['country_id'],
                'state_id' => $requestData['state_id'],
                'city_id' => $requestData['city_id'],
                'area' => $requestData['area_name'],
                'area_status' => $requestData['area_status']

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


    public function area_list(Request $request)
    {
        if ($request->ajax()) {
            $areas = DB::table('areas')
            ->join('countries', 'areas.country_id', '=', 'countries.id')
            ->join('states', 'areas.state_id', '=', 'states.id')
            ->join('cities', 'areas.city_id', '=', 'cities.id')
            ->select('areas.*', 'countries.country', 'states.state', 'cities.city')
            ->whereNotIn('areas.area_status', [-1])
            ->get()->toArray();

            return DataTables::of($areas)
                ->addIndexColumn()
                ->addColumn('area_status', function ($row) {

                    $area_status =    ($row->area_status == 0) ? "Active" : "In-Active";
                    return $area_status;
                })
                ->addColumn('action', function ($row) {

                    $btn = "<button class='btn btn-outline-danger' id='del' data-id='" . $row->id . "'><i class='bi bi-trash'></i></button>  <button class='btn btn-outline-success' id='edit' data-id='" . $row->id . "'><i class='bi bi-pencil'></i></button>";

                    return $btn;
                })
                ->rawColumns(['area_status', 'action'])
                ->make(true);
        }

    }


    public function delete_area(Request $request)
    {
        $post =   $request->post();
        $data = Area::find($post['id']);
        $data->update([
            "area_status" => -1
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




    public function edit_area(Request $request)
    {
        $post =   $request->post();

        $responce['status'] = 0;
        if ($post['id'] > 0) {
            $area = DB::table('areas')
                ->join('countries', 'areas.country_id', '=', 'countries.id')
                ->join('states', 'areas.state_id', '=', 'states.id')
                ->join('cities', 'areas.city_id', '=', 'cities.id')
                ->select('areas.*', 'countries.country', 'states.state', 'cities.city')
                ->where('areas.id', '=', $post['id'])
                ->get()->toArray();

            if (!empty($area)) {
                $responce['data'] = $area;
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
