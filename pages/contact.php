<?php

require __DIR__ . '/../includes/header.php';
require __DIR__ . '/../functions/functions.php';
blockAccess();

$currentName = $_SESSION['name'] ?? $_SESSION['username'] ?? '';
$currentEmail = $_SESSION['email'] ?? '';
?>

<section id="contact" class="contact-section">
    <div class="container">
        <div class="section-header">
            <h2>Get <span class="text-danger">In Touch</span></h2>
            <p>We are always open to Solve Your Problems.
            </p>
        </div>

        <form id="contactForm" class="contact-form">
            <div class="form-row">
                <div class="form-group">
                    <label for="name">Your Name</label>
                    <input type="text" id="name" name="name" class="form-input" 
                           value="<?= htmlspecialchars($currentName) ?>" readonly required>
                </div>

                <div class="form-group">
                    <label for="email">Your Email</label>
                    <input type="email" id="email" name="email" class="form-input" 
                           value="<?= htmlspecialchars($currentEmail) ?>" readonly required>
                </div>
            </div>

            <div class="form-group">
                <label for="subject">Subject</label>
                <input type="text" id="subject" name="subject" class="form-input" 
                       placeholder="What is this about?" required>
            </div>

            <div class="form-group">
                <label for="message">Your Message</label>
                <textarea id="message" name="message" class="form-input" rows="6" 
                          placeholder="Tell me more about your project..." required></textarea>
            </div>

            <div class="form-submit">
                <button type="submit" id="submitBtn" class="btn btn-primary">
                    Send Message 
                </button>
            </div>
        </form>
    </div>
</section>

<div id="toast-container"></div>




<?php
require __DIR__ . '/../includes/footer.php';
?>