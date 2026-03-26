
<!-- =====================================================
     SECTION CONTACT
     ===================================================== -->
<section class="contact-section" id="contact">
    <div class="container">
        <div class="contact-grid">

            <!-- Formulaire -->
            <div class="contact-form-wrap">
                <span class="section-tag">Nous contacter</span>
                <h2 class="section-title">Parlez-nous de votre <span class="text-gold">projet</span></h2>
                <p class="section-desc">Notre équipe vous répond sous 2h par email ou WhatsApp.</p>

                <div id="form-alert" class="form-alert" role="alert"></div>

                <form id="immo-contact-form" class="contact-form" novalidate>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="f-name">Nom complet *</label>
                            <input type="text" id="f-name" name="name" placeholder="Votre nom" required>
                        </div>
                        <div class="form-group">
                            <label for="f-phone">Téléphone *</label>
                            <input type="tel" id="f-phone" name="phone" placeholder="+261 34 00 000 00" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="f-email">Email *</label>
                        <input type="email" id="f-email" name="email" placeholder="votre@email.com" required>
                    </div>
                    <div class="form-group">
                        <label for="f-sujet">Sujet</label>
                        <select id="f-sujet" name="sujet">
                            <option value="">— Choisissez un sujet —</option>
                            <option value="achat">Achat d'un bien</option>
                            <option value="location">Location d'un bien</option>
                            <option value="vente">Vendre mon bien</option>
                            <option value="estimation">Estimation gratuite</option>
                            <option value="autre">Autre demande</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="f-message">Message *</label>
                        <textarea id="f-message" name="message" rows="4" placeholder="Décrivez votre projet immobilier..." required></textarea>
                    </div>
                    <div class="form-actions">
                        <button type="submit" id="form-submit" class="btn btn-gold">
                            <i class="fas fa-paper-plane"></i>
                            <span>Envoyer le message</span>
                        </button>
                        <a href="https://wa.me/261348349886?text=Bonjour%20Immo%20Tana%2C%20je%20souhaite%20des%20informations%20sur%20vos%20biens."
                           target="_blank" rel="noopener" class="btn btn-whatsapp">
                            <i class="fab fa-whatsapp"></i>
                            <span>WhatsApp direct</span>
                        </a>
                    </div>
                </form>
            </div>

            <!-- Infos contact -->
            <div class="contact-info-wrap">
                <div class="contact-info-card">
                    <div class="contact-info-item">
                        <div class="info-icon"><i class="fas fa-map-marker-alt"></i></div>
                        <div>
                            <strong>Adresse</strong>
                            <p>Alasora, Antananarivo<br>Madagascar</p>
                        </div>
                    </div>
                    <div class="contact-info-item">
                        <div class="info-icon"><i class="fas fa-phone-alt"></i></div>
                        <div>
                            <strong>Téléphone</strong>
                            <p><a href="tel:+261348349886">034 83 498 86</a></p>
                        </div>
                    </div>
                    <div class="contact-info-item">
                        <div class="info-icon"><i class="fab fa-whatsapp"></i></div>
                        <div>
                            <strong>WhatsApp</strong>
                            <p><a href="https://wa.me/261348349886" target="_blank" rel="noopener">034 83 498 86</a></p>
                        </div>
                    </div>
                    <div class="contact-info-item">
                        <div class="info-icon"><i class="fas fa-envelope"></i></div>
                        <div>
                            <strong>Email</strong>
                            <p><a href="mailto:baymi312@gmail.com">baymi312@gmail.com</a></p>
                        </div>
                    </div>
                    <div class="contact-info-item">
                        <div class="info-icon"><i class="fas fa-clock"></i></div>
                        <div>
                            <strong>Horaires</strong>
                            <p>Lun–Sam : 8h00 – 18h00<br>Dimanche : Sur RDV</p>
                        </div>
                    </div>
                </div>

                <!-- Google Maps -->
                <div class="contact-map">
                    <iframe
                        src="https://maps.google.com/maps?q=Alasora,+Antananarivo,+Madagascar&output=embed&z=14"
                        width="100%" height="220"
                        style="border:0;border-radius:12px;display:block;"
                        allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        title="Localisation Immo Tana">
                    </iframe>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- =====================================================
     FOOTER
     ===================================================== -->
