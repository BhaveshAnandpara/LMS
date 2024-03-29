<!DOCTYPE html>
<!-- Created by CodingLab |www.youtube.com/c/CodingLabYT-->

<html lang="en" dir="ltr">

<head>
  <meta charset="UTF-8">
  <title> Responsive Sidebar Menu </title>
  <!-- Boxicons CDN Link -->
  <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
  <script src="https://kit.fontawesome.com/65712a75e6.js" crossorigin="anonymous"></script>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../../css/common.css">
  <link rel="stylesheet" href="../../css/sideNavbar.css?v=<?php echo time(); ?>">
</head>

<body>
  <!-- Sidebar  -->
  <div class="sidebar open">
    <div class="logo-details">
      <img class="bitlogo" src="../../assets/bitlogo_transparent.png" alt="profileImg">
      <div class="logo_name">LMS</div>
      <i class='bx bx-menu' id="btn"></i>
    </div>
    <!-- Links for Navbar  -->
    <ul class="nav-list">
      <li>
        <i class='bx bx-search'></i>
        <input type="text" placeholder="Search...">
        <span class="tooltip">Search</span>
      </li>
      <li>
        <a href="../../pages/HOD/dashboard.php">
          <i class='bx bx-grid-alt'></i>
          <span class="links_name">Dashboard</span>
        </a>
        <span class="tooltip">Dashboard</span>
      </li>
      <li>
        <a href="../../pages/HOD/my_team.php">
          <i class="fas fa-user"></i>
          <span class="links_name">My Team</span>
        </a>
        <span class="tooltip">My Team</span>
      </li>
      <li>
        <a href="../../pages/HOD/leave_request.php">
          <i class="fa-solid fa-address-card"></i>
          <span class="links_name">Leave Requests</span>
        </a>
        <span class="tooltip">Leave Requests</span>
      </li>


      <!-- code for Log Out  -->
      <li class="profile">
        <div class="profile-details">
          <!-- <img src="assets/ganesh.jpg" alt="profileImg"> -->
          <img  id="profile"  alt="profileImg">
          <div class="name_job">
            <p class="name"><?php echo $_SESSION['fullName'] ?></p>
            <div class="job">Emp ID : <?php echo $_SESSION['employeeID'] ?></div>
          </div>
        </div>
        <a href="../../includes/logout.php" class="logouthyperlink" ><i  style="cursor : pointer;" class="fa-solid fa-right-from-bracket" id="log_out"></i></a>
      </li>
    </ul>
  </div>
  <script src="../../js/sidebarScript.js"></script>

  <script>

    if( localStorage.getItem('profile') === undefined ) document.getElementById('profile').src = "https://ik.imagekit.io/gmarvquw2/user.png?updatedAt=1703056838757"
    document.getElementById('profile').src = localStorage.getItem('profile')

  </script>



</body>

</html>