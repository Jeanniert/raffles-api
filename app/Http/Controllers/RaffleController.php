<?php

namespace App\Http\Controllers;

use App\Models\Raffle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RaffleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $raffle= Raffle::select('raffles.*')->paginate(10);
        return response()->json($raffle);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'identification_number' => 'required|string',
            'name' => 'required|string',
            'abono' => 'required|string',
            'buying_date' => 'date_format:Y-m-d|max:10|nullable',
            'numbers' => 'required',
            'methodOfPayment'=>'required|string',
            'responsible'=>'required|string',
        ];

        $validator= Validator::make($request->input(), $rules);
        if($validator->fails()){
            return response()->json([
                'status'=> false,
                'errors'=> $validator->errors()->all()
            ], 400);
        }

        $valores= Raffle::all();

        $requestArray= collect($request->numbers)->flatten()->toArray();
        $bdArray= collect($valores)->pluck('numbers')->flatten()->toArray();

        $comunes= collect($requestArray)->intersect($bdArray)->toArray();
        $comun= json_encode($comunes);

        if (empty($comunes)) {

            $raffle= new Raffle();
            $raffle->identification_number = $request->input('identification_number');
            $raffle->name = $request->input('name');
            $raffle->abono = $request->input('abono');
            $raffle->buying_date = $request->input('buying_date');
            $raffle->methodOfPayment = $request->input('methodOfPayment');
            $raffle->numbers = $request->input('numbers');
            $raffle->responsible= $request->input('responsible');
            if($request->reference) {
                $raffle->reference= $request->input('reference');
            }
            $raffle->save();
            return response()->json([
                'status'=> true,
                'message'=> 'Compra de numero exitosa!',
                'data'=> $raffle
            ],200);

        }else{

            return response()->json([
                'status'=> false,
                'message'=> 'Numero ya vendido '.$comun
            ],200);

        }
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $rules = [
            'identification_number' => 'required|string',
            'name' => 'required|string',
            'abono' => 'required|string',
            'buying_date' => 'date_format:Y-m-d|max:10|nullable',
            'numbers' => 'required',
            'methodOfPayment'=>'required|string',
            'responsible'=>'required|string',
        ];

        $validator= Validator::make($request->input(), $rules);
        if($validator->fails()){
            return response()->json([
                'status'=> false,
                'errors'=> $validator->errors()->all()
            ], 400);
        }

        //$raffle =Raffle::findOrFail($id)->update($request->input());
            $raffle = Raffle::find($id);
            $raffle->identification_number = $request->input('identification_number');
            $raffle->name = $request->input('name');
            $raffle->abono = $request->input('abono');
            $raffle->buying_date = $request->input('buying_date');
            $raffle->methodOfPayment = $request->input('methodOfPayment');
            $raffle->numbers = $request->input('numbers');
            $raffle->responsible= $request->input('responsible');
            if($request->reference) {
                $raffle->reference= $request->input('reference');
            }
            $raffle->update();
            return response()->json([
                'status'=> true,
                'message'=> 'Actualizacion exitosa!',
                'data'=> $raffle
            ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Raffle::find($id)->delete();
        return response()->json([
            'status'=> true,
            'message'=> 'Eliminación exitosa'
        ], 200);
    }

    public function balance(){

        $raffle= Raffle::selectRaw('methodOfPayment, sum(abono) as total')
        ->groupBy('methodOfPayment')
        ->paginate(10);;
        return response()->json($raffle);
    }

    public function numberValidate(){

        $valores= Raffle::all();    
        $bdArray= collect($valores)->pluck('numbers')->flatten()->toArray();
        return response()->json($bdArray);
    }
}
