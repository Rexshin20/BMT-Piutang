<?php

namespace App\Support;

class DemoData
{
    /**
     * One hundred deterministic demo tasks.
     */
    public static function tasks(): array
    {
        $titles = [
            'Implement multi-tenant support',
            'Fix navigation dropdown on mobile',
            'Add CSV export to the reports page',
            'Refactor authentication middleware',
            'Improve dashboard loading performance',
            'Update onboarding documentation',
            'Add dark mode support to charts',
            'Resolve duplicate invoice entries',
            'Create reusable table component',
            'Migrate legacy settings endpoint',
            'Add pagination to the activity feed',
            'Fix timezone offset in reports',
            'Introduce role-based access control',
            'Polish empty states across pages',
            'Replace broken avatar placeholders',
            'Optimize images on the marketing site',
            'Add bulk actions to the users table',
            'Handle expired sessions gracefully',
            'Write integration tests for billing',
            'Tidy up Tailwind configuration',
            'Add keyboard shortcuts to search',
            'Correct spacing in the sidebar',
            'Upgrade chart library to latest',
            'Surface validation errors inline',
            'Cache dashboard aggregates',
        ];

        $statuses = ['todo', 'in progress', 'done', 'canceled', 'backlog'];
        $labels = ['bug', 'feature', 'documentation'];
        $priorities = ['low', 'medium', 'high', 'critical'];
        $assignees = ['Alex John', 'Taylor Grande', 'John Doe', 'Megan Flux', 'David Brown', 'Julia Carter', 'Brad Wilson', 'Katie Lee', 'Matt Green', 'Sophie Alex'];

        $tasks = [];

        for ($i = 0; $i < 100; $i++) {
            $tasks[] = [
                'id' => 'TASK-' . (1000 + $i),
                'title' => $titles[$i % count($titles)] . ' (' . ($i + 1) . ')',
                'status' => $statuses[($i * 3 + 1) % count($statuses)],
                'label' => $labels[$i % count($labels)],
                'priority' => $priorities[($i * 2 + 1) % count($priorities)],
                'assignee' => $assignees[$i % count($assignees)],
                'created_at' => now()->subDays(100 - $i)->format('d M Y'),
                'due_date' => now()->addDays(10 + ($i % 40))->format('d M Y'),
                'description' => 'Deliver the work described in the issue, including tests and documentation updates.',
            ];
        }

        return $tasks;
    }

    /**
     * Demo users for the user management table.
     */
    public static function users(): array
    {
        $firstNames = ['Olivia', 'Jackson', 'Isabella', 'William', 'Sofia', 'Liam', 'Emma', 'Noah', 'Ava', 'Lucas', 'Mia', 'Ethan', 'Amelia', 'Mason', 'Harper'];
        $lastNames = ['Martin', 'Lee', 'Nguyen', 'Kim', 'Davis', 'Smith', 'Johnson', 'Brown', 'Wilson', 'Clark', 'Lewis', 'Walker', 'Hall', 'Young', 'King'];
        $statuses = ['active', 'inactive', 'invited', 'suspended'];
        $roles = ['superadmin', 'admin', 'manager', 'cashier'];

        $users = [];

        for ($i = 0; $i < 60; $i++) {
            $first = $firstNames[$i % count($firstNames)];
            $last = $lastNames[($i * 4 + 2) % count($lastNames)];
            $username = strtolower($first . '.' . $last) . ($i + 1);

            $users[] = [
                'id' => 'USR-' . (1000 + $i),
                'first_name' => $first,
                'last_name' => $last,
                'username' => $username,
                'email' => $username . '@example.com',
                'phone_number' => '+62 812-' . str_pad((string) (1000 + $i), 4, '0', STR_PAD_LEFT) . '-' . str_pad((string) (2000 + $i * 3), 4, '0', STR_PAD_LEFT),
                'status' => $statuses[($i * 2 + 1) % count($statuses)],
                'role' => $roles[($i * 3 + 2) % count($roles)],
                'created_at' => now()->subDays(200 - $i * 2)->format('d M Y'),
            ];
        }

        return $users;
    }

    /**
     * Customer receivables (piutang) shown on the receivables page.
     */
    public static function receivables(): array
    {
        $names = [
            'Budi Santoso', 'Siti Aminah', 'Andi Wijaya', 'Dewi Lestari', 'Rizky Pratama', 'Nur Aisyah',
            'Agus Setiawan', 'Rina Marlina', 'Fajar Nugroho', 'Putri Amelia', 'Hendra Gunawan', 'Maya Sari',
        ];
        $items = [
            'Beras 5 kg', 'Minyak Goreng 2 L', 'Gula Pasir 1 kg', 'Telur Ayam 1 kg', 'Tepung Terigu 1 kg',
            'Kopi Bubuk 250 g', 'Susu UHT 1 L', 'Sabun Mandi', 'Shampo 340 ml', 'Pasta Gigi',
            'Mie Instan (dus)', 'Air Mineral (dus)',
        ];
        $prices = [65000, 34000, 18000, 32000, 14000, 28000, 22000, 8500, 39000, 12500, 112000, 58000];

        $receivables = [];

        for ($i = 0; $i < 36; $i++) {
            $price = $prices[($i * 7) % count($prices)];
            $quantity = 1 + (($i * 3) % 5);

            $receivables[] = [
                'nama' => $names[$i % count($names)],
                'tanggal' => now()->subDays(($i * 2) + 1)->format('Y-m-d'),
                'barang' => $items[($i * 5) % count($items)],
                'harga' => $price,
                'total' => $price * $quantity,
            ];
        }

        return $receivables;
    }

