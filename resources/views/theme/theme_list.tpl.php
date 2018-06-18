<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mt-5">Base Theme list</h1>
            <?php Helper::getMessage(); ?>
            <br>
            <table class="table table-condense table-bordered table-hover">
                <tbody>
                <?php
                foreach ($base_themes as $base_theme) {
                    echo '<tr>';
                    echo '<td colspan="7">' . $base_theme['theme_name'] . ' &nbsp; <small><i>' . $base_theme['slug'] . '</i></small></td>';
                    echo '<td>Updated: ' . (new \DateTime($base_theme['last_updated']))->format('Y-m-d') . '</td>';
                    echo '<td>';
                    echo '<a href="/theme/base/' . $base_theme['id'] . '/edit">Edit</a>';
                    echo ' / <a href="/theme/version/' . $base_theme['id'] . '/list">Show Versions</a>';
                    echo ' / <a href="/theme/base/' . $base_theme['id'] . '/remove" onclick="return confirm(\'Continue?\');">Remove</a>';
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>