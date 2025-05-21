@extends('layout')

@section('content')
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-2 d-none d-md-block bg-light sidebar">
                <div class="sidebar-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="#">
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                Products
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                Customers
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                Reports
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main role="main" class=" px-4">
                <div class="container">

                    {{-- <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <h5 class="mb-4">Open Ticket</h5>
                        <a href="#" class="btn btn-primary"
                            style="display: flex; justify-content: center; align-items: center;">Create Ticket</a>
                    </div> --}}
            
                    @if($tickets->isEmpty())
                        <div class="alert alert-info">Your ticket is empty!</div>
                    @else
                        <div class="row">
                            <div class="container py-4">
                                <div class="card shadow-sm">
                                    <div class="card-header">
                                        <div class="header-content">
                                            <h5 class="title">
                                                <span class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                    <path d="M14 3a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h12zM2 2a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H2z"/>
                                                    <path d="M3 5.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zM3 8a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 8zm0 2.5a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5z"/>
                                                </svg></span>
                                                <span>Open Tickets</span>
                                            </h5>
                                            <span class="badge">
                                                <span class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                    <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
                                                </svg></span>
                                                <span id="ticket-count">{{$tickets->count()}}</span> Ticket(s)
                                            </span>
                                        </div>
                                    </div>
                                
                                    <div class="card-body">
                                        <!-- Tickets List -->
                                        <div class="ticket-container">
                                            <ul class="ticket-list">
                                                <!-- Ticket Items -->
                                                @foreach ($tickets as $ticket)
                                                    <li class="ticket-item priority-{{strtolower($ticket->priority)}}">
                                                        <div class="ticket-header">
                                                            <div class="ticket-meta">
                                                                <span class="priority-badge">{{ucwords($ticket->priority)}}</span>
                                                                <span class="ticket-id">{{$ticket->unique_id}}</span>
                                                            </div>
                                                            <span class="ticket-date">Created: {{$ticket->created_at->diffForHumans()}}</span>
                                                        </div>
                                                        <a href="{{route('OpenTicketView', ['ticket_unique_id' => $ticket->unique_id])}}" class="ticket-title">{{$ticket->subject}}</a>
                                                        <p class="ticket-desc">{{Str::limit($ticket->description, 50, '.....')}}</p>
                                                        <p class="ticket-status">
                                                            <span class="status-badge status-open">{{ucwords($ticket->status)}}</span>
                                                        </p>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                
                                <style>
                                /* Base Card Styles */
                                .card {
                                    border: none;
                                    border-radius: 10px;
                                    overflow: hidden;
                                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
                                    transition: transform 0.2s ease, box-shadow 0.2s ease;
                                }
                                
                                .card:hover {
                                    transform: translateY(-2px);
                                    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
                                }
                                
                                /* Card Header */
                                .card-header {
                                    background: linear-gradient(135deg, #3a7bd5 0%, #00d2ff 100%);
                                    padding: 1rem 1.5rem;
                                    border-bottom: none;
                                }
                                
                                .header-content {
                                    display: flex;
                                    justify-content: space-between;
                                    align-items: center;
                                }
                                
                                .title {
                                    display: flex;
                                    align-items: center;
                                    margin: 0;
                                    font-size: 1.1rem;
                                    font-weight: 600;
                                    color: white;
                                }
                                
                                .title .icon {
                                    margin-right: 0.5rem;
                                    display: inline-flex;
                                    align-items: center;
                                }
                                
                                .badge {
                                    background-color: rgba(255, 255, 255, 0.9);
                                    color: #333;
                                    padding: 0.35rem 0.75rem;
                                    border-radius: 20px;
                                    font-size: 0.85rem;
                                    font-weight: 500;
                                    display: inline-flex;
                                    align-items: center;
                                    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                                }
                                
                                .badge .icon {
                                    margin-right: 0.3rem;
                                    display: inline-flex;
                                    align-items: center;
                                }
                                
                                /* Card Body */
                                .card-body {
                                    padding: 0;
                                }
                                
                                .ticket-container {
                                    max-height: 500px;
                                    overflow-y: auto;
                                }
                                
                                .ticket-list {
                                    list-style: none;
                                    padding: 0;
                                    margin: 0;
                                }
                                
                                /* Ticket Item */
                                .ticket-item {
                                    padding: 1.25rem 1.5rem;
                                    border-bottom: 1px solid #f0f0f0;
                                    transition: background-color 0.2s ease;
                                }
                                
                                .ticket-item:hover {
                                    background-color: #f9f9f9;
                                }
                                
                                .ticket-header {
                                    display: flex;
                                    justify-content: space-between;
                                    align-items: flex-start;
                                    margin-bottom: 0.5rem;
                                }
                                
                                .ticket-meta {
                                    display: flex;
                                    align-items: center;
                                }
                                
                                .priority-badge {
                                    padding: 0.25rem 0.75rem;
                                    border-radius: 4px;
                                    font-size: 0.75rem;
                                    font-weight: 600;
                                    margin-right: 0.75rem;
                                    color: white;
                                }
                                
                                /* Priority Colors */
                                .priority-high .priority-badge { background-color: #ff4757; }
                                .priority-medium .priority-badge { background-color: #ffa502; }
                                .priority-low .priority-badge { background-color: #2ed573; }
                                
                                .ticket-id {
                                    font-size: 0.8rem;
                                    color: #666;
                                }
                                
                                .ticket-date {
                                    font-size: 0.8rem;
                                    color: #666;
                                }
                                
                                .ticket-title {
                                    display: block;
                                    font-weight: 600;
                                    color: #333;
                                    margin: 0.5rem 0;
                                    text-decoration: none;
                                    transition: color 0.2s ease;
                                }
                                
                                .ticket-title:hover {
                                    color: #3a7bd5;
                                }
                                
                                .ticket-desc {
                                    font-size: 0.85rem;
                                    color: #666;
                                    margin: 0.5rem 0 1rem;
                                    line-height: 1.4;
                                }
                                
                                .ticket-status {
                                    margin: 0;
                                }
                                
                                .status-badge {
                                    padding: 0.25rem 0.75rem;
                                    border-radius: 4px;
                                    font-size: 0.75rem;
                                    font-weight: 600;
                                    color: white;
                                }
                                
                                /* Status Colors */
                                .status-open { background-color: #ff4757; }
                                .status-in-progress { background-color: #2d98da; }
                                .status-resolved { background-color: #20bf6b; }
                                .status-closed { background-color: #a5b1c2; }
                                
                                /* Scrollbar styling */
                                .ticket-container::-webkit-scrollbar {
                                    width: 6px;
                                }
                                
                                .ticket-container::-webkit-scrollbar-track {
                                    background: #f1f1f1;
                                    border-radius: 3px;
                                }
                                
                                .ticket-container::-webkit-scrollbar-thumb {
                                    background: #c1c1c1;
                                    border-radius: 3px;
                                }
                                
                                .ticket-container::-webkit-scrollbar-thumb:hover {
                                    background: #a8a8a8;
                                }
                                </style>
                            </div>
                        </div>
                    @endif
                </div>
            </main>
        </div>
    </div>

@endsection