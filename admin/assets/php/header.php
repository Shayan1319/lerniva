<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
  header("Location: logout.php");
  exit;
}
include_once('sass/db_config.php');

$school_id = $_SESSION['admin_id']; // or dynamically get this if needed

$sql = "SELECT id, school_name, logo FROM schools WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $school_id);
$stmt->execute();
$result = $stmt->get_result();

$school = null;
if ($result->num_rows > 0) {
    $school = $result->fetch_assoc();
} else {
    // fallback/default values if no school found
    $school = [
        'id' => 0,
        'school_name' => 'Default School Name',
        'logo' => 'assets/img/default-logo.png'
    ];
}
$stmt->close();
?>



<!DOCTYPE html>
<html lang="en">


<!-- index.php  21 Nov 2019 03:44:50 GMT -->

<head>
    <title>Admin Dashboard</title>

    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <!-- General CSS Files -->
    <link rel="stylesheet" href="assets/css/app.min.css">
    <link rel="stylesheet" href="assets/bundles/prism/prism.css">
    <!-- Template CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/components.css">
    <!-- Custom style CSS -->
    <link rel="stylesheet" href="assets/css/custom.css">

    <link rel="stylesheet" href="assets/bundles/bootstrap-daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="assets/bundles/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css">
    <link rel="stylesheet" href="assets/bundles/select2/dist/css/select2.min.css">
    <link rel="stylesheet" href="assets/bundles/jquery-selectric/selectric.css">
    <link rel="stylesheet" href="assets/bundles/bootstrap-timepicker/css/bootstrap-timepicker.min.css">
    <link rel="stylesheet" href="assets/bundles/bootstrap-tagsinput/dist/bootstrap-tagsinput.css">
    <link rel='shortcut icon' type='image/x-icon' href='assets/img/T Logo.png' />

</head>

