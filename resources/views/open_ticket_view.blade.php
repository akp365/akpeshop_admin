@extends('layout')

@section('content')
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="container">

        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
            <h5 class="mb-4">Open Ticket</h5>
            {{-- <button type="submit" class="btn btn-primary">Create Ticket</button> --}}
        </div>


        <div class="row">
            <div class="support-inbox">
                <!-- Chat Header -->
                <div class="inbox-header">
                    <div class="ticket-info">
                        <h3 style="color: white">Ticket #{{request()->segment(3)}}</h3>
                        <form action="{{route('AssignTicket')}}" method="POST"
                            style="display: flex; justify-content: center; align-items: center;">
                            @csrf
                            <input type="hidden" name="ticket_id" value="{{$openTicketId->id}}">
                            <div class="" style="margin-right: 10px;">
                                <select style="width: 100%; margin-left: 23px; color: black;" name="status">


                                    {{-- @foreach ($openTicketId as $ticket) --}}
                                    @if ($openTicketId->status == 'open')
                                        <option value="open" selected>
                                            Open</option>
                                            <option value="closed">
                                                Closed</option>
                                                <option value="answered">
                                                    Answered</option>
                                    @elseif ($openTicketId->status == 'closed')
                                    <option value="open">
                                        Open</option>
                                        <option value="closed" selected>
                                            Closed</option>
                                            <option value="answered">
                                                Answered</option>
                                    @elseif ($openTicketId->status == 'answered')

                                    <option value="open">
                                        Open</option>
                                        <option value="closed">
                                            Closed</option>
                                            <option value="answered" selected>
                                                Answered</option>
                                    @endif
                                    {{-- @endforeach --}}





                                </select>
                            </div>
                            <div class="" style="margin-right: 10px;">
                                <select style="width: 100%; margin-left: 23px; color: black;" name="vendor_id">
                                    <option value="assign">
                                        Assign Vendor</option>
                                    @foreach ($allVendor as $seller)
                                        <option value="{{$seller?->id}}"@if ($seller?->id == $openTicketId?->assign_vendor?->vendor)
                                            selected
                                        @endif>
                                            {{$seller->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary" style="margin-left: 25px;">Save</button>
                        </form>
                    </div>
                </div>

                <!-- Messages Container -->
                <div class="messages-container">
                    @foreach($openTicket as $message)
                        @if($message->user_id)

                            <!-- Support Message -->
                            <div class="message support-message">
                                <div class="message-avatar">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                        <path
                                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z" />
                                    </svg>
                                </div>
                                <div class="message-content">
                                    {{ $message->message }}
                                    <div class="message-meta">
                                        <span class="message-time">
                                            {{ \Carbon\Carbon::parse($message->created_at)->format('d M Y, h:i A') }}
                                        </span>
                                        <span class="message-sender">Support</span>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- User Message -->
                            <div class="message user-message">
                                <div class="message-content">
                                    {{ $message->message }}
                                </div>
                                <div class="message-meta">
                                    <span class="message-time">
                                        {{ \Carbon\Carbon::parse($message->created_at)->format('d M Y, h:i A') }}
                                    </span>
                                    {{-- <span class="message-sender">You</span> --}}
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                <!-- Message Input -->
                <div class="message-input-container">
                    <form action="{{route('SendTicketMessage')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" value="{{$message->user_id}}" name="user_id"/>
                        <input type="hidden" name="ticket_id" value="{{$message->ticket_id}}" />
                        <div class="input-group">
                            <textarea name="message" class="message-input" placeholder="Type your reply..."
                                rows="1"></textarea>
                            <button type="submit" class="send-button">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z" />
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <style>
                .support-inbox {
                    display: flex;
                    flex-direction: column;
                    height: 100%;
                    background-color: #f5f7fa;
                    border-radius: 8px;
                    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                    overflow: hidden;
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                }

                .inbox-header {
                    padding: 16px 20px;
                    background-color: #3f51b5;
                    color: white;
                    border-bottom: 1px solid #e0e0e0;
                }

                .ticket-info {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                }

                .ticket-info h3 {
                    margin: 0;
                    font-size: 18px;
                    font-weight: 600;
                }

                .ticket-status {
                    background-color: #4caf50;
                    color: white;
                    padding: 4px 8px;
                    border-radius: 12px;
                    font-size: 12px;
                    font-weight: 500;
                }

                .messages-container {
                    flex: 1;
                    padding: 20px;
                    overflow-y: auto;
                    background-color: #f5f7fa;
                }

                .message {
                    display: flex;
                    margin-bottom: 16px;
                    max-width: 80%;
                }

                .message-content {
                    padding: 12px 16px;
                    border-radius: 8px;
                    position: relative;
                    word-wrap: break-word;
                    line-height: 1.4;
                }

                .message-meta {
                    font-size: 11px;
                    color: #757575;
                    margin-top: 4px;
                    display: flex;
                    justify-content: space-between;
                }

                .user-message {
                    margin-left: auto;
                    flex-direction: column;
                    align-items: flex-end;
                }

                .user-message .message-content {
                    background-color: #3f51b5;
                    color: white;
                    border-top-right-radius: 0;
                }

                .support-message {
                    margin-right: auto;
                    align-items: flex-start;
                }

                .support-message .message-content {
                    background-color: white;
                    color: #333;
                    border-top-left-radius: 0;
                    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
                }

                .message-avatar {
                    width: 32px;
                    height: 32px;
                    margin-right: 12px;
                    background-color: #e0e0e0;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: #757575;
                }

                .message-avatar svg {
                    width: 18px;
                    height: 18px;
                }

                .message-input-container {
                    padding: 16px;
                    border-top: 1px solid #e0e0e0;
                    background-color: white;
                }

                .input-group {
                    display: flex;
                    align-items: center;
                }

                .message-input {
                    flex: 1;
                    border: 1px solid #e0e0e0;
                    border-radius: 20px;
                    padding: 10px 16px;
                    /* resize: none; */
                    outline: none;
                    font-family: inherit;
                    transition: border 0.3s;
                }

                .message-input:focus {
                    border-color: #3f51b5;
                }

                .send-button {
                    margin-left: 12px;
                    background-color: #3f51b5;
                    color: white;
                    border: none;
                    width: 40px;
                    height: 40px;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    cursor: pointer;
                    transition: background-color 0.3s;
                }

                .send-button:hover {
                    background-color: #303f9f;
                }

                .send-button svg {
                    width: 20px;
                    height: 20px;
                }
            </style>
        </div>
    </div>
@endsection