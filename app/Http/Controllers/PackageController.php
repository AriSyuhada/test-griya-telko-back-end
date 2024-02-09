<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Package;
use Illuminate\Support\Facades\Validator;

class PackageController extends Controller
{
    public function index(Request $request) {
        $packages = Package::all();

        return response()->json(['data' => $packages], Response::HTTP_OK);
    }

    public function show(Request $request, $id) {
        $package = Package::findOrFail($id);

        return response()->json(['data' => $package], Response::HTTP_OK);
    } 

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'prices' => 'required|numeric|gte:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        $package = Package::create([
            'name' => $request->name,
            'prices' => $request->prices,
        ]);

        return response()->json(['data' => $package], Response::HTTP_OK);
    }

    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'prices' => 'required|numeric|gte:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        $package = Package::findOrFail($id);

        $package->update($request->all());

        return response()->json(['data' => $package], Response::HTTP_OK);
    }

    public function destroy(Request $request, $id) {
        $package = Package::findOrFail($id);

        $package->delete();

        return response()->json(['messages' => ['Package data where id = ' . $id . ' id deleted']], Response::HTTP_NO_CONTENT);
    }
}
