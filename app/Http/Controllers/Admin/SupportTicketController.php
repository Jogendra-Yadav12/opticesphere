<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\SupportTicket;
use App\Models\TicketReply;
use Illuminate\Http\Request;

class SupportTicketController extends Controller
{
    public function index()
    {
        $tickets = SupportTicket::with(['user', 'assignedTo'])
            ->latest()
            ->get();

        return view('admin.supportTickets', compact('tickets'));
    }

    public function show(SupportTicket $ticket)
    {
        $ticket->load(['user', 'order', 'replies.replier', 'assignedTo']);

        $admins = Admin::all();

        return view('admin.ticketDetail', compact('ticket', 'admins'));
    }

    public function reply(Request $request, SupportTicket $ticket)
    {
        $data = $request->validate(['body' => 'required|string|max:2000']);

        TicketReply::create([
            'ticket_id' => $ticket->id,
            'replier_type' => get_class(auth()->user()),
            'replier_id' => auth()->id(),
            'body' => $data['body'],
            'is_staff' => true,
        ]);

        if ($ticket->status === 'open') {
            $ticket->status = 'answered';
            $ticket->save();
        }

        return back()->with('success', 'Reply sent.');
    }

    public function updateStatus(Request $request, SupportTicket $ticket)
    {
        $data = $request->validate([
            'status' => 'required|in:open,answered,on_hold,closed',
            'assigned_to' => 'nullable|exists:admins,id',
        ]);

        $ticket->status = $data['status'];
        $ticket->assigned_to = $data['assigned_to'] ?? null;
        $ticket->save();

        return back()->with('success', 'Ticket updated.');
    }
}
