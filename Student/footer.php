
  </div> <!-- Close content-wrapper -->

  <footer class="footer py-3 bg-light text-center">
    <div class="container">
      <strong>ExamHub &copy; <?php echo date('Y'); ?></strong>
      All rights reserved.
    </div>
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Custom JS for sidebar toggle -->
  <script>
    // Toggle sidebar on mobile
    document.addEventListener('DOMContentLoaded', function() {
      const sidebarToggle = document.querySelector('.sidebar-toggle');
      const sidebar = document.getElementById('sidebar');
      
      if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function() {
          sidebar.classList.toggle('show');
        });
      }
      
      // Close sidebar when clicking outside on mobile
      document.addEventListener('click', function(event) {
        if (window.innerWidth <= 992) {
          if (!sidebar.contains(event.target) && !sidebarToggle.contains(event.target)) {
            sidebar.classList.remove('show');
          }
        }
      });
    });
  </script>
</body>
</html>
