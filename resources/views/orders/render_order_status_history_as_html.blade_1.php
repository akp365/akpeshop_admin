@if($order->order_status_history)
    @foreach($order->order_status_history as $history)
        <div class='tracking-item' style='display: flex; align-items: center; position: relative; margin-bottom: 15px;'>
            <div class='status-circle' style='width: 20px; height: 20px; border-radius: 50%; background-color: #ccc; margin-right: 15px; position: relative; z-index: 1;'></div>
            <div class='tracking-content' style='flex: 1; margin-top: 25px;'>
                <span class='date' style='font-weight: bold;'>{{ \Carbon\Carbon::parse($history->created_at)->format('M.d') }}</span>
                <span class='time' style='font-size: 12px;'>{{ \Carbon\Carbon::parse($history->created_at)->format('H:i') }}</span>
                <span class='status' style='font-size: 14px; font-weight: bold;'>{{ $history->status_name }}</span>
                <p style='margin: 5px 0 0; font-size: 12px; color: #555;'>
                    {{ $history->note }}
                </p>
            </div>
        </div>
    @endforeach
@endif
