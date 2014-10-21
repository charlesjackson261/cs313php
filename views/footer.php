<!-- FOOTER -->
<div class="container">

    <footer>
        <p class="pull-right"><a href="#">Back to top</a></p>
        <p>&copy; 2014 Company, Inc. &middot; <a href="#">Privacy</a> &middot; <a href="#">Terms</a></p>
    </footer>

</div><!-- /.container -->    

<?php

global $debug, $log, $javascript;

if ($debug) {
?>
<div class="container">
    <div class="alert alert-dismissable alert-success">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>Debug: </strong><br>
        <?php
    echo $log;
        ?>
    </div>
</div>
<?php
}

?>

</body>

<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

<script src="js/app.js"></script>

<!-- create all of the dynamic content -->
<?php
    foreach ($javascript as $java)
    {
        echo '<script type="text/javascript">' . $java . '</script>' ;
    }
?>

</html>
