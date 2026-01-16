<?php 
require __DIR__."/includes/header.php";

?>
    <!-- 2. MAIN CONTENT AREA -->
    <div class="main-wrapper">


        <!-- Dashboard Body -->
        <main class="dashboard-body container">
            
            <h1 class="page-title">Dashboard Overview</h1>
            
            <!-- KPI Statistics Cards Grid -->
            <div class="stats-grid">
                
                <div class="stat-card primary-border">
                    <div class="stat-icon"><i class="fas fa-database"></i></div>
                    <div class="stat-info">
                        <p class="stat-value">5,241</p>
                        <p class="stat-label">Total Submissions</p>
                    </div>
                </div>
                
                <div class="stat-card secondary-border">
                    <div class="stat-icon"><i class="fas fa-chart-line"></i></div>
                    <div class="stat-info">
                        <p class="stat-value">47</p>
                        <p class="stat-label">New Today</p>
                    </div>
                </div>

                
                
                <div class="stat-card warning-border">
                    <div class="stat-icon"><i class="fas fa-file-csv"></i></div>
                    <div class="stat-info">
                        <p class="stat-value">1,500</p>
                        <p class="stat-label">Unexported Records</p>
                    </div>
                </div>
            </div> <!-- /stats-grid -->

            <!-- Recent Activity & Charts -->
            <div class="data-section-grid">
                
                <!-- Panel 1: Recent Responses Table -->
                <div class="panel recent-responses">
                    <div class="panel-header">
                        <h3>Recent Submissions</h3>
                        <a href="view.php" class="panel-action">View All <i class="fas fa-chevron-right"></i></a>
                    </div>
                    <div class="panel-body">
                        
                        <div class="table-responsive">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Date</th>
                                        <th>User/IP</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>#1054</td>
                                        <td>2 mins ago</td>
                                        <td>192.168.1.5</td>
                                        <td><span class="tag tag-new">New</span></td>
                                        <td><a href="view.php?id=1054" class="btn-table-action"><i class="fas fa-eye"></i></a></td>
                                    </tr>
                                    <tr>
                                        <td>#1053</td>
                                        <td>1 hour ago</td>
                                        <td>Jane D.</td>
                                        <td><span class="tag tag-read">Read</span></td>
                                        <td><a href="view.php?id=1053" class="btn-table-action"><i class="fas fa-eye"></i></a></td>
                                    </tr>
                                    <tr>
                                        <td>#1052</td>
                                        <td>Yesterday</td>
                                        <td>203.0.113.8</td>
                                        <td><span class="tag tag-export">Exported</span></td>
                                        <td><a href="view.php?id=1052" class="btn-table-action"><i class="fas fa-eye"></i></a></td>
                                    </tr>
                                    <tr>
                                        <td>#1051</td>
                                        <td>2 days ago</td>
                                        <td>Anonymous</td>
                                        <td><span class="tag tag-read">Read</span></td>
                                        <td><a href="view.php?id=1051" class="btn-table-action"><i class="fas fa-eye"></i></a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                 <!-- Panel 2: Quick Actions -->
                 <div class="panel quick-actions">
                    <div class="panel-header">
                        <h3>Quick Actions</h3>
                    </div>
                    <div class="panel-body action-list">
                        <a href="export.php" class="btn btn-primary btn-block animate-hover">
                            <i class="fas fa-file-export"></i> Trigger Full Export
                        </a>
                        <button class="btn btn-secondary btn-block animate-hover">
                            <i class="fas fa-chart-bar"></i> View Analytics Report
                        </button>
                        <button class="btn btn-danger btn-block animate-hover">
                            <i class="fas fa-trash-alt"></i> Delete Old Responses
                        </button>
                    </div>
                    
                </div>

            </div> <!-- /data-section-grid -->
            
        </main>
        <?php 
require __DIR__."/includes/footer.php";
?>



