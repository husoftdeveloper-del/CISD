<?php
$adminPage = 'settings';
$adminPageTitle = 'Site Settings';
require_once 'includes/init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $keys = [
        'institute_name', 'institute_tagline', 'institute_phone', 'institute_whatsapp',
        'institute_email', 'institute_address', 'institute_maps',
        'social_tiktok', 'social_youtube', 'social_facebook', 'social_instagram',
        'hero_eyebrow', 'hero_title', 'hero_subtitle',
        'features_eyebrow', 'features_title', 'features_subtitle',
        'courses_eyebrow', 'courses_title', 'courses_page_subtitle',
        'testimonials_eyebrow', 'testimonials_title',
        'cta_title', 'cta_subtitle',
        'about_header_eyebrow', 'about_header_title', 'about_header_subtitle',
        'about_story_title', 'about_story_p1', 'about_story_p2', 'about_story_image',
        'about_values_eyebrow', 'about_values_title',
        'about_team_eyebrow', 'about_team_title',
        'gallery_eyebrow', 'gallery_title', 'gallery_subtitle',
        'contact_eyebrow', 'contact_title', 'contact_subtitle',
        'admissions_eyebrow', 'admissions_title', 'admissions_subtitle',
        'admissions_process', 'admissions_fees',
    ];

    $settings = [];
    foreach ($keys as $key) {
        if (isset($_POST[$key])) {
            $settings[$key] = trim($_POST[$key]);
        }
    }

    cisd_save_settings($pdo, $settings);
    $SITE_SETTINGS = cisd_load_site_settings($pdo);
    cisd_apply_institute_from_settings();
    $adminMessage = 'Settings saved successfully.';
    $adminMessageType = 'success';
}

require 'includes/header.php';
?>

<div class="header">
    <h1>Site Settings</h1>
    <div class="header-actions">
        <a href="../index.php" target="_blank" class="btn btn-primary">View Website</a>
    </div>
</div>

<?php if ($adminMessage): ?>
    <div class="alert alert-<?= e($adminMessageType) ?>"><?= e($adminMessage) ?></div>
<?php endif; ?>

