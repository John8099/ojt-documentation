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
    ?>
      <li class="nav-item">
        <a class="nav-link " href="<?= $value["url"] ?>" style="background: <?= $value["url"] == str_replace(".php", "", $self) ? "#f6f9ff" : "none"  ?>;">
          <i class="bi bi-<?= $value["config"]["icon"] ?>"></i>
          <span><?= $value["title"] ?></span>
        </a>
      </li>

    <?php
    endforeach;
    ?>
  </ul>

</aside>