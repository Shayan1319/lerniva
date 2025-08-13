<?php require_once 'assets/php/header.php'; ?>

<style>
#dashboard {
    padding-left: 20px;
    position: relative;
    color: #6777ef !important;
    background-color: #f0f3ff;
}

#dashboard i {
    color: #6777ef !important;
}
</style>
<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <!-- Search + Scanner -->
        <div class="row mb-4">
            <div class="col-md-10 col-sm-9 col-12 mb-2">
                <input type="text" id="searchInput" class="form-control" placeholder="Search any ID or Code">
            </div>
            <div class="col-md-2 col-sm-3 col-12">
                <button class="btn btn-primary btn-block" onclick="openScannerModal()">
                    <i class="fas fa-qrcode"></i> Scan
                </button>
            </div>
        </div>

        <style>
        .card:hover {
            transform: scale(1.03);
            transition: transform 0.3s ease;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            cursor: pointer;
        }
        </style>

        <div class="row">
            <!-- Card 1 -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <a href="see-all-student.html" style="text-decoration: none; color: inherit;">
                    <div class="card">
                        <div class="card-statistic-4">
                            <div class="align-items-center justify-content-between">
                                <div class="row">
                                    <div class="col-lg-6 pr-0 pt-3">
                                        <div class="card-content">
                                            <h5 class="font-15">Total No. of Students</h5>
                                            <h2 class="mb-3 font-18">54</h2>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 pl-0">
                                        <div class="banner-img">
                                            <img src="assets/img/student.png" alt="student">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Card 2 -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <a href="student-profile.html" style="text-decoration: none; color: inherit;">
                    <div class="card">
                        <div class="card-statistic-4">
                            <div class="align-items-center justify-content-between">
                                <div class="row">
                                    <div class="col-lg-6 pr-0 pt-3">
                                        <div class="card-content">
                                            <h5 class="font-15">Student Profile</h5>
                                            <h2 class="mb-3 font-18">View</h2>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 pl-0">
                                        <div class="banner-img">
                                            <img src="assets/img/stu.png" alt="profile">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Card 3 -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <a href="assignment-test.html" style="text-decoration: none; color: inherit;">
                    <div class="card">
                        <div class="card-statistic-4">
                            <div class="align-items-center justify-content-between">
                                <div class="row">
                                    <div class="col-lg-6 pr-0 pt-3">
                                        <div class="card-content">
                                            <h5 class="font-15">Assignments</h5>
                                            <h2 class="mb-3 font-18">12 Tasks</h2>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 pl-0">
                                        <div class="banner-img">
                                            <img src="assets/img/task icon.png" alt="assignment">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Card 4 -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <a href="Dairy.html" style="text-decoration: none; color: inherit;">
                    <div class="card">
                        <div class="card-statistic-4">
                            <div class="align-items-center justify-content-between">
                                <div class="row">
                                    <div class="col-lg-6 pr-0 pt-3">
                                        <div class="card-content">
                                            <h5 class="font-15">Diary</h5>
                                            <h2 class="mb-3 font-18">View</h2>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 pl-0">
                                        <div class="banner-img">
                                            <img src="assets/img/diary.jpg" alt="diary">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-sm-12 col-lg-12">
                <div class="card ">
                    <div class="card-header">
                        <h4>Revenue chart</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-9">
                                <div id="chart1"></div>
                                <div class="row mb-0">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                        <div class="list-inline text-center">
                                            <div class="list-inline-item p-r-30"><i data-feather="arrow-up-circle"
                                                    class="col-green"></i>
                                                <h5 class="m-b-0">51,587 PKR</h5>
                                                <p class="text-muted font-14 m-b-0">Monthly</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                        <div class="list-inline text-center">
                                            <div class="list-inline-item p-r-30"><i data-feather="arrow-up-circle"
                                                    class="col-green"></i>
                                                <h5 class="mb-0 m-b-0">6,45,965 PKR</h5>
                                                <p class="text-muted font-14 m-b-0">Yearly</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="row mt-5">
                                    <div class="col-7 col-xl-7 mb-3">Total Students</div>
                                    <div class="col-5 col-xl-5 mb-3">
                                        <span class="text-big">1,554</span>
                                        <sup class="col-green">+19%</sup>
                                    </div>
                                    <div class="col-7 col-xl-7 mb-3">Total Income</div>
                                    <div class="col-5 col-xl-5 mb-3">
                                        <span class="text-big">6,45,965 PKR</span>
                                        <sup class="col-green">38%</sup>
                                    </div>
                                    <div class="col-7 col-xl-7 mb-3">Total Teachers</div>
                                    <div class="col-5 col-xl-5 mb-3">
                                        <span class="text-big">29</span>
                                        <sup class="col-green">+05%</sup>
                                    </div>
                                    <div class="col-7 col-xl-7 mb-3">Total Salararies</div>
                                    <div class="col-5 col-xl-5 mb-3">
                                        <span class="text-big">2,13,580 PKR</span>
                                    </div>
                                    <div class="col-7 col-xl-7 mb-3">Other Expenses</div>
                                    <div class="col-5 col-xl-5 mb-3">
                                        <span class="text-big">93,800 PKR</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-sm-12 col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Chart</h4>
                    </div>
                    <div class="card-body">
                        <div id="chart4" class="chartsh"></div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-12 col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Chart</h4>
                    </div>
                    <div class="card-body">
                        <div class="summary">
                            <div class="summary-chart active" data-tab-group="summary-tab" id="summary-chart">
                                <div id="chart3" class="chartsh"></div>
                            </div>
                            <div data-tab-group="summary-tab" id="summary-text">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-12 col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Chart</h4>
                    </div>
                    <div class="card-body">
                        <div id="chart2" class="chartsh"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Task Table Record</h4>
                        <div class="card-header-form">
                            <form>
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search">
                                    <div class="input-group-btn">
                                        <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <tr>
                                    <th class="text-center">
                                        <div class="custom-checkbox custom-checkbox-table custom-control">
                                            <input type="checkbox" data-checkboxes="mygroup" data-checkbox-role="dad"
                                                class="custom-control-input" id="checkbox-all">
                                            <label for="checkbox-all" class="custom-control-label">&nbsp;</label>
                                        </div>
                                    </th>
                                    <th>Task Name</th>
                                    <th>Task Status</th>
                                    <th>Assigh Date</th>
                                    <th>Due Date</th>
                                    <th>Priority</th>
                                    <th>Action</th>
                                </tr>
                                <tr>
                                    <td class="p-0 text-center">
                                        <div class="custom-checkbox custom-control">
                                            <input type="checkbox" data-checkboxes="mygroup"
                                                class="custom-control-input" id="checkbox-1">
                                            <label for="checkbox-1" class="custom-control-label">&nbsp;</label>
                                        </div>
                                    </td>
                                    <td>Preperation for Sports Gala</td>
                                    <td class="align-middle">
                                        <div class="progress-text">60%</div>
                                        <div class="progress" data-height="6">
                                            <div class="progress-bar bg-success" data-width="50%"></div>
                                        </div>
                                    </td>
                                    <td>2025-04-20</td>
                                    <td>2025-07-28</td>
                                    <td>
                                        <div class="badge badge-success">Low</div>
                                    </td>
                                    <td><a href="#" class="btn btn-outline-primary">Detail</a></td>
                                </tr>
                                <tr>
                                    <td class="p-0 text-center">
                                        <div class="custom-checkbox custom-control">
                                            <input type="checkbox" data-checkboxes="mygroup"
                                                class="custom-control-input" id="checkbox-2">
                                            <label for="checkbox-2" class="custom-control-label">&nbsp;</label>
                                        </div>
                                    </td>
                                    <td>Mid-Term Examination</td>
                                    <td class="align-middle">
                                        <div class="progress-text">40%</div>
                                        <div class="progress" data-height="6">
                                            <div class="progress-bar bg-danger" data-width="40%"></div>
                                        </div>
                                    </td>
                                    <td>2025-05-14</td>
                                    <td>2025-10-21</td>
                                    <td>
                                        <div class="badge badge-danger">High</div>
                                    </td>
                                    <td><a href="#" class="btn btn-outline-primary">Detail</a></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
    <?php require_once 'assets/php/footer.php'; ?>