<aside id="sidebar" class="sidebar">

  <ul class="sidebar-nav" id="sidebar-nav">
    <?php
    include_once("links.php");

    $navBarLinks = array_filter(
      $links,
      fn ($val) => in_array($user->role, $val["allowedViews"]),
      ARRAY_FILTER_USE_BOTH
    );
    foreach ($navBarLinks as $key => $value) :
      if ($value["title"] == "Attendance") :
    ?>
        <li class="nav-item">
          <a class="nav-link " href="#" style="background: <?= $value["url"] == str_replace(".php", "", $self) ? "#f6f9ff" : "none"  ?>;" onclick="return window.location.replace('<?= $SERVER_NAME . '/pages/student-attendance?office=' . $user->office_account_id ?>')">
            <i class="bi bi-<?= $value["config"]["icon"] ?>"></i>
            <span><?= $value["title"] ?></span>
          </a>
        </li>

      <?php else : ?>
        <li class="nav-item">
          <a class="nav-link " href="<?= $value["url"] ?>" style="background: <?= $value["url"] == str_replace(".php", "", $self) ? "#f6f9ff" : "none"  ?>;">
            <i class="bi bi-<?= $value["config"]["icon"] ?>"></i>
            <span><?= $value["title"] ?></span>
          </a>
        </li>
    <?php
      endif;
    endforeach;
    ?>
  </ul>

</aside>