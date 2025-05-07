            </div> <!-- End main content -->
        </div> <!-- End row -->
    </div> <!-- End container-fluid -->

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Custom JS -->
    <script>
        // Add active class to current nav item
        $(document).ready(function() {
            var current = location.pathname;
            $('.nav-link').each(function() {
                var $this = $(this);
                if ($this.attr('href') === current) {
                    $this.addClass('active');
                }
            });
        });
    </script>
</body>
</html> 