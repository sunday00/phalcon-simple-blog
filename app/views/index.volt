<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Phalcon PHP Framework</title>
        <link rel="shortcut icon" type="image/x-icon" href="<?php echo $this->url->get('img/favicon.ico')?>"/>

        <link rel="stylesheet" href="dist/styles.5c32a3d8.css">
        <script src="dist/styles.5c32a3d8.js"></script>
        <link rel="stylesheet" href="dist/resources.e31bb0bc.css">
        
    </head>
    <body class="min-h-screen flex flex-col">
        <header class="w-screen xl flex-none">
            {% include "layouts/header.volt" %}
        </header>
        <div class="w-screen xl flex flex-grow flex-1">
            <div class="flex flex-1">
                {% include "layouts/left.volt" %}
                <div class="flex-grow content">
                    <?php echo $this->getContent(); ?>
                </div>
            </div>
        </div>
        <script src="dist/resources.e31bb0bc.js"></script>
        <footer class="flex-none bg-{{ theme }}-accent text-{{ theme }}-light text-center p-6">
            {% include "layouts/footer.volt" %}
        </footer>
    </body>
</html>
