<?php
/**
 * Idempotent schema migrations and default content seeding.
 */
function cisd_run_migrations(PDO $pdo): void
{
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS site_settings (
            setting_key VARCHAR(100) PRIMARY KEY,
            setting_value TEXT,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS features (
            id INT AUTO_INCREMENT PRIMARY KEY,
            section ENUM('home','about_values') NOT NULL DEFAULT 'home',
            icon VARCHAR(20) NOT NULL DEFAULT '★',
            title VARCHAR(120) NOT NULL,
            description TEXT NOT NULL,
            display_order INT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS team_members (
            id INT AUTO_INCREMENT PRIMARY KEY,
            image_path VARCHAR(255) NOT NULL,
            name VARCHAR(120) NOT NULL,
            role VARCHAR(120) NOT NULL,
            specialty VARCHAR(160) NOT NULL,
            location VARCHAR(160),
            contact VARCHAR(60),
            email VARCHAR(160),
            education VARCHAR(160),
            bio TEXT,
            display_order INT DEFAULT 0,
            status ENUM('active','inactive') DEFAULT 'active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB
    ");

    try {
        $pdo->exec("ALTER TABLE contact_messages ADD COLUMN is_read TINYINT(1) NOT NULL DEFAULT 0");
    } catch (PDOException $e) {
        // Column already exists.
    }

    cisd_seed_defaults($pdo);
}

function cisd_seed_setting(PDO $pdo, string $key, string $value): void
{
    $stmt = $pdo->prepare("INSERT IGNORE INTO site_settings (setting_key, setting_value) VALUES (?, ?)");
    $stmt->execute([$key, $value]);
}

function cisd_seed_defaults(PDO $pdo): void
{
    $settings = [
        'institute_name' => 'CISD INSTITUTE',
        'institute_tagline' => 'Professional IT & Digital Skills Training Institute',
        'institute_phone' => '+923149284641',
        'institute_whatsapp' => '923149284641',
        'institute_email' => 'cisdsardheri@gmail.com',
        'institute_address' => 'Main Sardheri Bazar Wardagha Road',
        'institute_maps' => 'https://www.google.com/maps?q=Main+Boulevard+Lahore&output=embed',
        'social_tiktok' => '#',
        'social_youtube' => '#',
        'social_facebook' => '#',
        'social_instagram' => '#',
        'hero_eyebrow' => 'Premium IT Training',
        'hero_title' => 'Learn the skills that <span>shape the future</span>',
        'hero_subtitle' => 'Industry-grade courses in Web Development, Graphic Design, Digital Marketing, App Development and more — taught by expert mentors with real projects.',
        'features_eyebrow' => 'Why Choose Us',
        'features_title' => 'Built for serious learners',
        'features_subtitle' => 'Practical curriculum, modern tools, mentor support and project-based learning that prepares you for jobs and freelance work.',
        'courses_eyebrow' => 'Featured Courses',
        'courses_title' => 'Programs designed for the modern career',
        'testimonials_eyebrow' => 'Student Stories',
        'testimonials_title' => 'What our graduates say',
        'cta_title' => 'Ready to start your journey?',
        'cta_subtitle' => 'Limited seats per batch — secure your admission today.',
        'about_header_eyebrow' => 'About Us',
        'about_header_title' => 'Empowering the next generation of digital talent',
        'about_header_subtitle' => 'Born from a passion to bridge the gap between classroom theory and industry skills.',
        'about_story_title' => 'Built by professionals, for ambitious learners',
        'about_story_p1' => 'CISD INSTITUTE was founded with one mission: to make world-class IT and digital skills education accessible, practical and outcome-driven. Our graduates work as freelancers, full-time employees and founders — across borders.',
        'about_story_p2' => 'We combine modern curriculum, hands-on projects, and 1:1 mentorship to ensure every student walks out with a portfolio, not just a certificate.',
        'about_story_image' => 'images/hero-classroom.jpg',
        'about_values_eyebrow' => 'Our Values',
        'about_values_title' => 'Mission, Vision & Promise',
        'about_team_eyebrow' => 'Meet the Team',
        'about_team_title' => 'Mentors who care about your success',
        'gallery_eyebrow' => 'Campus Life',
        'gallery_title' => 'A look inside our learning space',
        'gallery_subtitle' => 'Modern classrooms, collaborative learning and a vibrant student community.',
        'contact_eyebrow' => 'Contact Us',
        'contact_title' => "We'd love to hear from you",
        'contact_subtitle' => 'Questions about courses, fees or admissions — drop us a message.',
        'admissions_eyebrow' => 'Admissions Open',
        'admissions_title' => 'Apply for Admission',
        'admissions_subtitle' => 'Fill the form below — our advisor will reach out to confirm your seat.',
        'admissions_process' => "1. Submit application form\n2. Counseling call within 24 hrs\n3. Fee deposit & seat confirmation\n4. Welcome onboarding + class start",
        'admissions_fees' => 'Easy installment plans available. Discount for early-bird and group admissions.',
        'courses_page_subtitle' => '10+ practical, project-based programs designed for careers and freelancing.',
    ];

    foreach ($settings as $key => $value) {
        cisd_seed_setting($pdo, $key, $value);
    }

    $featureCount = (int) $pdo->query("SELECT COUNT(*) FROM features")->fetchColumn();
    if ($featureCount === 0) {
        $homeFeatures = [
            ['★', 'Expert Instructors', 'Learn from industry professionals with 5+ years of hands-on experience.'],
            ['⚡', 'Project-Based', 'Build real portfolios with live client-style projects from day one.'],
            ['✓', 'Career Support', 'CV reviews, freelancing mentorship and interview preparation included.'],
            ['⏱', 'Flexible Timings', 'Morning, evening and weekend batches for students and professionals.'],
            ['🏆', 'Verified Certificate', 'Earn an industry-recognized certificate upon successful completion.'],
            ['💬', '1:1 Mentorship', 'Personal guidance through WhatsApp groups and live doubt sessions.'],
        ];
        $stmt = $pdo->prepare("INSERT INTO features (section, icon, title, description, display_order) VALUES ('home', ?, ?, ?, ?)");
        foreach ($homeFeatures as $i => $f) {
            $stmt->execute([$f[0], $f[1], $f[2], $i]);
        }

        $aboutValues = [
            ['M', 'Mission', 'To deliver practical, industry-aligned training that opens real career and freelance opportunities.'],
            ['V', 'Vision', 'To be the most trusted digital skills institute producing employable, confident professionals.'],
            ['P', 'Promise', 'Real projects, real mentors, real outcomes — or your fee refunded within 7 days.'],
            ['C', 'Community', 'Lifetime access to our alumni community for jobs, gigs and collaborations.'],
        ];
        $stmt = $pdo->prepare("INSERT INTO features (section, icon, title, description, display_order) VALUES ('about_values', ?, ?, ?, ?)");
        foreach ($aboutValues as $i => $f) {
            $stmt->execute([$f[0], $f[1], $f[2], $i]);
        }
    }

    $teamCount = (int) $pdo->query("SELECT COUNT(*) FROM team_members")->fetchColumn();
    if ($teamCount === 0) {
        $team = [
            ['images/shahabsir.JPEG', 'Shahab Khan', 'Founder, Director & Lead Technology Mentor', 'Leading the vision, strategy, academic direction, and technology programs of CISD.', 'Sardheri, Charsadda', '+92-370-504-0330', 'shahab@cisd-institute.com', 'M.Sc. Computer Science'],
            ['images/taimor.jpeg', 'Taimor Khan', 'Chief Executive Officer (CEO)', 'Responsible for operations, business growth, partnerships, and organizational management.', 'Sardheri, Charsadda', '', '', ''],
            ['images/qarisaib.JPEG', 'Usman Ali', 'Senior Trainer & Web/App Developer', 'Specializing in Web Development, Mobile App Development, and student technical training.', 'Sardheri, Charsadda', '+92-370-504-0331', 'usman@cisd-institute.com', 'B.Des. Graphic Design'],
        ];
        $stmt = $pdo->prepare("INSERT INTO team_members (image_path, name, role, specialty, location, contact, email, education, display_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($team as $i => $t) {
            $stmt->execute([...$t, $i]);
        }
    }

    $storyCount = (int) $pdo->query("SELECT COUNT(*) FROM success_stories")->fetchColumn();
    if ($storyCount === 0) {
        $stories = [
            ['Hafiz.png', 'Hafiz Ullah', 'Father Name', 'Web Development', 'Hands-on projects helped me land my first freelance client in 3 months.', 'Sardheri Charsadda', '+92 3265611593', 'hu.softdeveloper@gmail.com', 'FSC Computer Science'],
            ['Maaz.jpeg', 'Maaz Khan', 'Father Name', 'Graphic Designing', 'From zero to running my own Instagram design page. Practical and modern.', 'Saedheri Charsadda', '+92 3705171087', 'maazkhanmalik6@gmail.com', 'BS Computer Science'],
            ['Daniyal.jpeg', 'Daniyal Khan', 'Father Name', 'Digital Marketing', 'Best decision. I now manage ads for two local brands and earn in dollars.', 'Sardheri Charsadda', '+92 3478763428', 'danyalkhan034787@gmail.com', 'MBA Marketing'],
        ];
        $stmt = $pdo->prepare("INSERT INTO success_stories (image, name, father_name, course, quote, location, contact, email, education) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($stories as $s) {
            $stmt->execute($s);
        }
    }
}
