<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use Illuminate\Http\Request;

class CommissionController extends Controller
{
    public function index()
    {
        $commission = Commission::all();
        return view('admin.commission.list', compact('commission'));
    }

    public function add()
    {
        return view('admin.commission.add');
    }

    public function insert(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|unique:commissions',
            'points' => 'required',
            'gift' => 'required',
            'profit' => 'required',
        ]);

        $commission = new Commission();
        $commission->title = $request->title;
        $commission->gift = $request->gift;
        $commission->points = $request->points;
        $commission->profit = $request->profit;
        $commission->ptp = $request->ptp;

        if ($commission->save()) {
            return redirect()
                ->route('admin.commission.add')
                ->with('success', 'Data is saved successfully');
        } else {
            return redirect()
                ->route('admin.commission.add')
                ->with('error', 'Something went wrong');
        }

    }

    public function edit($id)
    {
        $commission = Commission::findorFail($id);
        return view('admin.commission.edit', compact('commission'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required',
            'points' => 'required',
            'gift' => 'required',
            'profit' => 'required',
        ]);

        $commission = Commission::findorFail($id);
        $commission->title = $request->title;
        $commission->gift = $request->gift;
        $commission->points = $request->points;
        $commission->profit = $request->profit;
        $commission->ptp = $request->ptp;

        if ($commission->save()) {
            return redirect()
                ->route('admin.commission.edit',$id)
                ->with('success', 'Data is updated successfully');
        } else {
            return redirect()
                ->route('admin.commission.edit',$id)
                ->with('error', 'Something went wrong');
        }

    }

    public function delete($id)
    {
        $response = Commission::destroy($id);
        if ($response) {
            $json = [
                'type' => 1,
                'msg' => 'Data is deleted successfully',
            ];
        } else {
            $json = [
                'type' => 0,
                'msg' => 'Something went wrong',
            ];
        }
        return response()->json($json);
    }
}
