<html>
    <head>
        <title>GoonJS Micro-framework</title>
    </head>
    <body style="text-align:center">
        <h1>Welcome GoonJS Micro-framework</h1>
        <h4>https://github.com/goonjs/goonjs-slim-eloquent</h4>
        <h3>Set your configuration at <code>framework/app/bootstrap/config.php</code></h3>
        <?php if ($userData): ?>
            <h4>You have logged in: <?php echo $userData['username'] ?></h4>
        <?php endif ?>
    </body>
</html>