    /**
     * Borrower master data: name, address, and phone number.
     */
    public static function borrowers(): array
    {
        $addresses = [
            'Jl. Merdeka No. 12, Bandung',
            'Jl. Sudirman No. 45, Jakarta',
            'Jl. Diponegoro No. 8, Semarang',
            'Jl. Gajah Mada No. 21, Surabaya',
            'Jl. Malioboro No. 56, Yogyakarta',
            'Jl. Ahmad Yani No. 10, Medan',
            'Jl. Pemuda No. 33, Semarang',
            'Jl. Braga No. 7, Bandung',
            'Jl. Asia Afrika No. 88, Bandung',
            'Jl. Thamrin No. 5, Jakarta',
            'Jl. Kuta Raya No. 19, Denpasar',
            'Jl. Sam Ratulangi No. 27, Makassar',
        ];

        $names = array_values(array_unique(array_column(self::receivables(), 'nama')));

        $borrowers = [];

        foreach ($names as $index => $name) {
            $borrowers[] = [
                'nama' => $name,
                'alamat' => $addresses[$index % count($addresses)],
                'telepon' => '0812-' . str_pad((string) (3456 + $index * 11), 4, '0', STR_PAD_LEFT) . '-' . str_pad((string) (7800 + $index * 13), 4, '0', STR_PAD_LEFT),
            ];
        }

        return $borrowers;
    }

    /**
     * Integrations shown on the apps page.
     */
    public static function apps(): array
    {
        return [
            ['name' => 'Telegram', 'brand' => 'telegram', 'connected' => false, 'desc' => 'Connect with Telegram for real-time communication.'],
            ['name' => 'Notion', 'brand' => 'notion', 'connected' => true, 'desc' => 'Effortlessly sync Notion pages for seamless collaboration.'],
            ['name' => 'Figma', 'brand' => 'figma', 'connected' => true, 'desc' => 'View and collaborate on Figma designs in one place.'],
            ['name' => 'Trello', 'brand' => 'trello', 'connected' => false, 'desc' => 'Sync Trello cards for streamlined project management.'],
            ['name' => 'Slack', 'brand' => 'slack', 'connected' => false, 'desc' => 'Integrate Slack for efficient team communication.'],
            ['name' => 'Zoom', 'brand' => 'zoom', 'connected' => true, 'desc' => 'Host Zoom meetings directly from the dashboard.'],
            ['name' => 'Stripe', 'brand' => 'stripe', 'connected' => false, 'desc' => 'Easily manage Stripe transactions and payments.'],
            ['name' => 'Gmail', 'brand' => 'gmail', 'connected' => true, 'desc' => 'Access and manage Gmail messages effortlessly.'],
            ['name' => 'Medium', 'brand' => 'medium', 'connected' => false, 'desc' => 'Explore and share Medium stories on your dashboard.'],
            ['name' => 'Skype', 'brand' => 'skype', 'connected' => false, 'desc' => 'Connect with Skype contacts seamlessly.'],
            ['name' => 'Docker', 'brand' => 'docker', 'connected' => false, 'desc' => 'Effortlessly manage Docker containers on your dashboard.'],
            ['name' => 'GitHub', 'brand' => 'github', 'connected' => false, 'desc' => 'Streamline code management with GitHub integration.'],
            ['name' => 'GitLab', 'brand' => 'gitlab', 'connected' => false, 'desc' => 'Efficient code project management with GitLab integration.'],
            ['name' => 'Discord', 'brand' => 'discord', 'connected' => false, 'desc' => 'Connect with Discord for seamless team communication.'],
            ['name' => 'WhatsApp', 'brand' => 'whatsapp', 'connected' => false, 'desc' => 'Easily integrate WhatsApp for direct messaging.'],
        ];
    }

