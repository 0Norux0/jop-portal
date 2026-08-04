<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Support\PortalData;
use Illuminate\Database\Seeder;

class DemoPortalContentSeeder extends Seeder
{
    public function run(): void
    {
        $portal = PortalData::load();

        $portal['countries'] = [
            'Kuwait', 'UAE', 'Saudi Arabia', 'Qatar', 'Bahrain', 'Oman', 'United Kingdom',
            'Canada', 'Australia', 'United States', 'European countries', 'Germany', 'Pakistan',
            'Philippines', 'India', 'Nepal', 'Bangladesh', 'Sri Lanka', 'Kenya',
            'Africa and other regions',
        ];

        $portal['candidate_types'] = [
            'Job seekers from any country', 'Recruitment agencies', 'Freelancers and remote workers',
            'Fresh graduates', 'Skilled workers', 'Professional candidates', 'Blue-collar workers',
            'Caregivers', 'IT professionals', 'Office/admin workers', 'Healthcare support workers',
            'Hospitality workers', 'Teachers/trainers', 'Drivers/technicians', 'Accountants',
            'Designers', 'Programmers', 'AI/data/cybersecurity professionals',
            'Optional ICSA/NAS verified graduate category',
        ];

        $portal['employer_types'] = [
            'Employers from any country', 'Small businesses', 'Large companies', 'Training institutes',
            'Hospitals/clinics', 'Care homes', 'Hotels/restaurants', 'Recruitment agencies',
            'Remote-first companies', 'International employers', 'Startups',
            'Government-approved manpower agencies', 'Education providers',
        ];

        $portal['seo_pages'] = config('portal.seo_pages', []);
        $portal['blog_categories'] = config('portal.blog_categories', []);
        $portal['blog_topics'] = config('portal.blog_topics', []);

        $portal['jobs'] = [
            [
                'slug' => 'senior-caregiver-manchester-sponsored',
                'title' => 'Senior Caregiver',
                'company' => 'Northbridge Care Group',
                'city' => 'Manchester',
                'country' => 'United Kingdom',
                'mode' => 'On-site',
                'salary' => 'GBP 2,100 - 2,650 monthly',
                'type' => 'Full-time',
                'category' => 'Caregiving',
                'badges' => ['Demo offer', 'Visa sponsorship pathway', 'Accommodation guidance', 'Verified employer'],
                'urgent' => true,
                'deadline' => '2026-09-30',
                'vacancies' => 18,
                'description' => 'Support elderly residents with personal care, mobility, medication reminders, companionship, and safe daily routines in a regulated care environment.',
                'responsibilities' => ['Assist residents with personal care and mobility', 'Maintain accurate care notes', 'Support meals and companionship', 'Follow safeguarding and health policies'],
                'skills' => ['Patient care', 'English communication', 'Safeguarding awareness', 'Teamwork'],
                'requirements' => ['Caregiving training or relevant experience', 'Basic English', 'Willing to relocate', 'Clean employment record'],
                'benefits' => ['Visa sponsorship pathway', 'Accommodation guidance', 'Paid training', 'Overtime available'],
            ],
            [
                'slug' => 'remote-laravel-developer-canada',
                'title' => 'Remote Laravel Developer',
                'company' => 'MapleCloud Labs',
                'city' => 'Toronto',
                'country' => 'Canada',
                'mode' => 'Remote',
                'salary' => 'USD 2,800 - 4,200 monthly',
                'type' => 'Contract',
                'category' => 'Information Technology',
                'badges' => ['Demo offer', 'Remote', 'Portfolio required', 'Verified employer'],
                'urgent' => false,
                'deadline' => '2026-09-15',
                'vacancies' => 4,
                'description' => 'Build and maintain Laravel applications for international SaaS clients with clean APIs, queues, tests, and secure admin workflows.',
                'responsibilities' => ['Develop Laravel features', 'Write automated tests', 'Review pull requests', 'Integrate third-party APIs'],
                'skills' => ['Laravel', 'MariaDB', 'REST APIs', 'Git', 'Tailwind CSS'],
                'requirements' => ['2+ years Laravel experience', 'Public portfolio or GitHub', 'Remote collaboration skills'],
                'benefits' => ['Remote-first team', 'Flexible schedule', 'Long-term contract option'],
            ],
            [
                'slug' => 'front-office-executive-dubai',
                'title' => 'Front Office Executive',
                'company' => 'Pearl Vista Hotels',
                'city' => 'Dubai',
                'country' => 'UAE',
                'mode' => 'On-site',
                'salary' => 'AED 3,500 - 4,500 monthly',
                'type' => 'Full-time',
                'category' => 'Hospitality',
                'badges' => ['Demo offer', 'Urgent hiring', 'Transportation provided', 'Verified employer'],
                'urgent' => true,
                'deadline' => '2026-08-25',
                'vacancies' => 6,
                'description' => 'Welcome guests, manage check-in and check-out, coordinate bookings, and provide polished hospitality service.',
                'responsibilities' => ['Handle reservations', 'Manage guest requests', 'Coordinate housekeeping', 'Prepare front-desk reports'],
                'skills' => ['Customer service', 'English', 'MS Office', 'Hospitality etiquette'],
                'requirements' => ['Hospitality diploma preferred', '1+ year hotel experience', 'Professional appearance'],
                'benefits' => ['Transportation', 'Duty meals', 'Medical insurance'],
            ],
            [
                'slug' => 'junior-cybersecurity-analyst-kuwait',
                'title' => 'Junior Cybersecurity Analyst',
                'company' => 'GulfSecure Systems',
                'city' => 'Kuwait City',
                'country' => 'Kuwait',
                'mode' => 'Hybrid',
                'salary' => 'KWD 450 - 650 monthly',
                'type' => 'Fresh graduate',
                'category' => 'Cybersecurity',
                'badges' => ['Demo offer', 'Fresh graduates welcome', 'Training provided', 'Verified employer'],
                'urgent' => false,
                'deadline' => '2026-10-05',
                'vacancies' => 3,
                'description' => 'Monitor alerts, support incident response, prepare reports, and learn security operations in a supervised team.',
                'responsibilities' => ['Review SIEM alerts', 'Document incidents', 'Support vulnerability checks', 'Escalate suspicious activity'],
                'skills' => ['Networking basics', 'Linux basics', 'Security fundamentals', 'Report writing'],
                'requirements' => ['Cybersecurity course or certification', 'Analytical mindset', 'Willingness to work shifts'],
                'benefits' => ['Mentorship', 'Certification support', 'Hybrid work'],
            ],
            [
                'slug' => 'accountant-kuwait-trading-company',
                'title' => 'Junior Accountant',
                'company' => 'Al Noor Trading Co.',
                'city' => 'Kuwait City',
                'country' => 'Kuwait',
                'mode' => 'On-site',
                'salary' => 'KWD 350 - 500 monthly',
                'type' => 'Full-time',
                'category' => 'Accounting',
                'badges' => ['Demo offer', 'Verified employer', 'Salary disclosed'],
                'urgent' => false,
                'deadline' => '2026-09-20',
                'vacancies' => 2,
                'description' => 'Assist with daily accounting entries, reconciliations, invoices, supplier statements, and monthly finance reports.',
                'responsibilities' => ['Record invoices', 'Reconcile accounts', 'Prepare reports', 'Support month-end closing'],
                'skills' => ['Excel', 'Bookkeeping', 'Attention to detail', 'Basic VAT knowledge'],
                'requirements' => ['Accounting diploma or degree', '0-2 years experience', 'English communication'],
                'benefits' => ['Medical insurance', 'Annual leave', 'Training support'],
            ],
            [
                'slug' => 'remote-graphic-designer-uk-agency',
                'title' => 'Remote Graphic Designer',
                'company' => 'BrightLane Creative',
                'city' => 'London',
                'country' => 'United Kingdom',
                'mode' => 'Remote',
                'salary' => 'USD 1,500 - 2,300 monthly',
                'type' => 'Freelance',
                'category' => 'Design & Creative',
                'badges' => ['Demo offer', 'Remote', 'Portfolio required'],
                'urgent' => false,
                'deadline' => '2026-09-10',
                'vacancies' => 5,
                'description' => 'Create brand assets, social media creatives, presentation decks, and campaign visuals for international clients.',
                'responsibilities' => ['Create digital assets', 'Prepare brand layouts', 'Revise client designs', 'Export final files'],
                'skills' => ['Adobe Photoshop', 'Illustrator', 'Canva', 'Brand design'],
                'requirements' => ['Strong portfolio', 'Reliable internet', 'Client communication skills'],
                'benefits' => ['Remote work', 'Flexible hours', 'Repeat project pipeline'],
            ],
        ];

        $portal['candidates'] = [
            ['name' => 'Ayesha Khan', 'headline' => 'Certified Caregiver open to UK roles', 'country' => 'Pakistan', 'badges' => ['Verified Profile', 'Video Profile', 'Verified Certificate'], 'skills' => ['Elder care', 'First aid', 'English']],
            ['name' => 'Daniel Reyes', 'headline' => 'Remote Laravel developer with SaaS portfolio', 'country' => 'Philippines', 'badges' => ['Portfolio Available', 'Open to Remote Work'], 'skills' => ['Laravel', 'Vue', 'APIs']],
            ['name' => 'Mariam Al-Sabah', 'headline' => 'Junior cybersecurity analyst', 'country' => 'Kuwait', 'badges' => ['Verified ICSA Graduate', 'Verified Skill'], 'skills' => ['SOC', 'Linux', 'Networking']],
            ['name' => 'Priya Menon', 'headline' => 'Accountant ready for Gulf finance roles', 'country' => 'India', 'badges' => ['Verified Email', 'Portfolio Available'], 'skills' => ['Bookkeeping', 'Excel', 'Reports']],
        ];

        PortalData::save($portal);
    }
}
