<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>

  <title> Responsive Sidebar Menu </title>
  
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Boxicons CDN Link -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../../css/sideNavbar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../../css/StaffHomePage.css?v=<?php echo time(); ?>">

    <script src="https://kit.fontawesome.com/65712a75e6.js" crossorigin="anonymous"></script>

</head>

<body>

    <div class="sidebar open">
        <div class="logo-details">

            <img class="bitlogo" src="../../assets/bitlogo_transparent.png" alt="profileImg">
            <div class="logo_name">LMS</div>
            <i class='bx bx-menu' id="btn"></i>

        </div>
        
        <ul class="nav-list">

            <li>
                <i class='bx bx-search'></i>
                <input type="text" placeholder="Search...">
                <span class="tooltip">Search</span>
            </li>

            <li>
                <a href="../../pages/Admin/dashboard.php">
                    <i class='bx bx-grid-alt'></i>
                    <span class="links_name">Dashboard</span>
                </a>
                <span class="tooltip">Dashboard</span>
            </li>

            <li>
                <a href="../../pages/Admin/manageMasterData.php">
                    <i class="fa-solid fa-server"></i>
                    <span class="links_name">Manage Master Data</span>
                </a>
                <span class="tooltip">Manage Master Data</span>
            </li>

            <li>
                <a href="../../pages/Admin/manageDepartment.php">
                    <i class="fa-solid fa-building-user"></i>
                    <span class="links_name">Manage Department</span>
                </a>
                <span class="tooltip">Manage Department</span>
            </li>

            <li>
                <a href="../../pages/Admin/manageEmployees.php">
                    <i class="fa-solid fa-users"></i>
                    <span class="links_name">Manage Employees</span>
                </a>
                <span class="tooltip">Manage Employees</span>
            </li>



            <li>
                <a href="../../pages/Admin/manageLeaves.php">
                    <i class="fas fa-sticky-note"></i>
                    <span class="links_name">Manage Leaves</span>
                </a>
                <span class="tooltip">Manage Leaves</span>
            </li>

            <li>
                <a href="../../pages/Admin/manageHolidays.php">
                    <i class="fas fa-calendar"></i>
                    <span class="links_name">Manage Holidays</span>
                </a>
                <span class="tooltip">Manage Holidays</span>
            </li>

            <li class="profile">
                <div class="profile-details">
                    <!-- <img src="assets/ganesh.jpg" alt="profileImg"> -->
                    <img src="../../assets/ganesh.jpg" alt="profileImg">
                    <div class="name_job">
                        <div class="name"><?php echo $_SESSION['fullname']?></div>
                        <div class="job">ID: <?php echo $_SESSION['id']?></div>
                    </div>
                </div>
                <a href="../logout.php" class="logouthyperlink"><i class="fa-solid fa-right-from-bracket"
                        id="log_out"></i></a>
                <!-- <i class='bx bx-log-out' id="log_out" ></i> -->
            </li>

        </ul>
    </div>

    <script src="../../js/sidebarScript.js"></script>

</body>

</html>