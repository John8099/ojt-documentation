<?php
$role = $user->role == "student" ? "student" : "admin";
?>
<header id="header" class="header fixed-top d-flex align-items-center">

  <div class="d-flex align-items-center justify-content-between">
    <a href="./" class="logo d-flex align-items-center">
      <img src="<?= $SERVER_NAME . "/assets/img/ojt.jpg" ?>" style="max-height: 50px;">
      <span class="d-none d-lg-block">Documentation</span>
    </a>
    <i class="bi bi-list toggle-sidebar-btn"></i>
  </div><!-- End Logo -->

  <nav class="header-nav ms-auto">
    <ul class="d-flex align-items-center">
      <?php if ($role != "student") : ?>
        <li class="nav-item dropdown">

          <a class="nav-link nav-icon" href="javascript:void(0)" id="notificationIcon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="bi bi-bell"></i>
            <?php
            $notificationCountQ = mysqli_query(
              $con,
              "SELECT * FROM `notification` WHERE admin_id='$user->id' and unread='0'"
            );
            $notificationCount = mysqli_num_rows($notificationCountQ);
            ?>
            <span class="badge bg-primary badge-number" id="badgeNotification"><?= $notificationCount == 0 ? null : $notificationCount ?></span>
          </a><!-- End Notification Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications" style="width: 321px;">
            <div id="notificationData"></div>
          </ul><!-- End Notification Dropdown Items -->

        </li>
      <?php endif; ?>

      <li class="nav-item dropdown pe-3">

        <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-toggle="dropdown">
          <img src="<?= "$SERVER_NAME/profile/" . ($user->avatar ? "$user->avatar" : "default.png") ?>" alt="Profile" class="rounded-circle" id="headerProfile">
          <span class="d-none d-md-block dropdown-toggle ps-2">
            <?= $fullName ?>
          </span>
        </a>

        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
          <li class="dropdown-header">
            <h6> <?= $fullName ?></h6>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>

          <li>
            <a class="dropdown-item d-flex align-items-center" href="<?= "$SERVER_NAME/pages/$role/users-profile" ?>">
              <i class="bi bi-person"></i>
              <span>My Profile</span>
            </a>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>

          <li>
            <a class="dropdown-item d-flex align-items-center" href="<?= "$SERVER_NAME/backend/nodes.php?action=logout" ?>">
              <i class="bi bi-box-arrow-right"></i>
              <span>Sign Out</span>
            </a>
          </li>

        </ul><!-- End Profile Dropdown Items -->
      </li><!-- End Profile Nav -->

    </ul>
  </nav><!-- End Icons Navigation -->

</header>