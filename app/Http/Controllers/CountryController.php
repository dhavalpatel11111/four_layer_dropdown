<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DataTables;


class CountryController extends Controller
{
    public function index()
    {
        return view('country');
    }

    public function add_country(Request $request)
    {

        $requestData = $request->all();

        if (empty($requestData['hid'])) {
            $validation = Validator::make($request->all(), [
                'country_name' => 'required|max:15',
                'country_status' => 'required',
            ]);

            if ($validation->fails()) {
                $responce['status'] = 0;
                $responce['msg'] = "plz fill Valid Value";
                return $responce;
            }
            if (Country::create([
                'country' => $requestData['country_name'],
                'country_status' => $requestData['country_status']
            ])) {
                $responce['status'] = 1;
                $responce['msg'] = "Country Added Succsesfully";
                return $responce;
            } else {
                $responce['status'] = 0;
                $responce['msg'] = "Finding Some Error";
                return $responce;
            }
        } else {

            $validation = Validator::make($request->all(), [
                'country_name' => 'max:15',
            ]);

            if ($validation->fails()) {
                $responce['status'] = 0;
                $responce['msg'] = "plz fill Valid Value";
                return $responce;
            }

            $country_data = Country::where("id", $requestData['hid']);
            if ($country_data->update([
                'country' => $requestData['country_name'],
                'country_status' => $requestData['country_status']

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


    public function country_list( Request  $request)
    {

        if ($request->ajax()) {
            $alldata = Country::whereNotIn("country_status", [-1])->get()->toArray();

            return DataTables::of($alldata)
                ->addIndexColumn()
                ->addColumn('country_status', function ($row) {

                 $country_status =    ($row['country_status'] == 0) ? "Active" : "In-Active";
                 return $country_status;
                })
                ->addColumn('action', function ($row) {

                    $btn = "<button class='btn btn-outline-danger' id='del' data-id='" . $row['id'] . "'><i class='bi bi-trash'></i></button>   <button class='btn btn-outline-success' id='edit' data-id='" . $row['id'] . "'><i class='bi bi-pencil'></i></button>";

                    return $btn;
                })
                ->rawColumns(['country_status' , 'action'])
                ->make(true);
        }
    }

    public function delete_country(Request $request)
    {
        $post =   $request->post();
        $data = Country::find($post['id']);
        $data->update([
            "country_status" => -1
        ]);
        $responce['status'] = 0;
        if ($data) {
            $responce['status'] = 1;
            if ($responce['status'] = 1) {
                $responce['mesege'] = "Data Deleted ";
            } else {
                $responce['mesege'] = "Data not Deleted";
            }
        } else {
        }

        return $responce;
    }




    public function edit_country(Request $request)
    {
        $post =   $request->post();
        $responce['status'] = 0;
        if ($post['id'] > 0) {
            $data = Country::find($post['id']);
            if (!empty($data)) {
                $responce['data'] = $data;
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
