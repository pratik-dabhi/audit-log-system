<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Logs</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest"; window.axios.defaults.withCredentials = true;</script>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: Arial, sans-serif; background: #f5f7fb; color: #1f2937; }
        .container { max-width: 1300px; margin: 40px auto; padding: 0 20px; }
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
        .page-header h1 { margin: 0; font-size: 28px; }
        .page-header p { margin: 6px 0 0; color: #6b7280; font-size: 14px; }
        .table-wrapper { background: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.07); }
        table { width: 100%; border-collapse: collapse; }
        th { padding: 15px; background: #f9fafb; text-align: left; font-size: 13px; color: #4b5563; border-bottom: 1px solid #e5e7eb; }
        td { padding: 15px; border-bottom: 1px solid #e5e7eb; vertical-align: top; font-size: 14px; }
        tr:last-child td { border-bottom: none; }
        .event { display: inline-block; padding: 5px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
        .event-created { background: #dcfce7; color: #166534; }
        .event-updated { background: #dbeafe; color: #1e40af; }
        .event-deleted { background: #fee2e2; color: #991b1b; }
        .event-default { background: #f3f4f6; color: #374151; }
        .json-box { max-width: 300px; padding: 10px; background: #f8fafc; border: 1px solid #e5e7eb; border-radius: 6px; font-family: monospace; font-size: 12px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .empty-value { color: #9ca3af; font-style: italic; }
        .user { font-weight: 600; }
        .date { color: #6b7280; white-space: nowrap; }
        .empty { text-align: center; padding: 50px; color: #6b7280; }
        .view-btn { padding: 7px 12px; background: #2563eb; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 12px; }
        .view-btn:hover { background: #1d4ed8; }
        .modal { display: none; position: fixed; z-index: 1000; inset: 0; background: rgba(0, 0, 0, 0.5); align-items: center; justify-content: center; }
        .modal.active { display: flex; }
        .modal-content { width: 90%; max-width: 800px; max-height: 85vh; background: white; border-radius: 10px; overflow: hidden; }
        .modal-header { padding: 18px 20px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e5e7eb; }
        .modal-header h2 { margin: 0; font-size: 18px; }
        .close { border: none; background: none; font-size: 25px; cursor: pointer; color: #6b7280; }
        .modal-body { padding: 20px; overflow-y: auto; }
        .json-title { margin-bottom: 8px; font-weight: 600; }
        pre { margin: 0 0 25px; padding: 15px; background: #111827; color: #e5e7eb; border-radius: 6px; overflow-x: auto; font-size: 13px; line-height: 1.6; }
    </style>
</head>
<body>
    @include('layouts.header')
    <div class="container" x-data="auditLogsApp()" x-init="fetchLogs()"> 
        <div class="page-header">
            <div>
                <h1>Audit Logs</h1>
            </div>
            <div style="display:flex; gap: 10px;">
                <select x-model="filters.event" @change="fetchLogs()" style="padding: 8px; border-radius: 5px;">
                    <option value="">All Events</option>
                    <option value="created">Created</option>
                    <option value="updated">Updated</option>
                    <option value="deleted">Deleted</option>
                </select>
            </div>
        </div>
        <div class="table-wrapper">
            <template x-if="loading">
                <div class="empty">Loading...</div>
            </template>
            <template x-if="!loading && logs.length > 0">
                <table>
                    <thead>
                        <tr>
                            <th width="60">#</th>
                            <th width="120">Event</th>
                            <th width="150">User</th>
                            <th>Old Values</th>
                            <th>New Values</th>
                            <th width="170">Date</th>
                            <th width="100">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="log in logs" :key="log.id">
                            <tr>
                                <td x-text="'#' + log.id"></td>
                                <td>
                                    <span :class="'event event-' + log.event.toLowerCase()" x-text="log.event"></span>
                                </td>
                                <td>
                                    <span class="user" x-text="log.user ? log.user.name : 'System'"></span>
                                </td>
                                <td>
                                    <template x-if="log.old_values">
                                        <div class="json-box" x-text="JSON.stringify(log.old_values)"></div>
                                    </template>
                                    <template x-if="!log.old_values">
                                        <span class="empty-value">No previous data</span>
                                    </template>
                                </td>
                                <td>
                                    <template x-if="log.new_values">
                                        <div class="json-box" x-text="JSON.stringify(log.new_values)"></div>
                                    </template>
                                    <template x-if="!log.new_values">
                                        <span class="empty-value">No new data</span>
                                    </template>
                                </td>
                                <td>
                                    <div class="date" x-text="new Date(log.created_at).toLocaleDateString()"></div>
                                </td>
                                <td>
                                    <button type="button" class="view-btn" @click="showLog(log)">View</button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </template>
            <template x-if="!loading && logs.length === 0">
                <div class="empty">
                    <h3>No audit logs found</h3>
                </div>
            </template>
        </div>

        <!-- Modal -->
        <div id="logModal" class="modal" :class="{'active': modalOpen}" @click.self="closeLog()">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Audit Log Details</h2>
                    <button class="close" @click="closeLog()">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="json-title">Old Values</div>
                    <pre x-text="activeLog.old_values ? JSON.stringify(activeLog.old_values, null, 4) : 'No previous data'"></pre>
                    <div class="json-title">New Values</div>
                    <pre x-text="activeLog.new_values ? JSON.stringify(activeLog.new_values, null, 4) : 'No new data'"></pre>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function auditLogsApp() {
            return {
                logs: [],
                loading: true,
                modalOpen: false,
                activeLog: {},
                filters: {
                    event: ''
                },
                fetchLogs() {
                    this.loading = true;
                    axios.get('{{ url('/api/audit-logs') }}', { params: this.filters })
                        .then(response => {
                            this.logs = response.data.data;
                            this.loading = false;
                        })
                        .catch(error => {
                            console.error('Error fetching logs:', error);
                            this.loading = false;
                        });
                },
                showLog(log) {
                    this.activeLog = log;
                    this.modalOpen = true;
                },
                closeLog() {
                    this.modalOpen = false;
                }
            }
        }
    </script>
</body>
</html>
