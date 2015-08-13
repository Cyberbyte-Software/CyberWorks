<?php
$total_pages = ceil($total_records / $_SESSION['items']);
if ($total_pages > 1) { ?>
<center>
<nav>
  <ul class="pagination ">
    <li>
      <a href="?page=1" aria-label="First">
        <span aria-hidden="true">&laquo;</span>
      </a>
    </li>
    <?php
    if ($total_pages > 5) {
        $start = $pageNum - 2;
        $end = $pageNum + 2;
        if ($pageNum < 4) $start = 1;
        if ($pageNum < 2) $end += 1;
        if ($pageNum < 3) $end += 1;
        
        if ($pageNum > $total_pages - 2) {$start -= 1; $end -= 1; }
        if ($pageNum > $total_pages - 1) {$start -= 1; $end -= 1; }
    } else {
        $start = 1;
        $end = $total_pages;
    }
    
    for ($i = $start; $i <= $end; $i++) {
        echo '<li';
        if ($i == $pageNum) echo  ' class="active"';
        echo '><a href="?page=' . $i . '">' . $i . '</a></li>';
    }; ?>

    <li>
      <a href="?page=<?php echo $total_pages ?>" aria-label="Last">
        <span aria-hidden="true">&raquo;</span>
      </a>
    </li>
  </ul>
</nav>
</center>
<?php } 