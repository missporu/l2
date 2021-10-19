        <div class="clearfix"></div>
        <div class="col-xs-12 text-center">
            <hr>
        </div>
        <div class="clearfix"></div>
        <div class="col-xs-12 text-center"><?
            if ($users->cookiesValid()) { ?>
                <a href="/">Главная</a> | <a href="index.php?s=pr">Правила</a><?
            } ?>
        </div>
        <div class="clearfix"></div>
        <div class="col-xs-12 text-center">
            <? $kolvo = $sql->getOne("select count(id) from users where online > ?i", time()-600); ?>
            <p class="text-info"><?
                if ($users->cookiesValid()) { ?>
                    <a href="index.php?s=online">Online (<?= $kolvo ?>)</a><?
                } else { ?>
                    Online (<?= $kolvo ?>)<?
                } ?>
            </p>
        </div>
        <div class="col-xs-12 text-center">
            <script>
                function moscowTime() {
                    var d = new Date();
                    d.setHours( d.getHours() + 3, d.getMinutes() + d.getTimezoneOffset() );
                    return d.toTimeString().substring(0, 8);
                }
                onload = function () {
                    setInterval(function () {
                        document.getElementById("server_time").innerHTML = moscowTime();
                    }, 100);
                }
            </script>
            <span id="server_time"><?= $site->getTime() ?></span>  | <?= $site->getDate();
            if ($users->cookiesValid()) { ?>
                <br><a href="index.php?s=exit">Выход</a><?
            } ?>
        </div>
    </div>
</div>
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <script src="//getbootstrap.com/assets/js/ie10-viewport-bug-workaround.js"></script>
</body>
</html><?php
        //var_dump($users->cookiesValid());
        exit();