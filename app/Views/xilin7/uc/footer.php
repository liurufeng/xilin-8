

<footer>

    <div class="bottom-line">
        <div class="container">
            <span> &copy;Copyright <?= date('Y') ?> <!--<span id="current_year"> </span>--> Xilin Northshore Chinese School</span>
            <ul>
                <li><a title="" href="/">HOME</a></li>
                <li><a title="" href="/">    </a></li>
            </ul>
        </div>
    </div>
    <a href="#" class="scrollToTop"><i class="ti ti-arrow-circle-up"></i></a>
</footer>

<!-- Script -->
<script type="text/javascript" src="<?php echo base_url();?>/<?=$_SESSION['tm']?>js/modernizr.js"></script><!-- Modernizer -->

<script type="text/javascript" src="<?php echo base_url();?>/<?=$_SESSION['tm']?>js/html5lightbox.js"></script><!-- HTML -->
<script type="text/javascript" src="<?php echo base_url();?>/<?=$_SESSION['tm']?>js/scrolly.js"></script><!-- Parallax -->
<script type="text/javascript" src="<?php echo base_url();?>/<?=$_SESSION['tm']?>js/price-range.js"></script><!-- Parallax -->
<script type="text/javascript" src="<?php echo base_url();?>/<?=$_SESSION['tm']?>js/script.js"></script><!-- Script -->

<script src="<?php echo base_url();?>/<?=$_SESSION['tm']?>js/rs-plugin/js/jquery.themepunch.tools.min.js"></script>
<script src="<?php echo base_url();?>/<?=$_SESSION['tm']?>js/rs-plugin/js/jquery.themepunch.revolution.js"></script>

<script src="<?php echo base_url();?>/<?=$_SESSION['tm']?>js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url();?>/<?=$_SESSION['tm']?>js/jquery.cookie.js"></script>


<script type="text/javascript">
    $(document).ready(function () {
        "use strict";
        jQuery('.tp-banner').show().revolution({

        });


    });
</script>


<script type="text/javascript">
    $(document).ready(function () {
        "use strict";

        $(function () {
            $('#toggle-widget .content').hide();
            $('#toggle-widget h2:first').addClass('active').next().slideDown('slow');
            $('#toggle-widget h2').on("click", function () {
                if ($(this).next().is(':hidden')) {
                    $('#toggle-widget h2').removeClass('active').next().slideUp('slow');
                    $(this).toggleClass('active').next().slideDown('slow');
                }
            });
        });

        $("a[href^='#']").click(function(e) {
            e.preventDefault();

            if($(this)[0].hasAttribute("data-parent")){
                return;
            }

            var position = $($(this).attr("href")).offset().top - 160;

            $("body, html").animate({
                scrollTop: position
            }, 2000 );
        });


        //checking screen size for mobile
        var $window = $(window);
        var $pane1 = $('#collapseOne');
        var $pane2 = $('#collapseTwo');
        var $pane4 = $('#collapseFour');

        function checkWidth() {
            var windowsize = $window.width();
            if (windowsize <= 680) {
                $pane1.addClass('panel-collapse collapse');
                $pane2.addClass('panel-collapse collapse');
                $pane4.addClass('panel-collapse collapse');

                $(".panel > a").each(function (index) {
                    $(this).attr('data-toggle', 'collapse');
                });
            } else {
                $pane1.removeClass('panel-collapse collapse in');
                $pane2.removeClass('panel-collapse collapse in');
                $pane4.removeClass('panel-collapse collapse in');

                $(".panel > a").each(function (index) {
                    $(this).attr('data-toggle', '');
                });
            }
        }
        // Execute on load
        checkWidth();
        // Bind event listener
        $(window).resize(checkWidth);
    });


</script>

<div class="overlay"></div>

<!--<script>
    document.getElementById("current_year").innerHTML = new Date().getFullYear();
</script>-->
</body>
</html>