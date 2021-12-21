<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Report;
use App\Models\Token;

class ReportController extends Controller
{
    //
    public function createreport(Request $req){
        $token = Token::where('token', $req->header('token'))->where('expired', false)->first();
        $report = new Report();
        $report->post_id = $req->post_id;
        $report->user_id = $token->user_id;
        $report->desc = $req->desc;

        if($report->save()){
            return response()->json([
                'message'=> "Your report submitted successfully"
            ]);
        }
    }

    public function reports(Request $req){
        $reports = Report::with('post')->with('user')->orderBy('created_at', 'DESC')->get();
        if($reports){
            return response()->json([
                'reports'=> $reports
            ]);
        }
    }
}
