<?php require_once APPROOT . '/views/includes/header.php'; ?>

<div class="container py-5">
    <!-- Hero Section -->
    <section class="text-center mb-5">
        <h1 class="display-4 fw-bold text-primary-gradient mb-4">
            Contact
        </h1>
        <p class="lead">
            Neem contact op voor vragen, reserveringen of advies over kitesurfen
        </p>
    </section>

    <div class="row">
        <!-- Contact Informatie -->
        <div class="col-lg-6 mb-5">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Contact Gegevens
                    </h4>
                </div>
                <div class="card-body">
                    <div class="contact-item mb-4">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-user-tie fa-2x text-primary me-3"></i>
                            <div>
                                <h5>Eigenaar</h5>
                                <p class="mb-0">Terence Olieslager</p>
                                <small class="text-muted">Oprichter & Eigenaar Windkracht-12</small>
                            </div>
                        </div>
                    </div>

                    <div class="contact-item mb-4">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-envelope fa-2x text-primary me-3"></i>
                            <div>
                                <h5>Email</h5>
                                <p class="mb-0">
                                    <a href="mailto:info@kitesurfschool-windkracht12.nl" class="text-decoration-none">
                                        info@kitesurfschool-windkracht12.nl
                                    </a>
                                </p>
                                <small class="text-muted">We reageren binnen 24 uur</small>
                            </div>
                        </div>
                    </div>

                    <div class="contact-item mb-4">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-phone fa-2x text-primary me-3"></i>
                            <div>
                                <h5>Telefoon</h5>
                                <p class="mb-0">
                                    <a href="tel:0612345678" class="text-decoration-none">06-12345678</a>
                                </p>
                                <small class="text-muted">Dagelijks bereikbaar van 09:00 - 18:00</small>
                            </div>
                        </div>
                    </div>

                    <div class="contact-item mb-4">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-map-marker-alt fa-2x text-primary me-3"></i>
                            <div>
                                <h5>Locatie</h5>
                                <p class="mb-0">Utrecht (Nederland)</p>
                                <small class="text-muted">Lessen op 6 locaties langs de Nederlandse kust</small>
                            </div>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-clock fa-2x text-primary me-3"></i>
                            <div>
                                <h5>Openingstijden</h5>
                                <ul class="list-unstyled mb-0">
                                    <li><strong>Maandag - Vrijdag:</strong> 09:00 - 18:00</li>
                                    <li><strong>Weekend:</strong> 08:00 - 19:00</li>
                                    <li><strong>Feestdagen:</strong> Op afspraak</li>
                                </ul>
                                <small class="text-muted">Lestijden zijn flexibel en afhankelijk van wind/weer</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Formulier -->
        <div class="col-lg-6 mb-5">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-secondary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-paper-plane me-2"></i>Stuur ons een Bericht
                    </h4>
                </div>
                <div class="card-body">
                    <form id="contactForm" action="#" method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">Naam *</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Telefoon</label>
                            <input type="tel" class="form-control" id="phone" name="phone">
                        </div>

                        <div class="mb-3">
                            <label for="subject" class="form-label">Onderwerp *</label>
                            <select class="form-control" id="subject" name="subject" required>
                                <option value="">Kies een onderwerp...</option>
                                <option value="algemene-vraag">Algemene vraag</option>
                                <option value="reservering">Reservering / Beschikbaarheid</option>
                                <option value="lespakket-advies">Lespakket advies</option>
                                <option value="groepsles">Groepsles / Bedrijfsuitje</option>
                                <option value="klacht">Klacht</option>
                                <option value="compliment">Compliment</option>
                                <option value="anders">Anders</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label">Bericht *</label>
                            <textarea class="form-control" id="message" name="message" rows="5" required
                                      placeholder="Beschrijf je vraag of opmerking..."></textarea>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="privacy" name="privacy" required>
                                <label class="form-check-label" for="privacy">
                                    Ik ga akkoord met het <a href="#" class="text-primary">privacy beleid</a> *
                                </label>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-paper-plane me-2"></i>Bericht Versturen
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ Section -->
    <section class="mb-5">
        <h2 class="text-center mb-5">Veelgestelde Vragen</h2>
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="accordion" id="contactFaqAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#contactFaq1">
                                Hoe kan ik een les reserveren?
                            </button>
                        </h2>
                        <div id="contactFaq1" class="accordion-collapse collapse show" data-bs-parent="#contactFaqAccordion">
                            <div class="accordion-body">
                                Je kunt een les reserveren door je te registreren op onze website en vervolgens inloggen om een reservering te maken. Je kunt ook contact met ons opnemen via telefoon of email.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#contactFaq2">
                                Wat als ik mijn les moet annuleren?
                            </button>
                        </h2>
                        <div id="contactFaq2" class="accordion-collapse collapse" data-bs-parent="#contactFaqAccordion">
                            <div class="accordion-body">
                                Je kunt je les annuleren via je account op de website of door contact met ons op te nemen. Bij annulering minimaal 24 uur van tevoren krijg je het volledige bedrag terug of kun je een nieuwe datum kiezen.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#contactFaq3">
                                Bieden jullie ook groepslessen aan?
                            </button>
                        </h2>
                        <div id="contactFaq3" class="accordion-collapse collapse" data-bs-parent="#contactFaqAccordion">
                            <div class="accordion-body">
                                Ja, we bieden groepslessen aan voor bedrijfsuitjes, vriendengroepen en familie. Neem contact met ons op voor een offerte op maat.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#contactFaq4">
                                Welke betalingsmogelijkheden zijn er?
                            </button>
                        </h2>
                        <div id="contactFaq4" class="accordion-collapse collapse" data-bs-parent="#contactFaqAccordion">
                            <div class="accordion-body">
                                Je kunt betalen via bankoverschrijving na je reservering. Je ontvangt automatisch een email met de betalingsgegevens. Contante betaling ter plaatse is ook mogelijk.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#contactFaq5">
                                Is kitesurfen gevaarlijk?
                            </button>
                        </h2>
                        <div id="contactFaq5" class="accordion-collapse collapse" data-bs-parent="#contactFaqAccordion">
                            <div class="accordion-body">
                                Bij juiste begeleiding en veiligheidsinstructies is kitesurfen een relatief veilige sport. Al onze instructeurs zijn gecertificeerd en veiligheid staat altijd voorop. We geven alleen les bij geschikte weersomstandigheden.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Social Media Section -->
    <section class="mb-5">
        <div class="text-center">
            <h3 class="mb-4">Volg Ons Online</h3>
            <div class="d-flex justify-content-center gap-3">
                <a href="#" class="btn btn-outline-primary btn-lg">
                    <i class="fab fa-facebook fa-2x"></i>
                </a>
                <a href="#" class="btn btn-outline-primary btn-lg">
                    <i class="fab fa-instagram fa-2x"></i>
                </a>
                <a href="#" class="btn btn-outline-primary btn-lg">
                    <i class="fab fa-youtube fa-2x"></i>
                </a>
                <a href="#" class="btn btn-outline-primary btn-lg">
                    <i class="fab fa-linkedin fa-2x"></i>
                </a>
            </div>
            <p class="text-muted mt-3">
                Blijf op de hoogte van de laatste kitesurfnieuws, tips en aanbiedingen!
            </p>
        </div>
    </section>

    <!-- Emergency Contact -->
    <section>
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="alert alert-warning">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                        <div>
                            <h5 class="alert-heading">Noodcontact</h5>
                            <p class="mb-0">
                                Voor urgente zaken tijdens lessen kun je altijd direct contact opnemen met je instructeur. 
                                Het telefoonnummer ontvang je bij de bevestiging van je reservering.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Basic validation
    const requiredFields = this.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('is-invalid');
            isValid = false;
        } else {
            field.classList.remove('is-invalid');
        }
    });
    
    if (isValid) {
        // Simulate form submission
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Bezig met versturen...';
        submitBtn.disabled = true;
        
        setTimeout(() => {
            alert('Bedankt voor je bericht! We nemen zo spoedig mogelijk contact met je op.');
            this.reset();
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 2000);
    }
});

// Real-time validation
document.querySelectorAll('#contactForm [required]').forEach(field => {
    field.addEventListener('blur', function() {
        if (this.value.trim()) {
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
        }
    });
});
</script>

<?php require_once APPROOT . '/views/includes/footer.php'; ?>