<style>
    @media (min-width: 800px) {
    .pull-right-md {
        float: right;
    }
}
</style>
<div class="form-inline pull-right-md">
    <form name='rows' method="post" action="<?php echo $settings['url'] . $currentPage ?>">

            <input id='searchText' class="form-control" type='text' name='searchText' placeholder="<?php if (isset($search)) echo $search ?>">
            <input style='margin-right: 10px;' class='btn btn-primary' type='button' name='search' onclick='searchpage();' value='<?php echo $lang['search'] ?>'>

            <select id='items' class="form-control" name='items'>
                <option value="5" <?php if ('5' == $_SESSION['items']) echo 'selected'; ?>>5</option>
                <option value="10" <?php if ('10' == $_SESSION['items']) echo 'selected'; ?>>10</option>
                <option value="15" <?php if ('15' == $_SESSION['items']) echo 'selected'; ?>>15</option>
                <option value="25" <?php if ('25' == $_SESSION['items']) echo 'selected'; ?>>25</option>
                <option value="50" <?php if ('50' == $_SESSION['items']) echo 'selected'; ?>>50</option>
            </select>
            <input style='margin-right: 10px;' class='btn btn-primary' name='update' type="submit" value="<?php echo $lang['itemsPP'] ?>">

    </form>
</div>