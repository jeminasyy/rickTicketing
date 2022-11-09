<?php

namespace App\Http\Controllers;

use App\Mail\VerifyNew;
use App\Models\Student;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ReopenController extends Controller
{
    // ---- STUDENT VIEWS ----
    // Reopen Ticket
    // Verify email first
    public function inputReopen() {
        return view('email.reopen.index');
    }

    // Send code
    public function emailReopen(Request $request) {
        $code = Str::random(6);
        $find = DB::table('students')->where('email', $request->email)->first();
        
        if ($find) {
            $student = Student::find($find->id);
            if ($student->ongoingTickets >= 3) {
                return view('admin.tickets.limit-reached');
            }
            if($student->tickets > $student->ongoingTickets) {
                $formFields['code'] = $code;
                $student->update($formFields);
                Mail::to($student->email)->send(new VerifyNew($student, $code));
                return redirect('/reopen/code');
            }
            return view('email.reopen.no-tickets');
        }
        return redirect('/reopen/code');
    }

    // Show verify code form
    public function codeReopen(){
        return view('email.reopen.code');
    }

    // Verify email with code
    public function verifyNew(Request $request){
        $find = DB::table('students')->where('code', $request->code)->first();
        if (! $find){
            abort(404, 'Not Found');
        }
        $student = Student::find($find->id);
        if(!$student->FName || !$student->LName || !$student->studNumber){
            return redirect()->route('completeInfo', [$student]);
        }

        return redirect()->route('createTicket', [$student]);
    }
}