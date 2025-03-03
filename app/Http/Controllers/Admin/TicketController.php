<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketDetail;
use Auth;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index()
    {
        $ticket = Ticket::orderBy('id', 'DESC')->get();
        return view('admin.ticket.list', compact('ticket'));
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

    public function view($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticketdetail = TicketDetail::where('ticket_id', $ticket->id)
            ->orderBy('id', 'DESC')
            ->get();
        return view('admin.ticket.detail', [
            'ticket' => $ticket,
            'ticketdetail' => $ticketdetail,
        ]);
    }

    public function changeStatus($id)
    {
        $status = 1;
        $ticket = Ticket::findOrFail($id)->update([
            'status' => $status,
        ]);
        $msgtext = $status
        ? 'Ticket is closed successfully'
        : 'Ticket is opened successfully';
        if ($ticket) {
            $type = 1;
            $msg = $msgtext;
        } else {
            $type = 0;
            $msg = 'Something went wrong';
        }
        $result = ['type' => $type, 'msg' => $msg];
        echo json_encode($result);
        exit();
    }

    public function closeTicket($id)
    {
        $ticketdetail = TicketDetail::findOrFail($id);
        $ticket = Ticket::findOrFail($ticketdetail->ticket_id)->update([
            'status' => '1',
        ]);
        if ($ticket) {
            $type = 1;
            $msg = 'Ticket is closed successfully';
        } else {
            $type = 0;
            $msg = 'Something went wrong';
        }
        $result = ['type' => $type, 'msg' => $msg];
        echo json_encode($result);
        exit();
    }

    public function detail($id)
    {
        $ticket = Ticket::findOrFail($id);
        return view('admin.ticket.detail', [
            'ticket' => $ticket,
        ]);
    }

    public function reply($id)
    {
        $ticket = Ticket::findOrFail($id);
        return view('admin.ticket.reply', [
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
        $ticket = Ticket::findOrFail($id);

        $ticketdetail = new TicketDetail();
        $ticketdetail->ticket_id = $ticketid;
        $ticketdetail->from_id = Auth::guard('admin')->user()->id;
        $ticketdetail->to_id  = $ticket->user_id;
        $ticketdetail->message = $request->content;
        $ticketdetail->user_type = '1';
        $resposeticketdetail = $ticketdetail->save();

        if ($resposeticketdetail) {
            return redirect()->route('admin.ticket.detail', $id)->with('success', "Ticket is created successfully");
        } else {
            return redirect()->route('admin.ticket.add', $id)->with('error', "Something went wrong please try again");
        }

    }
}
