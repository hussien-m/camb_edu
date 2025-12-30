<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class CookiePolicySeeder extends Seeder
{
    public function run(): void
    {
        Page::updateOrCreate(
            ['slug' => 'cookie-policy'],
            [
                'title' => 'Cookie Policy',
                'content' => $this->getCookiePolicyContent(),
                'is_published' => true,
                'meta_description' => 'Learn about how Cambridge International College uses cookies to enhance your browsing experience.',
                'meta_keywords' => 'cookies, privacy, policy, data protection, GDPR',
            ]
        );
    }

    private function getCookiePolicyContent(): string
    {
        return <<<'HTML'
<div class="container my-5">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <h1 class="mb-4">🍪 Cookie Policy</h1>
            <p class="lead">Last updated: December 30, 2025</p>
            
            <hr class="my-4">

            <h2><i class="fas fa-info-circle text-primary"></i> What Are Cookies?</h2>
            <p>
                Cookies are small text files that are placed on your device (computer, smartphone, or tablet) when you visit our website. 
                They help us provide you with a better experience by remembering your preferences and understanding how you use our site.
            </p>

            <div class="alert alert-info">
                <i class="fas fa-lightbulb"></i>
                <strong>Think of cookies like sticky notes:</strong> They help websites remember useful information about you, 
                like your language preference or login status, so you don't have to enter it every time.
            </div>

            <h2 class="mt-5"><i class="fas fa-list text-primary"></i> Types of Cookies We Use</h2>

            <div class="card mb-3">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-shield-alt"></i> Essential Cookies (Required)</h5>
                </div>
                <div class="card-body">
                    <p><strong>Purpose:</strong> These cookies are necessary for the website to function properly and cannot be switched off.</p>
                    <p><strong>What they do:</strong></p>
                    <ul>
                        <li>Enable you to log in and access your account</li>
                        <li>Remember items in your shopping cart</li>
                        <li>Protect against security threats and fraud</li>
                        <li>Ensure forms work correctly (CSRF protection)</li>
                    </ul>
                    <p><strong>Examples:</strong></p>
                    <ul>
                        <li><code>laravel_session</code> - Session management</li>
                        <li><code>XSRF-TOKEN</code> - Security protection</li>
                    </ul>
                    <p class="mb-0"><span class="badge bg-success">Duration:</span> Session or 2 hours</p>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user-check"></i> Functional Cookies (Optional)</h5>
                </div>
                <div class="card-body">
                    <p><strong>Purpose:</strong> These cookies enable enhanced functionality and personalization.</p>
                    <p><strong>What they do:</strong></p>
                    <ul>
                        <li>Remember your login for next time ("Remember Me" feature)</li>
                        <li>Remember your language and region preferences</li>
                        <li>Provide enhanced, personalized features</li>
                    </ul>
                    <p><strong>Examples:</strong></p>
                    <ul>
                        <li><code>remember_web_*</code> - Remember login</li>
                    </ul>
                    <p class="mb-0"><span class="badge bg-primary">Duration:</span> Up to 5 years (if you choose "Remember Me")</p>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-line"></i> Analytics Cookies (Optional)</h5>
                </div>
                <div class="card-body">
                    <p><strong>Purpose:</strong> These cookies help us understand how visitors interact with our website.</p>
                    <p><strong>What they do:</strong></p>
                    <ul>
                        <li>Count the number of visitors</li>
                        <li>See how visitors navigate the site</li>
                        <li>Identify which pages are most popular</li>
                        <li>Help us improve the website's performance</li>
                    </ul>
                    <p><strong>Examples:</strong></p>
                    <ul>
                        <li>Google Analytics cookies (if enabled)</li>
                    </ul>
                    <p class="mb-0"><span class="badge bg-info">Duration:</span> Varies (typically up to 2 years)</p>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-bullhorn"></i> Marketing Cookies (Optional)</h5>
                </div>
                <div class="card-body">
                    <p><strong>Purpose:</strong> These cookies are used to track visitors across websites to display relevant advertisements.</p>
                    <p><strong>What they do:</strong></p>
                    <ul>
                        <li>Show you relevant ads on other websites</li>
                        <li>Limit the number of times you see an ad</li>
                        <li>Measure the effectiveness of advertising campaigns</li>
                    </ul>
                    <p class="mb-0 text-muted"><em>Note: We currently do not use marketing cookies on our website.</em></p>
                </div>
            </div>

            <h2 class="mt-5"><i class="fas fa-cog text-primary"></i> How to Control Cookies</h2>
            
            <h4>On Our Website:</h4>
            <p>
                You can change your cookie preferences at any time by clicking the "Cookie Settings" button at the bottom of our website.
            </p>

            <h4>In Your Browser:</h4>
            <p>Most web browsers allow you to control cookies through their settings. Here's how:</p>

            <div class="row mt-4">
                <div class="col-md-6 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5><i class="fab fa-chrome text-warning"></i> Google Chrome</h5>
                            <ol class="small">
                                <li>Click the three dots (⋮) in the top right</li>
                                <li>Settings → Privacy and security</li>
                                <li>Cookies and other site data</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5><i class="fab fa-firefox text-danger"></i> Mozilla Firefox</h5>
                            <ol class="small">
                                <li>Click the three lines (≡) in the top right</li>
                                <li>Settings → Privacy & Security</li>
                                <li>Cookies and Site Data</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5><i class="fab fa-safari text-primary"></i> Safari</h5>
                            <ol class="small">
                                <li>Safari → Preferences</li>
                                <li>Privacy tab</li>
                                <li>Manage Website Data</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5><i class="fab fa-edge text-info"></i> Microsoft Edge</h5>
                            <ol class="small">
                                <li>Click the three dots (•••) in the top right</li>
                                <li>Settings → Cookies and site permissions</li>
                                <li>Cookies and site data</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="alert alert-warning mt-4">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>Please note:</strong> Disabling certain cookies may affect the functionality of our website 
                and prevent you from accessing some features.
            </div>

            <h2 class="mt-5"><i class="fas fa-users text-primary"></i> Third-Party Cookies</h2>
            <p>
                Some cookies on our website are set by third-party services that appear on our pages. We don't control these cookies. 
                Please check the third-party websites for more information about these cookies.
            </p>

            <h2 class="mt-5"><i class="fas fa-sync-alt text-primary"></i> Updates to This Policy</h2>
            <p>
                We may update this Cookie Policy from time to time. Any changes will be posted on this page with an updated revision date. 
                We encourage you to review this policy periodically.
            </p>

            <h2 class="mt-5"><i class="fas fa-envelope text-primary"></i> Contact Us</h2>
            <p>
                If you have any questions about our use of cookies, please contact us:
            </p>
            <div class="card">
                <div class="card-body">
                    <p class="mb-2"><i class="fas fa-envelope text-primary"></i> <strong>Email:</strong> info@cambridgecollage.com</p>
                    <p class="mb-2"><i class="fas fa-phone text-success"></i> <strong>Phone:</strong> +13062167976</p>
                    <p class="mb-0"><i class="fas fa-map-marker-alt text-danger"></i> <strong>Address:</strong> Canada, Saskatchewan</p>
                </div>
            </div>

            <div class="text-center mt-5 mb-4">
                <a href="/" class="btn btn-primary btn-lg">
                    <i class="fas fa-home me-2"></i>Back to Homepage
                </a>
                <button onclick="customizeCookies()" class="btn btn-outline-primary btn-lg ms-2">
                    <i class="fas fa-cog me-2"></i>Cookie Settings
                </button>
            </div>
        </div>
    </div>
</div>
HTML;
    }
}

