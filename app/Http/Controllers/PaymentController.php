<?php

namespace App\Http\Controllers;

use App\EJB\PaymentEJB;
use Illuminate\Http\Request;
use App\Models\Installment;
use App\Models\Payment;
use PDF;
use Carbon\Carbon;

class PaymentController extends Controller
{
    protected $paymentEJB;

    public function __construct(PaymentEJB $paymentEJB)
    {
        $this->paymentEJB = $paymentEJB;
    }

    public function create($installment_id)
    {
        $installment = Installment::with(['enrollment', 'payments.payment', 'payments.notes'])->whereId($installment_id)->firstOrFail();
        return view('payments.partials.create')->with('installment', $installment);
    }

    public function store(Request $request)
    {
        return $this->paymentEJB->to_pay_installment($request);
    }

    public function history($installment_id)
    {
        $installment = Installment::with(['payments.notes.user.entity', 'payments.user.entity'])->where('state', null)
            ->whereId($installment_id)

            ->firstOrFail();

        //return $installment;
        return view('payments.partials.history')->with('installment', $installment);
    }

    public function payment_document_pdf()
    {

        $payment = Payment::with(['installment.enrollment.user.entity', 'installment.enrollment.relative.entity', 'installment.enrollment.classroom.level.period', 'payment'])->whereId($_GET['payment_id'])->firstOrFail();

        $customPaper = array(0, 0, 226, 841);
        $pdf = PDF::loadView('payments.payment_document_pdf', ['payment' => $payment])->setPaper($customPaper);
        return $pdf->stream('download.pdf');
    }


    public function users_collection_report()
    {
        return view('reports.users_collection_report.index');
    }

    public function do_users_collection(Request $request)
    {

        $from = Carbon::createFromFormat('d/m/Y H:i:s', $request->from . ' 00:00:00')->toDateTimeString();
        $to = Carbon::createFromFormat('d/m/Y H:i:s', $request->to . ' 23:59:59')->toDateTimeString();
        // $payments=Payment::with('user.entity')->whereBetween('created_at', [$from, $to])->where('type','ticket')->whereDoesntHave('notes')->groupBy('payments.user_id')->get();

        $payments = Payment::with('user.entity')->whereBetween('created_at', [$from, $to])->where('type', 'ticket')->whereDoesntHave('notes')->get();
        $user_payments = $payments->groupBy('user_id');
        // return $payments->groupBy('user_id');
        $users = [];
        $collections = [];
        foreach ($user_payments as $key => $user_payment) {
            $users[] = $user_payment->first()->user;
            $collections[] = $user_payment->sum('amount');
        }

        return view('reports.users_collection_report.partials.listing')->with('users', $users)->with('collections', $collections);
    }


    public function  test()
    {
        $from = Carbon::createFromFormat('d/m/Y', '01/03/2020')->toDateString();
        $to = Carbon::createFromFormat('d/m/Y', '07/05/2020')->toDateString();

        // $payments= Payment::with('user.entity')->where('type','ticket')->whereDoesntHave('notes')->get()->sortBy('user_id');
        $payments = Payment::with('user.entity')->whereBetween('created_at', [$from, $to])->where('type', 'ticket')->whereDoesntHave('notes')->get();
        $user_payments = $payments->groupBy('user_id');
        // return $payments->groupBy('user_id');
        $users = [];
        $collections = [];
        foreach ($user_payments as $key => $user_payment) {
            $users[] = $user_payment->first()->user->entity;
            $collections[] = $user_payment->sum('amount');
        }
        return [$users, $collections];
    }
}
