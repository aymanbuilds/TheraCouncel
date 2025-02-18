<?php
require 'dashboard/config.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $blog_id = intval($_GET['id']);

    $stmt = $pdo->prepare("SELECT * FROM blogs WHERE id = ?");
    $stmt->execute([$blog_id]);
    $blog = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$blog) {
        die("Blog not found!");
    }
} else {
    die("Invalid blog ID!");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="robots" content="index, follow">

        <meta name="description" content="Discover insights on <?= htmlspecialchars(substr($blog['title'], 0, 50)); ?>. Read our latest article on TheraCouncel to explore expert therapy advice and healing strategies.">
        <meta name="keywords" content="TheraCouncel, mental health blog, therapy advice, emotional well-being, self-care, counseling, healing strategies, mental wellness, <?= htmlspecialchars(str_replace(' ', ', ', strtolower($blog['title']))); ?>">

        <meta property="og:title" content="TheraCouncel - Mental Health Support and Therapy in Fort Lauderdale">
        <meta property="og:description"
            content="TheraCouncel offers mental health therapy in Fort Lauderdale, providing a safe and compassionate space for healing through experienced LCSWs and PMHNPs.">
        <meta property="og:image" content="URL_to_image_for_social_sharing">

        <meta property="og:url" content="https://www.theracouncel.com">
        <meta property="og:type" content="website">

        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="TheraCouncel - Your Path to Healing">
        <meta name="twitter:description"
            content="TheraCouncel provides experienced mental health support and therapy in Fort Lauderdale, helping you heal and find emotional well-being.">
        <meta name="twitter:image" content="URL_to_image_for_social_sharing">

        <title>TheraCouncel | Read: <?= htmlspecialchars($blog['title']); ?></title>

        <link rel="preload" href="assets/images/home-page.webp" as="image">
        <link rel="preload"
            href="https://fonts.googleapis.com/css2?family=Fauna+One&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
            as="style">
        <link
            href="https://fonts.googleapis.com/css2?family=Fauna+One&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
            rel="stylesheet" media="all">

        <link rel="stylesheet" href="assets/styles/style.css">


        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Therapist",
            "name": "TheraCouncel",
            "description": "TheraCouncel provides mental health therapy and support in Fort Lauderdale, offering services through experienced LCSWs and PMHNPs. We provide a compassionate, safe space for healing and emotional well-being.",
            "url": "https://www.theracouncel.com",
            "telephone": "+1-954-716-6514",
            "address": {
                "@type": "PostalAddress",
                "streetAddress": "4546 N Federal Hwy",
                "addressLocality": "Fort Lauderdale",
                "addressRegion": "FL",
                "postalCode": "33308",
                "addressCountry": "US"
            },
            "sameAs": [
                "https://web.facebook.com/TheraCounsel",
                "https://www.linkedin.com/in/sherri-issa-lcsw-7a757941/"
            ],
            "contactPoint": {
                "@type": "ContactPoint",
                "telephone": "+1-555-555-5555", 
                "contactType": "customer service",
                "areaServed": "US",
                "availableLanguage": "English"
            },
            "logo": "https://www.theracounsel.com/wp-content/uploads/2020/05/TC-LOGO.png"
        }
        </script>
    </head>

</head>

<body>
    <header class="site-header">
        <div class="header-container">
            <a href="index.html" class="logo">
                <img width="120" height="120" src="assets/images/logo.webp" alt="TheraCouncel Logo" loading="lazy" />
            </a>
            <nav class="site-navigation">
                <ul class="nav-list">
                    <li class="nav-item" id="hide-mobile-menu">
                        <button aria-label="Close menu">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M6 18L18 6M6 6L18 18" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </li>
                    <li class="nav-item"><a href="index.html" class="nav-link">Home</a></li>
                    <li class="nav-item"><a href="vision-and-mission-statement.html" class="nav-link">About</a></li>
                    <li class="nav-item"><a href="theracounselers-and-licensed-clinical-social-workers.html"
                            class="nav-link">Mental Health Specialists</a></li>
                    <li class="nav-item has-submenu">
                        <a href="#" class="nav-link">Benefits</a>
                        <ul class="submenu">
                            <li><a href="make-the-call.html" class="submenu-link">A Place of Healing</a></li>
                            <li><a href="prevention.html" class="submenu-link">Prevention</a></li>
                            <li><a href="request-benefits.html" class="submenu-link">Request Benefits</a></li>
                            <li><a href="guidance-support.html" class="submenu-link">Guidance &amp; Support</a></li>
                        </ul>
                    </li>
                    <li class="nav-item has-submenu">
                        <a href="#" class="nav-link">More</a>
                        <ul class="submenu">
                            <li><a href="additional-resources.html" class="submenu-link">Additional Resources</a></li>
                            <li><a href="career-opportunities.html" class="submenu-link">Career Opportunities</a></li>
                            <li><a href="faq.html" class="submenu-link">FAQ</a></li>
                            <li><a href="privacy-policy.html" class="submenu-link">Privacy Policy</a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a href="blogs.php" class="nav-link">Blogs</a></li>
                    <li class="nav-item"><a href="contact.html" class="nav-link">Contact</a></li>
                </ul>
            </nav>
            <button id="show-mobile-menu" aria-label="Open menu">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </button>
        </div>
    </header>

    <main>
        <section class="home-section lazy dark-text cover-container"
            data-bg="<?= !empty($blog['image']) ? 'url(\'dashboard/' . $blog['image'] . '\')' : 'url(\'dashboard/assets/images/placeholder-image.jpg\')'; ?>">
            <div class="cover-layer"></div>
            <div class="home-content">
                <h1><?= htmlspecialchars($blog['title']); ?></h1>
            </div>
        </section>

        <section class="article-section">
        <p class="publish-date">Published on <?= date('F d, Y', strtotime($blog['created_at'])); ?> by TheraCouncel</p>
        <div class="content"><?php echo strip_tags($blog['content'], '<p><a><b><strong><i><em><ul><ol><li><br><h1><h2><h3><h4><h5><h6>'); ?></div>
        </section>

        <section class="visit-us-facebook">
            <div class="container">
                <a href="https://web.facebook.com/TheraCounsel" class="logo-link">
                    <img width="143" height="143" src="assets/images/logo.webp" alt="TheraCouncel Logo"
                        loading="lazy" />
                </a>
                <a href="https://web.facebook.com/TheraCounsel" class="facebook-link">
                    <i class="fa-brands fa-facebook-f"></i> Visit us on Facebook
                </a>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <div class="container">
            <p>&copy; <span id="current-year"></span> TheraCounsel, Inc. All rights reserved.</p>
            <a class="admin-space" href="dashboard/login.php">Admin Space</a>
        </div>
    </footer>

    <script src="assets/scripts/main.js"></script>
</body>

</html>