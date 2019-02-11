<div class="container">
    <div class="row">
        <div class="col-md-6">
            <h1 class="mt-5">Show Theme Details</h1>
            <?php Helper::getMessage(); ?>
            <br>
            <h4><?=$base_theme['theme_name'];?></h4>
            <table class="table table-borderless table-condensed">
                <tbody>
                <tr>
                    <td>Slug</td>
                    <td><?=$base_theme['slug'];?></td>
                </tr>
                <tr>
                    <td>Author</td>
                    <td><?=$base_theme['author'];?></td>
                </tr>
                <tr>
                    <td>Author Homepage</td>
                    <td><?=$base_theme['author_homepage'];?></td>
                </tr>
                <tr>
                    <td>Info URL</td>
                    <td><?=$base_theme['url'];?></td>
                </tr>
                <tr>
                    <td>Section Description</td>
                    <td><?=$base_theme['section_description'];?></td>
                </tr>
                <tr>
                    <td>Last updated</td>
                    <td><?=$base_theme['updated_at'];?></td>
                </tr>
                <tr>
                    <td rowspan="2">
                        <a href="/theme/base/<?=$base_theme['id'];?>/edit" class="btn btn-primary">Edit this theme</a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>