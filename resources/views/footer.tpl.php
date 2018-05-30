<div class="container" style="margin-top:30px;">
    <div class="row">
        <div class="col-md-12">
            <hr>
            <small>
                Visit me: &nbsp;
                <a href="https://github.com/KaiserWerk" target="_blank">@Github</a> &nbsp;
                <a href="https://kaiserrobin.eu" target="_blank">@WWW</a>
            </small>
            <br>
            <small>
                Script executed in <?php echo round($StopwatchHelper->stop()->getIntervalSeconds(), 6); ?>s
            </small>
        </div>
    </div>
</div>
<script src="/assets/jquery/jquery.min.js"></script>
<script src="/assets/popper/popper.min.js"></script>
<script src="/assets/bootstrap/js/bootstrap.min.js"></script>

<script>
    $(document).ready(function(){
        $('[data-tooltip]').tooltip();
    });
</script>
<!-- Script execution time: <?php echo $StopwatchHelper->stop()->getIntervalSeconds(); ?> -->
</body>
</html>
