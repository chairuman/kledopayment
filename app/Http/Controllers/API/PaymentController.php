<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Events\PaymentDeleteProgress;
use App\Http\Resources\PaymentCollection;
use App\Http\Requests\PaymentRequest;
use Illuminate\Support\Facades\Bus;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Jobs\DeletePayment;

class PaymentController extends Controller
{

    /**
     *  function for route GET /api/payments that return all payments data
     */
    public function index()
    {
        // Get all payment from database and order it by created_at
        $payments = Payment::orderBy('created_at', 'desc')->simplePaginate(10);

        // return payments data as a collection
        return (new PaymentCollection($payments));
    }

    /**
     *  funtion for route POST /api/payments that will store a new record of payment
     *  validate form request with App\Requests\PaymentRequest
     *  and will return response true and messsage 'Berhasil menambahkan payment'
     */
    public function store(PaymentRequest $request)
    {
        // If request if validate then create payment data with input body payment_name from request
        $payment = Payment::create([
            'payment_name' => $request->payment_name
        ]);

        // create response that will be return
        $response['status'] = true;
        $response['message'] = 'Berhasil menambahkan payment';

        // return response as json 
        return response()->json($response);
    }

    /**
     * funtion for route DELETE /api/payments with parameter array of payment_id
     * this function will run a queue job and push job progress to pusher
     * also the request if validate with App\Requests\PaymentRequest
     */
    public function delete(PaymentRequest $request)
    {
        // separate request payment_id 
        $id = explode(',', $request->payment_id);
        // initiate a batch job
        $batch = Bus::batch([])->dispatch();

        // looping how many $id to run a job

        for ($i=0; $i < count($id); $i++) { 
            // each $id that looping with run a queue job DeletePayment 
            $batch->add(new DeletePayment($id[$i]));
            
            $number = $i+1;
            if(count($id) != $number){
                $message = 'Telah berhasil menghapus '. $number .' Data';
            }else{
                $message = "Hapus data selesai";
            }
            // after each job is dene then will run a new even PaymentDeleteProgress
            // this even will push progress into pusher
            event(new PaymentDeleteProgress($message));
            
            // set a time sleep for running so the progress not too fast and readable
            sleep(2);
        }

        // create response that will be return when process is done
        $response['status'] = true;
        $response['message'] = 'Berhasil menghapus data';

        // return response as json response
        return response()->json($response);
    }
}
