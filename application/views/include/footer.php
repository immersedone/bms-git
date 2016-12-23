 <!-- footer content -->
        <footer>
          <div class="pull-right">
            Created By BMS Project Team</a>
          </div>
          <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
      </div>
    </div>

    <!-- jQuery -->
    <!--<script src="/assets/vendors/jquery/dist/jquery.min.js"></script>-->
    
    <script src="/assets/js/csrf.js"></script>
    <!-- Bootstrap -->
    <script src="/assets/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="/assets/vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="/assets/vendors/nprogress/nprogress.js"></script>
    <!-- Chart.js -->
    <script src="/assets/vendors/Chart.js/dist/Chart.min.js"></script>
    <!-- gauge.js -->
    <script src="/assets/vendors/gauge.js/dist/gauge.min.js"></script>
    <!-- bootstrap-progressbar -->
    <script src="/assets/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
    <!-- iCheck -->
    <script src="/assets/vendors/iCheck/icheck.min.js"></script>
    <!-- Skycons -->
    <script src="/assets/vendors/skycons/skycons.js"></script>
    <!-- Flot -->
    <script src="/assets/vendors/Flot/jquery.flot.js"></script>
    <script src="/assets/vendors/Flot/jquery.flot.pie.js"></script>
    <script src="/assets/vendors/Flot/jquery.flot.time.js"></script>
    <script src="/assets/vendors/Flot/jquery.flot.stack.js"></script>
    <script src="/assets/vendors/Flot/jquery.flot.resize.js"></script>
    <!-- Flot plugins -->
    <script src="/assets/vendors/flot.orderbars/js/jquery.flot.orderBars.js"></script>
    <script src="/assets/vendors/flot-spline/js/jquery.flot.spline.min.js"></script>
    <script src="/assets/vendors/flot.curvedlines/curvedLines.js"></script>
    <!-- DateJS -->
    <script src="/assets/vendors/DateJS/build/date.js"></script>
    <!-- JQVMap -->
    <script src="/assets/vendors/jqvmap/dist/jquery.vmap.js"></script>
    <script src="/assets/vendors/jqvmap/dist/maps/jquery.vmap.world.js"></script>
    <script src="/assets/vendors/jqvmap/examples/js/jquery.vmap.sampledata.js"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="/assets/js/moment/moment.min.js"></script>
    <script src="/assets/js/datepicker/daterangepicker.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="/assets/js/custom.min.js"></script>

    <script type="text/javascript">
        $('input.ajaxDialogSetting').change(function() {
            var csrf_token = Cookies.get("csrf_cookie");
            $.ajax({
                url: "<?php echo base_url() . 'user/update_DialogSetting'; ?>",
                type: "POST",
                data: { "csrf_token": csrf_token},
                dataType: "json",
                success: function(data) {
                    location.reload();
                }
            });
        });
    </script>
  </body>
</html>