<form method="POST" class="form-container">
    <div class="settings-section">
        <h3>Institute Information</h3>
        <div class="settings-grid">
            <div class="form-group"><label>Institute Name</label><input name="institute_name" value="<?= e(site_setting('institute_name')) ?>" required></div>
            <div class="form-group"><label>Tagline</label><input name="institute_tagline" value="<?= e(site_setting('institute_tagline')) ?>"></div>
            <div class="form-group"><label>Phone</label><input name="institute_phone" value="<?= e(site_setting('institute_phone')) ?>"></div>
            <div class="form-group"><label>WhatsApp (digits only)</label><input name="institute_whatsapp" value="<?= e(site_setting('institute_whatsapp')) ?>"></div>
            <div class="form-group"><label>Email</label><input type="email" name="institute_email" value="<?= e(site_setting('institute_email')) ?>"></div>
            <div class="form-group full"><label>Address</label><input name="institute_address" value="<?= e(site_setting('institute_address')) ?>"></div>
            <div class="form-group full"><label>Google Maps Embed URL</label><input name="institute_maps" value="<?= e(site_setting('institute_maps')) ?>"></div>
        </div>
    </div>

    <div class="settings-section">
        <h3>Social Media Links</h3>
        <div class="settings-grid">
            <div class="form-group"><label>TikTok URL</label><input name="social_tiktok" value="<?= e(site_setting('social_tiktok')) ?>"></div>
            <div class="form-group"><label>YouTube URL</label><input name="social_youtube" value="<?= e(site_setting('social_youtube')) ?>"></div>
            <div class="form-group"><label>Facebook URL</label><input name="social_facebook" value="<?= e(site_setting('social_facebook')) ?>"></div>
            <div class="form-group"><label>Instagram URL</label><input name="social_instagram" value="<?= e(site_setting('social_instagram')) ?>"></div>
        </div>
    </div>

    <div class="settings-section">
        <h3>Homepage Hero</h3>
        <div class="settings-grid">
            <div class="form-group"><label>Eyebrow</label><input name="hero_eyebrow" value="<?= e(site_setting('hero_eyebrow')) ?>"></div>
            <div class="form-group full"><label>Title (HTML allowed for &lt;span&gt;)</label><input name="hero_title" value="<?= e(site_setting('hero_title')) ?>"></div>
            <div class="form-group full"><label>Subtitle</label><textarea name="hero_subtitle"><?= e(site_setting('hero_subtitle')) ?></textarea></div>
        </div>
    </div>

    <div class="settings-section">
        <h3>Homepage Sections</h3>
        <div class="settings-grid">
            <div class="form-group"><label>Features Eyebrow</label><input name="features_eyebrow" value="<?= e(site_setting('features_eyebrow')) ?>"></div>
            <div class="form-group"><label>Features Title</label><input name="features_title" value="<?= e(site_setting('features_title')) ?>"></div>
            <div class="form-group full"><label>Features Subtitle</label><textarea name="features_subtitle"><?= e(site_setting('features_subtitle')) ?></textarea></div>
            <div class="form-group"><label>Courses Eyebrow</label><input name="courses_eyebrow" value="<?= e(site_setting('courses_eyebrow')) ?>"></div>
            <div class="form-group"><label>Courses Title</label><input name="courses_title" value="<?= e(site_setting('courses_title')) ?>"></div>
            <div class="form-group"><label>Testimonials Eyebrow</label><input name="testimonials_eyebrow" value="<?= e(site_setting('testimonials_eyebrow')) ?>"></div>
            <div class="form-group"><label>Testimonials Title</label><input name="testimonials_title" value="<?= e(site_setting('testimonials_title')) ?>"></div>
            <div class="form-group"><label>CTA Title</label><input name="cta_title" value="<?= e(site_setting('cta_title')) ?>"></div>
            <div class="form-group full"><label>CTA Subtitle</label><input name="cta_subtitle" value="<?= e(site_setting('cta_subtitle')) ?>"></div>
        </div>
    </div>

    <div class="settings-section">
        <h3>About Page</h3>
        <div class="settings-grid">
            <div class="form-group"><label>Header Eyebrow</label><input name="about_header_eyebrow" value="<?= e(site_setting('about_header_eyebrow')) ?>"></div>
            <div class="form-group full"><label>Header Title</label><input name="about_header_title" value="<?= e(site_setting('about_header_title')) ?>"></div>
            <div class="form-group full"><label>Header Subtitle</label><textarea name="about_header_subtitle"><?= e(site_setting('about_header_subtitle')) ?></textarea></div>
            <div class="form-group full"><label>Story Title</label><input name="about_story_title" value="<?= e(site_setting('about_story_title')) ?>"></div>
            <div class="form-group full"><label>Story Paragraph 1</label><textarea name="about_story_p1"><?= e(site_setting('about_story_p1')) ?></textarea></div>
            <div class="form-group full"><label>Story Paragraph 2</label><textarea name="about_story_p2"><?= e(site_setting('about_story_p2')) ?></textarea></div>
            <div class="form-group full"><label>Story Image Path</label><input name="about_story_image" value="<?= e(site_setting('about_story_image')) ?>"></div>
            <div class="form-group"><label>Values Eyebrow</label><input name="about_values_eyebrow" value="<?= e(site_setting('about_values_eyebrow')) ?>"></div>
            <div class="form-group"><label>Values Title</label><input name="about_values_title" value="<?= e(site_setting('about_values_title')) ?>"></div>
            <div class="form-group"><label>Team Eyebrow</label><input name="about_team_eyebrow" value="<?= e(site_setting('about_team_eyebrow')) ?>"></div>
            <div class="form-group"><label>Team Title</label><input name="about_team_title" value="<?= e(site_setting('about_team_title')) ?>"></div>
        </div>
    </div>

    <div class="settings-section">
        <h3>Other Pages</h3>
        <div class="settings-grid">
            <div class="form-group"><label>Gallery Eyebrow</label><input name="gallery_eyebrow" value="<?= e(site_setting('gallery_eyebrow')) ?>"></div>
            <div class="form-group"><label>Gallery Title</label><input name="gallery_title" value="<?= e(site_setting('gallery_title')) ?>"></div>
            <div class="form-group full"><label>Gallery Subtitle</label><textarea name="gallery_subtitle"><?= e(site_setting('gallery_subtitle')) ?></textarea></div>
            <div class="form-group"><label>Contact Eyebrow</label><input name="contact_eyebrow" value="<?= e(site_setting('contact_eyebrow')) ?>"></div>
            <div class="form-group"><label>Contact Title</label><input name="contact_title" value="<?= e(site_setting('contact_title')) ?>"></div>
            <div class="form-group full"><label>Contact Subtitle</label><textarea name="contact_subtitle"><?= e(site_setting('contact_subtitle')) ?></textarea></div>
            <div class="form-group"><label>Admissions Eyebrow</label><input name="admissions_eyebrow" value="<?= e(site_setting('admissions_eyebrow')) ?>"></div>
            <div class="form-group"><label>Admissions Title</label><input name="admissions_title" value="<?= e(site_setting('admissions_title')) ?>"></div>
            <div class="form-group full"><label>Admissions Subtitle</label><textarea name="admissions_subtitle"><?= e(site_setting('admissions_subtitle')) ?></textarea></div>
            <div class="form-group full"><label>Admission Process</label><textarea name="admissions_process" rows="4"><?= e(site_setting('admissions_process')) ?></textarea></div>
            <div class="form-group full"><label>Fee Structure Info</label><textarea name="admissions_fees"><?= e(site_setting('admissions_fees')) ?></textarea></div>
            <div class="form-group full"><label>Courses Page Subtitle</label><input name="courses_page_subtitle" value="<?= e(site_setting('courses_page_subtitle')) ?>"></div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Save All Settings</button>
</form>

<?php require 'includes/footer.php'; ?>
