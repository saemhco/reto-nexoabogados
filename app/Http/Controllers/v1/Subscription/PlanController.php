<?php

namespace App\Http\Controllers\v1\Subscription;

use App\Http\Controllers\Controller;
use App\Http\Requests\Plan\Store;
use App\Http\Requests\Plan\Update;
use App\Models\Plan;

class PlanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api']);
        $this->middleware('can:plan.index')->only('index');
        $this->middleware('can:plan.store')->only('store');
        $this->middleware('can:plan.show')->only('show');
        $this->middleware('can:plan.update')->only('update');
        $this->middleware('can:plan.destroy')->only('destroy');
    }

    public function index()
    {
        $data = Plan::filters()->get();
        return $this->showAll($data);
    }
    public function store(Store $request)
    {
        $data = Plan::create($request->input());
        return $this->showMessage("Datos registrados correctamente", $data, 201);
    }
    public function show(Plan $plan)
    {
        return $this->showOne($plan);
    }
    public function update(Update $request, Plan $plan)
    {
        $plan->update($request->input());
        return $this->showMessage("Se actualizó correctamente", $plan->fresh(), 200);
    }
    public function destroy(Plan $plan)
    {
        return $this->delete($plan);
    }
}
