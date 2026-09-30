<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Logs</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f7fb;
            color: #1f2937;
        }

        .container {
            max-width: 1300px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .page-header h1 {
            margin: 0;
            font-size: 28px;
        }

        .page-header p {
            margin: 6px 0 0;
            color: #6b7280;
            font-size: 14px;
        }

        .btn {
            display: inline-block;
            padding: 10px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
            border: none;
        }

        .btn-secondary {
            background: #e5e7eb;
            color: #374151;
        }

        .btn-secondary:hover {
            background: #d1d5db;
        }

        .table-wrapper {
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.07);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            padding: 15px;
            background: #f9fafb;
            text-align: left;
            font-size: 13px;
            color: #4b5563;
            border-bottom: 1px solid #e5e7eb;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
            font-size: 14px;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .event {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .event-created {
            background: #dcfce7;
            color: #166534;
        }

        .event-updated {
            background: #dbeafe;
            color: #1e40af;
        }

        .event-deleted {
            background: #fee2e2;
            color: #991b1b;
        }

        .event-default {
            background: #f3f4f6;
            color: #374151;
        }

        .json-box {
            max-width: 300px;
            padding: 10px;
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            font-family: monospace;
            font-size: 12px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .empty-value {
            color: #9ca3af;
            font-style: italic;
        }

        .user {
            font-weight: 600;
        }

        .date {
            color: #6b7280;
            white-space: nowrap;
        }

        .empty {
            text-align: center;
            padding: 50px;
            color: #6b7280;
        }

        .view-btn {
            padding: 7px 12px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
        }

        .view-btn:hover {
            background: #1d4ed8;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            align-items: center;
            justify-content: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            width: 90%;
            max-width: 800px;
            max-height: 85vh;
            background: white;
            border-radius: 10px;
            overflow: hidden;
        }

        .modal-header {
            padding: 18px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #e5e7eb;
        }

        .modal-header h2 {
            margin: 0;
            font-size: 18px;
        }

        .close {
            border: none;
            background: none;
            font-size: 25px;
            cursor: pointer;
            color: #6b7280;
        }

        .modal-body {
            padding: 20px;
            overflow-y: auto;
        }

        .json-title {
            margin-bottom: 8px;
            font-weight: 600;
        }

        pre {
            margin: 0 0 25px;
            padding: 15px;
            background: #111827;
            color: #e5e7eb;
            border-radius: 6px;
            overflow-x: auto;
            font-size: 13px;
            line-height: 1.6;
        }

        @media (max-width: 900px) {
            .table-wrapper {
                overflow-x: auto;
            }

            table {
                min-width: 900px;
            }
        }
    </style>
</head>

<body>
    @include('layouts.header')
    <div class="container"> 
        <div class="page-header">
            <div>
                <h1>Audit Logs</h1>
            </div>
        </div>
        <div class="table-wrapper">
            @if ($logs->count())
                <table>
                    <thead>
                        <tr>
                            <th width="60">#</th>
                            <th width="120">Event</th>
                            <th width="150">User</th>
                            <th>Before</th>
                            <th>After</th>
                            <th width="170">Date</th>
                            <th width="100">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($logs as $log)
                            <tr> {{-- ID --}} <td> #{{ $log->id }} </td> {{-- Event --}} <td>
                                    @php
                                        $event = strtolower($log->event ?? 'unknown');
                                        $eventClass = match ($event) {
                                            'created' => 'event-created',
                                            'updated' => 'event-updated',
                                            'deleted' => 'event-deleted',
                                            default => 'event-default',
                                        };
                                    @endphp <span class="event {{ $eventClass }}"> {{ $event }}
                                    </span> </td> {{-- User --}} <td>
                                    @if ($log->user)
                                        <span class="user"> {{ $log->user->name }} </span>
                                    @else
                                        <span class="empty-value"> System </span>
                                        @endif
                                </td> {{-- Before --}} <td>
                                    @if ($log->before)
                                        <div class="json-box"> {{ json_encode($log->before) }} </div>
                                    @else
                                        <span class="empty-value"> No previous data </span>
                                        @endif
                                </td> {{-- After --}} <td>
                                    @if ($log->after)
                                        <div class="json-box"> {{ json_encode($log->after) }} </div>
                                    @else
                                        <span class="empty-value"> No new data </span>
                                    @endif
                                </td> {{-- Date --}} <td>
                                    <div class="date"> {{ $log->created_at->format('d M Y') }} </div> <small>
                                        {{ $log->created_at->format('h:i A') }} </small>
                                </td> {{-- Action --}} <td> <button type="button" class="view-btn"
                                        onclick="showLog({{ $log->id }})"> View </button> </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty">
                    <h3>No audit logs found</h3>
                    <p> There are currently no activities to display. </p>
                </div>
            @endif
        </div>
    </div>
    <div id="logModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2> Audit Log Details </h2> <button class="close" onclick="closeLog()"> &times; </button>
            </div>
            <div class="modal-body">
                <div class="json-title"> Before </div>
                <pre id="beforeData"></pre>
                <div class="json-title"> After </div>
                <pre id="afterData"></pre>
            </div>
        </div>
    </div>
    <script>
        const logs = @json($logs->keyBy('id'));

        function showLog(id) {
            const log = logs[id];
            const before = log.before ? JSON.stringify(log.before, null, 4) : 'No previous data';
            const after = log.after ? JSON.stringify(log.after, null, 4) : 'No new data';
            document.getElementById('beforeData').textContent = before;
            document.getElementById('afterData').textContent = after;
            document.getElementById('logModal').classList.add('active');
        }

        function closeLog() {
            document.getElementById('logModal').classList.remove('active');
        }
        window.addEventListener('click', function(event) {
            const modal = document.getElementById('logModal');
            if (event.target === modal) {
                closeLog();
            }
        });
    </script>
</body>
</html>
