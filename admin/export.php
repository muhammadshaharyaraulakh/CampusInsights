<?php 
require __DIR__."/includes/header.php";
?>

    <!-- 2. MAIN CONTENT AREA -->
    <div class="main-wrapper">


        <!-- Export Configuration Body -->
        <main class="dashboard-body container">
            
            <h1 class="page-title">Data Export Management</h1>
            
            <!-- Panel 1: Export Configuration Form -->
            <div class="panel export-config mb-4">
                <div class="panel-header">
                    <h3>Configure Export Settings</h3>
                </div>
                <div class="panel-body">
                    
                    <p class="text-muted mb-4">Select your desired date range, status, and format to generate a batch export of survey responses.</p>

                    <form action="process_export.php" method="POST" class="export-form-grid">

                        <!-- Filter 1: Date Range -->
                        <div class="form-field">
                            <label for="export-start-date">Start Date</label>
                            <input type="date" id="export-start-date" name="start_date" class="form-control" required>
                        </div>

                        <div class="form-field">
                            <label for="export-end-date">End Date</label>
                            <input type="date" id="export-end-date" name="end_date" class="form-control" required>
                        </div>
                        
                        <!-- Filter 2: Status -->
                        <div class="form-field">
                            <label for="export-status">Response Status</label>
                            <select id="export-status" name="status" class="form-control">
                                <option value="all">All Responses (5,241)</option>
                                <option value="new">New Only (47)</option>
                                <option value="unread">Unexported (1,500)</option>
                            </select>
                        </div>

                        <!-- Filter 3: Format -->
                        <div class="form-field">
                            <label for="export-format">File Format</label>
                            <select id="export-format" name="format" class="form-control">
                                <option value="csv">CSV (Comma Separated Values)</option>
                                <option value="xlsx">Excel (XLSX)</option>
                                <option value="json">JSON (API Use)</option>
                            </select>
                        </div>
                        
                        <!-- Action Button (Span full width on mobile) -->
                        <div class="form-field form-submit-area">
                            <button type="submit" class="btn btn-primary btn-block animate-hover" id="trigger-export">
                                <i class="fas fa-file-export"></i> Initiate Data Export
                            </button>
                        </div>
                    </form>
                    
                </div>
            </div> <!-- End Config Panel -->


            <!-- Panel 2: Recent Export History -->
            <div class="panel export-history">
                 <div class="panel-header">
                    <h3>Recent Export History</h3>
                    <button class="btn btn-secondary btn-small"><i class="fas fa-history"></i> Refresh Log</button>
                </div>
                 <div class="panel-body">
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Export ID</th>
                                    <th>Date Created</th>
                                    <th>Records</th>
                                    <th>Format</th>
                                    <th>Status</th>
                                    <th>Download</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>EXP-20241025-003</td>
                                    <td>2024-10-25 11:30</td>
                                    <td>1,250</td>
                                    <td>CSV</td>
                                    <td><span class="tag tag-read">Complete</span></td>
                                    <td><a href="#" class="btn-table-action download-btn" title="Download"><i class="fas fa-download"></i></a></td>
                                </tr>
                                <tr>
                                    <td>EXP-20241024-002</td>
                                    <td>2024-10-24 15:45</td>
                                    <td>5,200</td>
                                    <td>XLSX</td>
                                    <td><span class="tag tag-new">Processing...</span></td>
                                    <td><span class="text-muted">N/A</span></td>
                                </tr>
                                <tr>
                                    <td>EXP-20241001-001</td>
                                    <td>2024-10-01 09:00</td>
                                    <td>5,000</td>
                                    <td>CSV</td>
                                    <td><span class="tag tag-export">Expired</span></td>
                                    <td><span class="text-danger">Deleted</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
        </main>
<?php 
require __DIR__."/includes/footer.php";
?>