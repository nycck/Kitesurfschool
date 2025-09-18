</main>

    <!-- Footer -->
    <footer class="bg-dark text-light py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5><i class="fas fa-wind me-2"></i>Windkracht-12</h5>
                    <p>De kitesurfschool van Nederland. Al 8 jaar ervaring in het geven van professionele kitesurflessen aan de Nederlandse kust.</p>
                    <div class="social-links">
                        <a href="#" class="text-light me-3"><i class="fab fa-facebook fa-2x"></i></a>
                        <a href="#" class="text-light me-3"><i class="fab fa-instagram fa-2x"></i></a>
                        <a href="#" class="text-light me-3"><i class="fab fa-youtube fa-2x"></i></a>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4">
                    <h5>Onze Locaties</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-map-marker-alt me-2"></i>Zandvoort</li>
                        <li><i class="fas fa-map-marker-alt me-2"></i>Muiderberg</li>
                        <li><i class="fas fa-map-marker-alt me-2"></i>Wijk aan Zee</li>
                        <li><i class="fas fa-map-marker-alt me-2"></i>IJmuiden</li>
                        <li><i class="fas fa-map-marker-alt me-2"></i>Scheveningen</li>
                        <li><i class="fas fa-map-marker-alt me-2"></i>Hoek van Holland</li>
                    </ul>
                </div>
                
                <div class="col-md-4 mb-4">
                    <h5>Contact</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-envelope me-2"></i>info@kitesurfschool-windkracht12.nl</li>
                        <li><i class="fas fa-phone me-2"></i>06-12345678</li>
                        <li><i class="fas fa-clock me-2"></i>Ma-Zo: 09:00 - 18:00</li>
                    </ul>
                    
                    <?php if (!isLoggedIn()): ?>
                    <div class="mt-3">
                        <a href="<?= URLROOT ?>/auth/register" class="btn btn-primary">
                            <i class="fas fa-user-plus me-1"></i>Registreer Nu
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <hr class="my-4">
            
            <div class="row align-items-center">
                <div class="col-md-6">
                    <small>&copy; <?= date('Y') ?> Kitesurfschool Windkracht-12. Alle rechten voorbehouden.</small>
                </div>
                <div class="col-md-6 text-md-end">
                    <small>
                        <a href="#" class="text-light text-decoration-none me-3">Privacy Policy</a>
                        <a href="#" class="text-light text-decoration-none">Algemene Voorwaarden</a>
                    </small>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    
    <!-- Custom JavaScript -->
    <script>
        // Confirm delete actions
        function confirmDelete(message) {
            return confirm(message || 'Weet je zeker dat je dit wilt verwijderen?');
        }
        
        // Form validation helper
        function validateRequired(formId) {
            var form = document.getElementById(formId);
            var requiredFields = form.querySelectorAll('[required]');
            var isValid = true;
            
            requiredFields.forEach(function(field) {
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                }
            });
            
            return isValid;
        }
    </script>
  </body>
</html>