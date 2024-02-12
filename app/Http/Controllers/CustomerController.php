<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Customer;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class CustomerController extends Controller
{
    public function index(Request $request) {
        $customers = Customer::all();

        return response()->json(['data' => $customers], Response::HTTP_OK);
    }

    public function show(Request $request, $id) {
        $customer = Customer::findOrFail($id);

        return response()->json(['data' => $customer], Response::HTTP_OK);
    } 

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'package_id' => 'required',
            'name' => 'required',
            'phone_number' => 'required',
            'address' => 'required',
            'id_card_pict' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails() || !$request->file('id_card_pict') || !$request->file('house_pict')) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        $customer = Customer::create([
            'package_id' => $request->package_id,
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'verified' => false,
        ]);

        $id_card_pict = $request->file('id_card_pict');
        $id_card_path = $id_card_pict->storeAs('customer/' . $customer->id . '/id_card_pict.' . $id_card_pict->extension(), ['disk' => 'public']);
        
        $house_pict = $request->file('house_pict');
        $house_path = $house_pict->storeAs('customer/' . $customer->id . '/house_pict.' . $house_pict->extension(), ['disk' => 'public']);
        
        $customer->id_card_pict_path = Storage::url($id_card_path);
        $customer->house_pict_path = Storage::url($house_path);

        $customer->save();

        return response()->json(['data' => $customer], Response::HTTP_OK);
    }

    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'package_id' => 'required',
            'name' => 'required',
            'phone_number' => 'required',
            'address' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        $customer = Customer::findOrFail($id);

        $id_card_pict = null;
        $id_card_path = $customer->id_card_pict_path;
        $house_pict = null;
        $house_path = $customer->house_pict_path;

        if ($request->file('id_card_pict')) {
            $id_card_pict = $request->file('id_card_pict');
            $id_card_path = $id_card_pict->storeAs('customer/' . $customer->id . '/id_card_pict.' . $id_card_pict->extension(), ['disk' => 'public']);
        }

        if ($request->file('house_pict')) {
            $house_pict = $request->file('house_pict');
            $house_path = $house_pict->storeAs('customer/' . $customer->id . '/house_pict.' . $house_pict->extension(), ['disk' => 'public']);
        }
        

        $customer->update([
            'package_id' => $request->package_id,
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'id_card_pict_path'=> Storage::url($id_card_path),
            'house_pict_path' => Storage::url($house_path),
        ]);

        return response()->json(['data' => $customer], Response::HTTP_OK);
    }

    public function destroy(Request $request, $id) {
        $customer = Customer::findOrFail($id);

        $customer->delete();

        return response()->json(['messages' => ['Customer data where id = ' . $id . ' id deleted']], Response::HTTP_NO_CONTENT);
    }

    public function verif(Request $request, $id) {
        $customer = Customer::findOrFail($id);

        $customer->update([
            'verified' => true,
        ]);

        return response()->json(['data' => $customer], Response::HTTP_OK);
    }

    public function file(Request $request, $id, $file) {
        $isFileValid = $file == 'house' || $file == 'id_card';

        if (!$isFileValid) {
            return response()->json(['errors' => ['file' => ['File requested is not valid']]]);
        }

        $customer = Customer::findOrFail($id);

        $filePath = $customer[$file . '_pict_path'];

        // return asset($filePath);

        return response()->file(public_path($filePath));
    }
}