    /**
     * Demo conversations for the chat page.
     */
    public static function conversations(): array
    {
        $now = now();

        return [
            [
                'id' => 'conv1',
                'initials' => 'AJ',
                'full_name' => 'Alex John',
                'username' => 'alex_dev',
                'title' => 'Senior Backend Dev',
                'messages' => self::messages($now, [
                    ['Alex', 'Hey Bob, how are you doing?'],
                    ['You', "Hey Alex, I'm doing well! How about you?"],
                    ['Alex', "I'm good, thanks! Did you finish the project?"],
                    ['You', 'Almost done. Just need to review a few things.'],
                    ['Alex', 'Great! Let me know if you need any help.'],
                    ['You', 'Will do! Thanks, Alex.'],
                    ['Alex', 'By the way, have you seen the new feature update?'],
                    ['You', "No, not yet. What's new?"],
                    ['Alex', "They've added a dark mode option! It looks really sleek."],
                    ['You', "Oh, nice! I've been waiting for that. I'll check it out later."],
                    ['Alex', 'Yeah, let me know what you think.'],
                    ['You', 'For sure. Anyway, I should get back to reviewing the project.'],
                    ['Alex', 'Alright, talk to you later!'],
                    ['You', 'See you later, Alex!'],
                ]),
            ],
            [
                'id' => 'conv2',
                'initials' => 'TG',
                'full_name' => 'Taylor Grande',
                'username' => 'taylor.codes',
                'title' => 'Tech Lead',
                'messages' => self::messages($now, [
                    ['Taylor', 'The new docs are live.'],
                    ['You', 'I saw that, the examples are much clearer now.'],
                    ['Taylor', "Yeah, it's really well-explained. You should give it a try."],
                ]),
            ],
            [
                'id' => 'conv3',
                'initials' => 'JD',
                'full_name' => 'John Doe',
                'username' => 'john_stack',
                'title' => 'QA',
                'messages' => self::messages($now, [
                    ['John', 'Regression suite is green.'],
                    ['You', 'Excellent, shipping today then?'],
                    ['John', 'Yep, see ya. 👋🏼'],
                ]),
            ],
            [
                'id' => 'conv4',
                'initials' => 'MF',
                'full_name' => 'Megan Flux',
                'username' => 'megan_frontend',
                'title' => 'Jr Developer',
                'messages' => self::messages($now, [
                    ['Megan', 'Can you review my pull request?'],
                    ['You', 'Sure, looking at it now.'],
                    ['Megan', 'Sure ✌🏼'],
                ]),
            ],
            [
                'id' => 'conv5',
                'initials' => 'DB',
                'full_name' => 'David Brown',
                'username' => 'dev_david',
                'title' => 'Senior UI/UX Designer',
                'messages' => self::messages($now, [
                    ['David', 'Uploaded the latest mockups.'],
                    ['You', 'They look great, thanks!'],
                    ['David', "Great, I'll review them now!"],
                ]),
            ],
            [
                'id' => 'conv6',
                'initials' => 'JC',
                'full_name' => 'Julia Carter',
                'username' => 'julia.design',
                'title' => 'Product Designer',
                'messages' => self::messages($now, [
                    ['Julia', 'How is the redesign coming along?'],
                    ['You', 'Slow but steady. The sidebar is done.'],
                    ['Julia', "Same here! It's coming together nicely."],
                ]),
            ],
            [
                'id' => 'conv7',
                'initials' => 'BW',
                'full_name' => 'Brad Wilson',
                'username' => 'brad_dev',
                'title' => 'CEO',
                'messages' => self::messages($now, [
                    ['Brad', 'Any blockers for the release?'],
                    ['You', 'Nothing major, just waiting on QA.'],
                    ['Brad', 'Got it! Thanks for the update.'],
                ]),
            ],
            [
                'id' => 'conv8',
                'initials' => 'KL',
                'full_name' => 'Katie Lee',
                'username' => 'katie_ui',
                'title' => 'QA',
                'messages' => self::messages($now, [
                    ['Katie', 'Standup in five?'],
                    ['You', 'On my way.'],
                    ['Katie', "I'll join the call in a few minutes."],
                ]),
            ],
            [
                'id' => 'conv9',
                'initials' => 'MG',
                'full_name' => 'Matt Green',
                'username' => 'matt_fullstack',
                'title' => 'Full-stack Dev',
                'messages' => self::messages($now, [
                    ['Matt', 'Can you send the update notes?'],
                    ['You', 'Sure, give me a moment.'],
                    ['Matt', "Sure thing, I'll send over the updates shortly."],
                ]),
            ],
            [
                'id' => 'conv10',
                'initials' => 'SA',
                'full_name' => 'Sophie Alex',
                'username' => 'sophie_dev',
                'title' => 'Jr. Frontend Dev',
                'messages' => self::messages($now, [
                    ['Sophie', 'I pushed a fix for the table layout.'],
                    ['You', 'Nice, I will take a look.'],
                    ["Sophie", "Thanks! I'll review your code and get back to you."],
                ]),
            ],
        ];
    }

    /**
     * Build timestamped messages, newest last.
     */
    private static function messages($now, array $messages): array
    {
        $result = [];
        $count = count($messages);
        $start = $count * 4;

        foreach ($messages as $index => $message) {
            $result[] = [
                'sender' => $message[0],
                'message' => $message[1],
                'timestamp' => $now->copy()->subMinutes($start - $index * 4)->format('Y-m-d H:i:s'),
                'time' => $now->copy()->subMinutes($start - $index * 4)->format('g:i A'),
                'date' => $now->copy()->subMinutes($start - $index * 4)->format('d M, Y'),
            ];
        }

        return $result;
    }
}
