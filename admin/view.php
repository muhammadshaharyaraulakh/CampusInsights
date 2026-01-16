<?php 
require __DIR__."/includes/header.php";
?>

    <!-- 2. MAIN CONTENT AREA -->
    <div class="main-wrapper">
        


        <!-- View Responses Body -->
        <main class="dashboard-body container">
            
            <h1 class="page-title">Survey Responses Archive</h1>
            
            <!-- Filter & Action Bar -->
            <div class="panel filter-bar">
                <div class="filter-group">
                    <label for="status-filter">Status:</label>
                    <select id="status-filter" class="form-control">
                        <option>All</option>
                        <option>New (47)</option>
                        <option>Read (3000)</option>
                        <option>Exported (2200)</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="date-filter">Date Range:</label>
                    <input type="date" id="start-date" class="form-control-inline">
                    <span>to</span>
                    <input type="date" id="end-date" class="form-control-inline">
                </div>

                <div class="filter-actions">
                    <button class="btn btn-primary btn-small">Apply Filters</button>
                    <button class="btn btn-secondary btn-small">Reset</button>
                </div>
            </div>
            
            <!-- Main Data Table Panel -->
            <div class="panel data-view-panel mt-4">
                <div class="panel-header">
                    <h3>Displaying 1 - 20 of 5,241 Results</h3>
                    <!-- Space for bulk actions or export button -->
                    <button class="btn btn-primary btn-small"><i class="fas fa-download"></i> Export Page</button>
                </div>

                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="data-table full-response-table">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="select-all"></th>
                                    <th>ID</th>
                                    <th>Date/Time</th>
                                    <th class="d-none d-md-table-cell">Respondent</th>
                                    <th>Key Rating</th>
                                    <th class="d-none d-lg-table-cell">City/Region</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Example Row: New -->
                                <tr>
                                    <td><input type="checkbox" name="response[]" value="1059"></td>
                                    <td>#1059</td>
                                    <td>2024-10-25 14:30</td>
                                    <td class="d-none d-md-table-cell">IP: 192.168.1.10</td>
                                    <td><span class="text-rating text-danger">2/5</span></td>
                                    <td class="d-none d-lg-table-cell">New York, NY</td>
                                    <td><span class="tag tag-new">New</span></td>
                                    <td><a href="#" class="btn-table-action view-btn" title="View Details"><i class="fas fa-eye"></i></a></td>
                                </tr>
                                <!-- Example Row: Read -->
                                <tr>
                                    <td><input type="checkbox" name="response[]" value="1058"></td>
                                    <td>#1058</td>
                                    <td>2024-10-25 10:15</td>
                                    <td class="d-none d-md-table-cell">Jane D. (Email)</td>
                                    <td><span class="text-rating text-success">5/5</span></td>
                                    <td class="d-none d-lg-table-cell">London, UK</td>
                                    <td><span class="tag tag-read">Read</span></td>
                                    <td><a href="#" class="btn-table-action view-btn"><i class="fas fa-eye"></i></a></td>
                                </tr>
                                <!-- Example Row: Exported -->
                                <tr>
                                    <td><input type="checkbox" name="response[]" value="1057"></td>
                                    <td>#1057</td>
                                    <td>2024-10-24 18:00</td>
                                    <td class="d-none d-md-table-cell">Anonymous</td>
                                    <td><span class="text-rating text-warning">4/5</span></td>
                                    <td class="d-none d-lg-table-cell">Sydney, AU</td>
                                    <td><span class="tag tag-export">Exported</span></td>
                                    <td><a href="#" class="btn-table-action view-btn"><i class="fas fa-eye"></i></a></td>
                                </tr>
                                <!-- ... more rows ... -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="panel-footer pagination-footer">
                    <p>Page 1 of 263</p>
                    <div class="pagination-controls">
                        <button class="btn btn-secondary btn-small" disabled><i class="fas fa-chevron-left"></i> Prev</button>
                        <button class="btn btn-primary btn-small">Next <i class="fas fa-chevron-right"></i></button>
                    </div>
                </div>
            </div>
            
        </main>
<?php 
require __DIR__."/includes/footer.php";
?>