<footer class="site-footer">
    <div class="footer-top">
        <div class="container footer-grid">

            <div class="footer-col">
                <div class="footer-logo">
                    <svg width="32" height="32" viewBox="0 0 38 38" fill="none">
                        <rect width="38" height="38" rx="8" fill="#c9a84c"/>
                        <path d="M8 28V16l11-8 11 8v12H23v-7h-6v7H8z" fill="white"/>
                        <rect x="16" y="13" width="6" height="5" rx="1" fill="#c9a84c"/>
                    </svg>
                    <span><strong>Immo</strong>Tana</span>
                </div>
                <p class="footer-desc">Votre partenaire immobilier de confiance à Antananarivo. Achat, vente et location de biens immobiliers à Madagascar.</p>
                <div class="footer-social">
                    <a href="https://www.facebook.com/bayane.miguel.singcol" target="_blank" rel="noopener" aria-label="Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="https://wa.me/261348349886" target="_blank" rel="noopener" aria-label="WhatsApp">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                </div>
            </div>

            <div class="footer-col">
                <h4 class="footer-title">Navigation</h4>
                <ul class="footer-links">
                    <li><a href="#accueil"><i class="fas fa-chevron-right"></i> Accueil</a></li>
                    <li><a href="#biens"><i class="fas fa-chevron-right"></i> Nos biens</a></li>
                    <li><a href="#services"><i class="fas fa-chevron-right"></i> Services</a></li>
                    <li><a href="#apropos"><i class="fas fa-chevron-right"></i> À propos</a></li>
                    <li><a href="#contact"><i class="fas fa-chevron-right"></i> Contact</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4 class="footer-title">Types de biens</h4>
                <ul class="footer-links">
                    <li><a href="#biens"><i class="fas fa-chevron-right"></i> Appartements</a></li>
                    <li><a href="#biens"><i class="fas fa-chevron-right"></i> Maisons</a></li>
                    <li><a href="#biens"><i class="fas fa-chevron-right"></i> Villas</a></li>
                    <li><a href="#biens"><i class="fas fa-chevron-right"></i> Terrains</a></li>
                    <li><a href="#biens"><i class="fas fa-chevron-right"></i> Bureaux</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4 class="footer-title">Contact</h4>
                <ul class="footer-contact-list">
                    <li><i class="fas fa-map-marker-alt"></i><span>Alasora, Antananarivo, Madagascar</span></li>
                    <li><i class="fas fa-phone-alt"></i><a href="tel:+261348349886">034 83 498 86</a></li>
                    <li><i class="fab fa-whatsapp"></i><a href="https://wa.me/261348349886" target="_blank" rel="noopener">WhatsApp</a></li>
                    <li><i class="fas fa-envelope"></i><a href="mailto:baymi312@gmail.com">baymi312@gmail.com</a></li>
                </ul>
            </div>

        </div>
    </div>

    <div class="footer-bottom">
        <div class="container footer-bottom-inner">
            <p>&copy; <?php echo date('Y'); ?> <strong>Immo Tana</strong>. Tous droits réservés.</p>
            <p>Développé par <a href="https://miguel-next-portfolio.vercel.app" target="_blank" rel="noopener">Bayane</a></p>
        </div>
    </div>
</footer>

<!-- Bouton WhatsApp flottant -->
<a href="https://wa.me/261348349886?text=Bonjour%20Immo%20Tana%2C%20je%20souhaite%20des%20informations%20sur%20vos%20biens."
   class="whatsapp-float" target="_blank" rel="noopener" aria-label="Contacter sur WhatsApp">
    <i class="fab fa-whatsapp"></i>
    <span class="whatsapp-tooltip">Chattez avec nous !</span>
</a>

<!-- Bouton retour en haut -->
<button class="back-to-top" id="back-to-top" aria-label="Retour en haut">
    <i class="fas fa-arrow-up"></i>
</button>

<?php wp_footer(); ?>
</body>
</html>