<body>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">


            <div class="navbar-bg"></div>
            <nav class="navbar navbar-expand-lg main-navbar sticky">
                <div class="form-inline mr-auto">
                    <ul class="navbar-nav mr-3">
                        <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg
									collapse-btn"> <i data-feather="align-justify"></i></a></li>
                        <!-- <li>
                            <form class="form-inline mr-auto">

                                <div class="search-element">
                                  <input class="form-control" type="search" placeholder="Search" aria-label="Search" data-width="200">
                                  <button class="btn" type="submit">
                                    <i class="fas fa-search"></i>
                                  </button>
                                </div> 

                            </form>
                        </li> -->
                    </ul>
                </div>
                <ul class="navbar-nav navbar-right">
                    <li class="dropdown dropdown-list-toggle"><a href="#" data-toggle="dropdown"
                            class="nav-link nav-link-lg message-toggle"><i data-feather="mail"></i>
                            <span class="badge headerBadge1">
                            </span> </a>
                        <div class="dropdown-menu dropdown-list dropdown-menu-right pullDown">
                            <div class="dropdown-header">
                                Messages
                                <div class="float-right">
                                    <a href="#">Mark All As Read</a>
                                </div>
                            </div>
                            <div class="dropdown-list-content dropdown-list-message">
                                <!-- Messages will be loaded here dynamically -->
                            </div>

                            <div class="dropdown-footer text-center">
                                <a href="chat.php">View All <i class="fas fa-chevron-right"></i></a>
                            </div>
                        </div>
                    </li>
                    <li class="dropdown dropdown-list-toggle"><a href="#" data-toggle="dropdown"
                            class="nav-link notification-toggle nav-link-lg"><i data-feather="bell" class="bell"></i>
                        </a>
                        <div class="dropdown-menu dropdown-list dropdown-menu-right pullDown">
                            <div class="dropdown-header">
                                Notifications
                                <div class="float-right">
                                    <a href="#">Mark All As Read</a>
                                </div>
                            </div>
                            <div class="dropdown-list-content dropdown-list-icons">
                                <a href="#" class="dropdown-item dropdown-item-unread"> <span
                                        class="dropdown-item-icon bg-primary text-white"> <i class="fas
												fa-code"></i>
                                    </span> <span class="dropdown-item-desc">Tomorrow is Public Holiday
                                    </span>
                                </a> <a href="#" class="dropdown-item"> <span
                                        class="dropdown-item-icon bg-info text-white"> <i class="far
												fa-user"></i>
                                    </span> <span class="dropdown-item-desc">Today is Assignment 4 Last Date
                                    </span>
                                </a> <a href="#" class="dropdown-item"> <span
                                        class="dropdown-item-icon bg-success text-white"> <i class="fas
												fa-check"></i>
                                    </span> <span class="dropdown-item-desc"> Check email for new messages!
                                    </span>

                            </div>
                            <div class="dropdown-footer text-center">
                                <a href="#">View All <i class="fas fa-chevron-right"></i></a>
                            </div>
                        </div>
                    </li>
                    <li class="dropdown"><a href="#" data-toggle="dropdown"
                            class="nav-link dropdown-toggle nav-link-lg nav-link-user"> <img alt="image"
                                src="uploads/logos/<?php echo htmlspecialchars($school['logo']); ?>"
                                class="user-img-radious-style"> <span class="d-sm-none d-lg-inline-block"></span></a>
                        <div class="dropdown-menu dropdown-menu-right pullDown">
                            <div class="dropdown-title">Hello Admin</div>
                            <a href="profile.php" class="dropdown-item has-icon"> <i class="far
										fa-user"></i> Profile
                            </a>
                            <a href="profile.php?#settings" class="dropdown-item has-icon"> <i class="fas fa-cog"></i>
                                Settings
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="logout.php" class="dropdown-item has-icon text-danger"> <i
                                    class="fas fa-sign-out-alt"></i>
                                Logout
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>
            <div class="main-sidebar sidebar-style-2">
                <aside id="sidebar-wrapper">
                    <div class="sidebar-brand" style="margin-top: 16px; padding-left: 10px;   height: fit-content;">
                        <a href="index.php">
                            <img alt="image" src="uploads/logos/<?php echo htmlspecialchars($school['logo']); ?>"
                                class="header-logo" style="width: 50px;border-radius: 50%;" />
                            <span class="logo-name"
                                style="font-size: 16px; font-weight: bold; margin-left: 10px;"><?php echo htmlspecialchars($school['school_name']); ?></span>
                        </a>
                    </div>
                    <ul class="sidebar-menu">
                        <!-- <li class="menu-header">Main</li> -->
                        <li class="dropdown ">
                            <a href="index.php" id="dashboard" class="nav-link"><i
                                    data-feather="monitor"></i><span>Dashboard</span></a>
                        </li>
                        <!-- <li class="dropdown">
                            <a href="#" class="menu-toggle nav-link has-dropdown"><i
                                    data-feather="bar-chart-2"></i><span>Graphs</span></a>
                            <ul class="dropdown-menu">
                                <li><a class="nav-link" href="widget-chart.php">Revenue / Cost Chart</a></li>
                                <li><a class="nav-link" href="widget-data.php">Student Profile Visualization</a></li>
                                <li><a class="nav-link" href="academic.php">Academic Reporting</a></li>
                                <li><a class="nav-link" href="socitiesclub.php">Societies Club</a></li>
                            </ul>
                        </li> -->

                        <li class="dropdown">
                            <a id="apps" href="#" class="menu-toggle nav-link has-dropdown"><i
                                    data-feather="command"></i><span>Apps</span></a>
                            <ul class="dropdown-menu">
                                <li><a class="nav-link" href="chat.php">Chat</a></li>
                                <!-- <li><a class="nav-link" href="calendar.php">Calendar</a></li> -->
                                <li><a class="nav-link" href="meeting_form.php">Meeting Scheduler</a></li>
                                <li><a class="nav-link" href="noticeboard.php">Digital Notice Board</a></li>
                                <li><a class="nav-link" href="students_list.php">Student</a></li>
                                <li><a class="nav-link" href="assign_task.php">Assign task</a></li>
                            </ul>
                        </li>
                        <li class="dropdown active">
                            <a id="attendance" href="Attendance.php" class="nav-link"
                                style="background-color: transparent !important; box-shadow: none !important; color: black !important;">
                                <i data-feather="edit" style="color: rgb(78, 77, 77) !important;"></i>
                                <span style="color: rgb(78, 77, 77) !important;">Attendance</span>
                            </a>
                        </li>

                        <li class="dropdown">
                            <a id="timetable" href="#" class="menu-toggle nav-link has-dropdown"><i
                                    data-feather="layout"></i><span>Time Table</span></a>
                            <ul class="dropdown-menu">
                                <li><a class="nav-link" href="timetable.php">Create Time Table</a></li>
                                <li><a class="nav-link" href="view_all_timetable.php">See Time Table</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a id="fee_type" href="#" class="menu-toggle nav-link has-dropdown"><i
                                    data-feather="layout"></i><span>Fee</span></a>
                            <ul class="dropdown-menu">
                                <li><a class="nav-link" href="fee_slip.php">Fee Slip</a></li>
                                <li><a class="nav-link" href="submit_student_fee.php">Submit Student Fee</a></li>
                                <li><a class="nav-link" href="fee_period_form.php">Fee Period</a></li>
                                <li><a class="nav-link" href="fee_strutter.php">Add Class Fee Plan</a></li>
                                <li><a class="nav-link" href="show_fee_structures.php">View Class Fee Plan</a></li>
                                <li><a class="nav-link" href="enroll_student_fee_plan.php">Student Fee Plan</a></li>
                                <li><a class="nav-link" href="fee_structure_view.php">All Students Fee
                                        Structure</a></li>
                                <li><a class="nav-link" href="fee_type.php">Fee Type</a></li>
                                <li><a class="nav-link" href="enroll_scholarship.php">Scholarship Form</a></li>
                                <li><a class="nav-link" href="load_scholarships.php">Scholarship</a></li>
                            </ul>
                        </li>
                        <!-- <li class="dropdown">
                            <a href="#" class="menu-toggle nav-link has-dropdown"><i
                                    data-feather="mail"></i><span>Email</span></a>
                            <ul class="dropdown-menu">
                                <li><a class="nav-link" href="email-inbox.php">Inbox</a></li>
                                <li><a class="nav-link" href="email-compose.php">Compose</a></li>
                                <li><a class="nav-link" href="email-read.php">read</a></li>
                            </ul>
                        </li> -->
                        <li class="dropdown">
                            <a href="#" id="facultyForm" class="menu-toggle nav-link has-dropdown"><i
                                    data-feather="layout"></i><span>Forms</span></a>
                            <ul class="dropdown-menu">
                                <li><a class="nav-link" href="faculty_registration.php">Faculty Registration</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" id="Managements" class="menu-toggle nav-link has-dropdown"><i
                                    data-feather="grid"></i><span>Managements</span></a>
                            <ul class="dropdown-menu">
                                <li><a class="nav-link" href="leaved.php">Leave Management</a></li>
                            </ul>
                        </li>

                    </ul>
                    </ul>

                    <!-- Add space above the bottom logo -->
                    <div class="sidebar-brand" style="margin-top: 10px;">
                        <a href="index.php" style="display: flex; align-items: center;">
                            <img alt="image" src="assets/img/Final Logo (1).jpg" class="header-logo"
                                style="height: 80px; width: auto;" />
                            <span class="logo-name"
                                style="font-size: 25px; font-weight: bold; margin-left: 10px; margin-top: 8px;">Lurniva</span>
                        </a>
                    </div>

                </aside>
            </div>