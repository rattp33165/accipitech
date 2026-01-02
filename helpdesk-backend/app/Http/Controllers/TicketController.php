<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Http\Resources\TicketResource;
use Illuminate\Http\Request;

class TicketController extends Controller
{

    private function getValidationRules() {
        return [
            'title' => 'required|string|max:255',
            'contact_email' => 'required|email',
            'description' => 'nullable|string',
            'priority' => 'required|in:Low,Medium,High',
            'status' => 'required|in:Open,In Progress,Resolved,Closed',
            'organization_id' => 'required|exists:organizations,id',
            'vessel_id' => 'required|exists:vessels,id',
            'category_id' => 'required|exists:categories,id',
            'service_line_id' => 'required|exists:service_lines,id',
            'assigned_to_user_id' => 'nullable|exists:users,id',
            'cc_emails' => 'nullable|string',
        ];
    }

    public function index()
    {
        $tickets = Ticket::with([
            'organization',
            'vessel',
            'serviceLine',
            'category',
            'assignedToUser'
        ])->orderBy('created_at', 'desc')->get();

        return TicketResource::collection($tickets);
    }

    public function show($id)
    {
        $ticket = Ticket::with([
            'organization',
            'vessel',
            'serviceLine',
            'category',
            'assignedToUser'
        ])->findOrFail($id);

        return new TicketResource($ticket);
    }

    public function store(Request $request)
    {

        $validated = $request->validate($this->getValidationRules());

        $ticket = Ticket::create($validated);

        return new TicketResource($ticket);
    }

    public function update(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        $validated = $request->validate($this->getValidationRules());

        $ticket->update($validated);

        return new TicketResource($ticket->refresh());
    }

    public function destroy($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();

        return response()->json([
            'message' => 'Ticket deleted successfully'
        ]);
    }
}
