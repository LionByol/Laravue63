<?php

namespace App\Http\Controllers;

use App\Bill;
use Illuminate\Http\Request;

use App\Http\Requests;

class BillController extends Controller
{
    /**
     * @var Bill
     */
    private $model;

    public function __construct(Bill $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        $bills = $this->model->latest()->get();

        return $bills;
    }

    public function indexPayable()
    {
        $bills = $this->model->where('receivable',0)->latest()->get();

        return $bills;
    }

    public function indexReceivable()
    {
        $bills = $this->model->where('receivable',1)->latest()->get();

        return $bills;
    }

    public function total()
    {
        $toPay = $this->model->whereReceivable(0)->get();
        $totalToPay = $toPay->sum('value');

        $toReceive = $this->model->whereReceivable(1)->get();
        $totalToReceive = $toReceive->sum('value');

        return response()->json(compact('totalToPay','totalToReceive'), 200);
    }

    public function store(Request $request) {
        $result = $this->validate($request, [
            'name' => 'required|min:3|max:255',
            'value' => 'required|numeric|min:0',
            'due_date' => 'required|date'
        ],[
            'name.required' => 'A conta precisa ter um nome',
            'name.min' => 'O nome da conta não pode ter menos que 3 caracteres',
            'name.max' => 'O nome da conta não pode ter mais que 255 caracteres',

            'value.required' => 'O valor é obrigatório',
            'value.min' => 'O valor não pode ser menor que zero',

            'due_date.required' => 'A data é obrigatória',
            'due_date.date' => 'Data inválida'
        ]);

        $bill = $this->model->create($request->all());

        if ($request->ajax()) {
            return response()->json($bill,200);
        }

        return $bill;
    }

    public function edit(Request $request, $id)
    {
        $bill = $this->model->find($id);

        if ($request->ajax()) {
            return response()->json($bill,200);
        }

        return $bill;

    }

    public function update(Request $request, $id)
    {
        $result = $this->validate($request, [
            'name' => 'required|min:3|max:255',
            'value' => 'required|numeric|min:0',
            'due_date' => 'required|date'
        ]);

        $bill = $this->model->find($id);
        $bill->update($request->all());

        if ($request->ajax()) {
            return response()->json($bill,200);
        }

        return $bill;
    }

    public function pay(Request $request, $id)
    {
        $bill = $this->model->find($id);
        $bill->done = 1;
        $bill->save();

        if ($request->ajax()) {
            return response()->json($bill,200);
        }

        return $bill;

    }

    public function unpay(Request $request, $id)
    {
        $bill = $this->model->find($id);
        $bill->done = 0;
        $bill->save();

        if ($request->ajax()) {
            return response()->json($bill,200);
        }

        return $bill;
    }

    public function destroy($id)
    {
        $this->model->find($id)->delete();

        return response()->json('Conta Apagada',200);
    }
}
