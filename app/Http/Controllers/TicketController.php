<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{

    public function add()
    {
        return view('user.ticket.add');
    }

    public function insert(Request $request)
    {
        $this->validate($request, [
            'subject' => ['required'],
            'content' => ['required'],
        ]);

        DB::beginTransaction();
        $ticket = new Ticket();
        $ticket->user_id = Auth::guard('web')->user()->id;
        $ticket->title = $request->subject;
        $ticket->priority = 0;
        $ticket->status = 0; // 0 opening, 1 closed
        $ticket->user_type = 1; //  0 admin , 1 user
        $responseticket = $ticket->save();
        $ticketid = $ticket->id;

        // ticket detail insert
        $ticketdetail = new TicketDetail();
        $ticketdetail->ticket_id = $ticketid;
        $ticketdetail->from_id = Auth::guard('web')->user()->id;
        // $ticketdetail->to_id  = $ticketid;
        $ticketdetail->message = $request->content;
        $ticketdetail->user_type = '0';
        $resposeticketdetail = $ticketdetail->save();

        if ($responseticket && $resposeticketdetail) {
            DB::commit();
            return redirect()->route('ticket.add')->with('success', "Ticket is created successfully");
        } else {
            DB::rollback();
            return redirect()->route('ticket.add')->with('error', "Something went wrong please try again");
        }
    }

    public function index()
    {
        $ticket = Ticket::where('user_id', Auth::guard('web')->user()->id)->where('user_type', '1')->orderBy('id', 'DESC')->get();
        return view('user.ticket.list', compact('ticket'));
    }

    public function delete($id)
    {
        $response = Ticket::destroy($id);
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

    public function detail($id)
    {
        $ticket = Ticket::findOrFail($id);
        return view('user.ticket.detail', [
            'ticket' => $ticket,
        ]);
    }

    public function reply($id)
    {
        $ticket = Ticket::findOrFail($id);
        return view('user.ticket.reply', [
            'ticket' => $ticket,
        ]);
    }

    public function replyInsert(Request $request, $id)
    {
        $this->validate($request, [
            'subject' => ['required'],
            'content' => ['required'],
        ]);
        $ticketid = $id;
        $ticketdetail = new TicketDetail();
        $ticketdetail->ticket_id = $ticketid;
        $ticketdetail->from_id = Auth::guard('web')->user()->id;
        // $ticketdetail->to_id  = $ticketid;
        $ticketdetail->message = $request->content;
        $ticketdetail->user_type = '0';
        $resposeticketdetail = $ticketdetail->save();

        if ($resposeticketdetail) {
            DB::commit();
            return redirect()->route('ticket.detail', $id)->with('success', "Ticket is created successfully");
        } else {
            DB::rollback();
            return redirect()->route('ticket.add', $id)->with('error', "Something went wrong please try again");
        }

    }

}
