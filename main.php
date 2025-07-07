<div class="website-info" style="margin-right: 20px">
    <h1>Hello <?= wp_get_current_user()->display_name ?>,</h1>
    <p>The plugin "Website Info" has been successfully installed into your WordPress website. Now copy just the API key and connect this website to your account in the "Website Info" app (<a href="https://website-info.onrender.com">https://website-info.onrender.com</a>).</p>
    <br><p>----------</p><br>

    <h1>Website Info</h1>
    <div class="tables">
        <div>
            <h2>Versions</h2>
            <table>
                <thead>
                <tr><th>Name</th><th>Version</th></tr>
                </thead>

                <tbody>
                <tr><td>WordPress</td><td><?= get_bloginfo('version') ?></td></tr>
                <tr><td>PHP</td><td><?= PHP_VERSION ?></td></tr>
                </tbody>
            </table>
        </div>

        <div>
            <h2>Themes</h2>
            <table>
                <thead>
                <tr><th>Name</th><th>Version</th><th>Description</th><th>Author</th></tr>
                </thead>

                <tbody>
                <?php
                    $themes = website_info_themes();
                    foreach ($themes as $theme) {
                        echo '<tr>
                            <td>' . $theme['name'] . '</td>
                            <td>' . $theme['version'] . '</td>
                            <td>' . $theme['description'] . '</td>
                            <td>' . $theme['author'] . '</td>
                        </tr>';
                    }
                ?>
                </tbody>
            </table>
        </div>
    </div>

    <h2>Plugins</h2>
    <table>
        <thead>
        <tr><th>Name</th><th>Version</th><th>Description</th><th>Author</th><th>Condition</th></tr>
        </thead>

        <tbody>
        <?php
            $plugins = website_info_plugins();
            foreach ($plugins as $plugin) {
                echo '<tr>
                    <td>' . $plugin['name'] . '</td>
                    <td>' . $plugin['version'] . '</td>
                    <td>' . $plugin['description'] . '</td>
                    <td><a href="' . $plugin['author_uri'] . '">' . $plugin['author'] . '</a></td>
                    <td>' . ($plugin['state'] ? 'Activated' : 'Deactivated') . '</td>
                </tr>';
            }
        ?>
        </tbody>
    </table>
    <br><br><p>----------</p><br>

    <h1>API key</h1>
    <p><code><?= get_option('WEBSITE_INFO_API') ?></code></p>
    <br><p>----------</p>
</div>

<style>
    h1 {
        margin-top: 24px;
        font-size: 24px;
        font-weight: 400;
    }

    h2 {
        font-size: 20px;
        font-weight: 400;
    }

    p {
        font-size: 14px;
    }

    table {
        background-color: #fff;
        border: 1px solid #c3c4c7;
    }

    th, td {
        height: 40px;
        padding: 0 10px;
    }

    td {
        background-color: #fff;
        border-top: 1px solid #c3c4c7;
    }

    .website-info th, .website-info td {
        font-size: 14px !important;
    }

    .tables {
        display: flex;
        justify-content: space-around;
    }
</style>