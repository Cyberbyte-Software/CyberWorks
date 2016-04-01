<style>
    @media (min-width: 800px) {
    .pull-right-md {
        float: right;
    }
}
</style>

<div class="form-inline pull-right-md">
    <form id="search" name='rows' method="post" action="<?php echo $settings['url'] . $currentPage ?>">

            <input id='searchText' class="form-control" type='text' name='searchText' placeholder="<?php if (isset($search)) echo $search ?>">
            <input id="searchButton" style='margin-right: 10px;' class='btn btn-primary' type='button' name='search' onclick='searchpage();' value='<?php echo $lang['search'] ?>'>
    </form>
    <select style="margin-right:15px;" onchange="if (this.value) window.location.href='?items='+this.value" id='items' class="form-control" name='items'>
        <?php
        foreach ($settings['item'] as $item) {
            echo '<option value="'.$item.'"';
            if ($item == $_SESSION['items']) echo ' selected';
            echo '>'.$item.'</option>';
        }
        ?>
    </select>
</div>
<script>
    document.getElementById('searchText').addEventListener('keypress', function(event) {
        if (event.keyCode == 13) {
            event.preventDefault();
            document.getElementById('searchButton').click();
        }
    });
</script>
