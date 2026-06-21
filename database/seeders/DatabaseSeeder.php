<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Test;
use App\Models\TestSection;
use App\Models\Passage;
use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Default Admin User
        User::updateOrCreate(
            ['email' => 'admin@languagecenter.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // 2. Seed English Placement Test
        $test = Test::create([
            'title' => 'English Placement Test - EVOLVE',
            'description' => 'Official Evolve English language placement test covering Listening, Reading comprehension, and Language Use.',
            'is_active' => true,
            'show_result_to_student' => true,
        ]);

        // ==========================================
        // SECTION 1: LISTENING (20 Items, 15 Minutes)
        // ==========================================
        $listening = TestSection::create([
            'test_id' => $test->id,
            'title' => 'Section I: Listening',
            'type' => 'listening',
            'duration_minutes' => 15,
            'instructions' => 'In Section I: Listening, you will hear nine conversations and complete one or more items about each one. Before you listen to a conversation, read the situation and the following item or items. Then listen to the conversation. Complete the items after the conversation ends. Choose the correct answer for each item, and fill in your choice. You will hear the conversation only once. The first conversation is an example. There are 20 items, and you have 15 minutes to complete this section.',
            'order' => 1,
        ]);

        $listeningQuestions = [
            [
                'num' => 1,
                'sit' => 'Situation 1: Emily and Jason are talking about work.',
                'text' => 'Emily …………..',
                'options' => [
                    'a' => 'works at a café',
                    'b' => 'never goes to the mall',
                    'c' => 'works every weekend',
                    'd' => 'goes to the mall every day',
                ],
                'correct' => 'a'
            ],
            [
                'num' => 2,
                'sit' => 'Situation 2: Jessica is buying clothes.',
                'text' => 'Jessica …………….',
                'options' => [
                    'a' => 'is buying a dress and a skirt',
                    'b' => 'thinks the skirts are expensive',
                    'c' => 'can’t find a red skirt',
                    'd' => 'pays $30 for the skirt',
                ],
                'correct' => 'c'
            ],
            [
                'num' => 3,
                'sit' => 'Situation 3: Rachel and Michael are talking in a mall.',
                'text' => 'Rachel and Michael …………….',
                'options' => [
                    'a' => 'are having lunch together',
                    'b' => 'are buying gifts for their children',
                    'c' => 'are busy tomorrow afternoon',
                    'd' => 'are going to meet again tomorrow',
                ],
                'correct' => 'c'
            ],
            [
                'num' => 4,
                'sit' => 'Situation 4: Andrew is talking to a waitress at a restaurant.',
                'text' => 'Andrew ………',
                'options' => [
                    'a' => 'didn’t enjoy the food',
                    'b' => 'ate just a little pasta',
                    'c' => 'ordered a salad',
                    'd' => 'didn’t like the dressing',
                ],
                'correct' => 'd'
            ],
            [
                'num' => 5,
                'sit' => 'Situation 4: Andrew is talking to a waitress at a restaurant.',
                'text' => 'The waitress …...',
                'options' => [
                    'a' => 'can make the salad dressing',
                    'b' => 'is going to talk to the chef',
                    'c' => 'doesn’t offer a dessert to Andrew',
                    'd' => 'is going to bring Andrew some coffee',
                ],
                'correct' => 'd'
            ],
            [
                'num' => 6,
                'sit' => 'Situation 5: Laura is talking to her father about a health problem.',
                'text' => 'Laura ………',
                'options' => [
                    'a' => 'hit her head in a basketball game',
                    'b' => 'ate some bad food at school yesterday',
                    'c' => 'has a horrible pain in her stomach',
                    'd' => 'has a very bad headache',
                ],
                'correct' => 'd'
            ],
            [
                'num' => 7,
                'sit' => 'Situation 5: Laura is talking to her father about a health problem.',
                'text' => 'Laura’s father………….',
                'options' => [
                    'a' => 'has a stomachache too',
                    'b' => 'offers to take her to the doctor',
                    'c' => 'is going to call a doctor',
                    'd' => 'wants to rest a little',
                ],
                'correct' => 'b'
            ],
            [
                'num' => 8,
                'sit' => 'Situation 6: Jack is talking to his friend Olivia on the phone.',
                'text' => 'When Jack called Olivia, she …………..',
                'options' => [
                    'a' => 'couldn’t hear him because of a bad connection',
                    'b' => 'was in a noisy area, but she moved',
                    'c' => 'was at the bus stop with her friend Katie',
                    'd' => 'was on her way to see a play',
                ],
                'correct' => 'b'
            ],
            [
                'num' => 9,
                'sit' => 'Situation 6: Jack is talking to his friend Olivia on the phone.',
                'text' => 'Jack …………………….',
                'options' => [
                    'a' => 'thought the movie was not very exciting',
                    'b' => 'thought the movie had too much action',
                    'c' => 'thinks Olivia shouldn’t see the movie',
                    'd' => 'is going out with Olivia and Katie on Friday',
                ],
                'correct' => 'a'
            ],
            [
                'num' => 10,
                'sit' => 'Situation 7: Amanda is meeting her friend Patrick at a café.',
                'text' => 'Amanda and Patrick ……………..',
                'options' => [
                    'a' => 'last met in January',
                    'b' => 'went to a concert together',
                    'c' => 'haven’t seen each other since April',
                    'd' => 'have been spending a lot of time together lately',
                ],
                'correct' => 'c'
            ],
            [
                'num' => 11,
                'sit' => 'Situation 7: Amanda is meeting her friend Patrick at a café.',
                'text' => 'Amanda ……….',
                'options' => [
                    'a' => 'has found a new job',
                    'b' => 'is looking for another job',
                    'c' => 'finds her work too challenging',
                    'd' => 'has been having problems at work',
                ],
                'correct' => 'a'
            ],
            [
                'num' => 12,
                'sit' => 'Situation 7: Amanda is meeting her friend Patrick at a café.',
                'text' => 'Patrick …………….',
                'options' => [
                    'a' => 'has been learning Spanish',
                    'b' => 'isn’t enjoying his cooking class very much',
                    'c' => 'has been all over the world lately',
                    'd' => 'wants to cook for Amanda and Jim',
                ],
                'correct' => 'd'
            ],
            [
                'num' => 13,
                'sit' => 'Situation 8: Nicole is talking to her teacher, Mr. Kushner, about her exam grade.',
                'text' => 'Mr. Kushner ……….',
                'options' => [
                    'a' => 'thought that Nicole was disappointed with her grade',
                    'b' => 'doesn’t think Nicole knows about his rules',
                    'c' => 'usually lets students take exams a second time',
                    'd' => 'thinks that Nicole will get a better grade next time',
                ],
                'correct' => 'd'
            ],
            [
                'num' => 14,
                'sit' => 'Situation 8: Nicole is talking to her teacher, Mr. Kushner, about her exam grade.',
                'text' => 'Nicole thinks that she got a low grade because ……………',
                'options' => [
                    'a' => 'she only had time to answer the reading questions',
                    'b' => 'she didn’t get a grade on the reading section',
                    'c' => 'she forgot to answer the reading questions',
                    'd' => 'she did badly on the reading section',
                ],
                'correct' => 'b'
            ],
            [
                'num' => 15,
                'sit' => 'Situation 8: Nicole is talking to her teacher, Mr. Kushner, about her exam grade.',
                'text' => 'In the end, Mr. Kushner ……………..',
                'options' => [
                    'a' => 'wasn’t able to help Nicole',
                    'b' => 'asked Nicole not to miss an exam again',
                    'c' => 'apologized to Nicole for the problem',
                    'd' => 'realized that Nicole’s exam was missing',
                ],
                'correct' => 'c'
            ],
            [
                'num' => 16,
                'sit' => 'Situation 9: Lisa is talking to Eric about her job interview.',
                'text' => 'After Lisa’s interview, she felt……..',
                'options' => [
                    'a' => 'more optimistic than she did before',
                    'b' => 'she was well prepared for it',
                    'c' => 'uncertain about it',
                    'd' => 'her answers sounded very confident',
                ],
                'correct' => 'c'
            ],
            [
                'num' => 17,
                'sit' => 'Situation 9: Lisa is talking to Eric about her job interview.',
                'text' => 'During the interview, Lisa ……………',
                'options' => [
                    'a' => 'recognized that she’s an impatient person',
                    'b' => 'said she tended to be too positive about things',
                    'c' => 'admitted she didn’t enjoy working on big projects',
                    'd' => 'boasted that she always met her deadlines',
                ],
                'correct' => 'a'
            ],
            [
                'num' => 18,
                'sit' => 'Situation 9: Lisa is talking to Eric about her job interview.',
                'text' => 'According to Eric, can make a person seem intelligent…….',
                'options' => [
                    'a' => 'taking less time to answer a question',
                    'b' => 'staying calm throughout an interview',
                    'c' => 'speaking naturally and showing no anxiety',
                    'd' => 'pausing before saying something',
                ],
                'correct' => 'd'
            ],
            [
                'num' => 19,
                'sit' => 'Situation 9: Lisa is talking to Eric about her job interview.',
                'text' => 'Lisa……………………',
                'options' => [
                    'a' => 'thinks she could find a much better job',
                    'b' => 'usually believes in miracles',
                    'c' => 'expects to be offered the position',
                    'd' => 'feels frustrated about the situation',
                ],
                'correct' => 'c'
            ],
            [
                'num' => 20,
                'sit' => 'Situation 9: Lisa is talking to Eric about her job interview.',
                'text' => 'Eric ……………….',
                'options' => [
                    'a' => 'agrees with Lisa’s views on her performance at the interview',
                    'b' => 'thinks people naturally have a good opinion about Lisa',
                    'c' => 'is concerned that Lisa might quit her job',
                    'd' => 'advises her not to be so proud of herself',
                ],
                'correct' => 'b'
            ],
        ];

        foreach ($listeningQuestions as $index => $q) {
            $question = Question::create([
                'test_section_id' => $listening->id,
                'question_number' => $q['num'],
                'situation' => $q['sit'],
                'question_text' => $q['text'],
                'audio_path' => null, // Left null until admin uploads audio
                'order' => $index + 1,
            ]);

            foreach ($q['options'] as $label => $textOpt) {
                QuestionOption::create([
                    'question_id' => $question->id,
                    'label' => $label,
                    'option_text' => $textOpt,
                    'is_correct' => ($label === $q['correct']),
                ]);
            }
        }

        // ==========================================
        // SECTION 2: READING (15 Items, 20 Minutes)
        // ==========================================
        $reading = TestSection::create([
            'test_id' => $test->id,
            'title' => 'Section II: Reading',
            'type' => 'reading',
            'duration_minutes' => 20,
            'instructions' => 'In this section of the test, you will read some short passages and complete the information about them. For each item, choose the correct answer (a, b, c, or d) and mark it. There are 20 items, and you will have 20 minutes to complete this section.',
            'order' => 2,
        ]);

        // Seed Reading Passages & Associated Questions
        $passages = [
            [
                'title' => 'Passage 1: An email, Subject: Greetings from Florida!',
                'content' => "Hi, Sara.\n\nI’m visiting my sister in Florida. It’s very warm and nice here. Every morning, I go to the beach and swim. Sometimes my sister comes home early, and we play tennis in the afternoon. And we always go for a long walk after that. I’m having a great time!\n\nLove,\nHeather",
                'questions' => [
                    [
                        'num' => 21,
                        'text' => 'Heather every day…….',
                        'options' => [
                            'a' => 'swims',
                            'b' => 'plays tennis',
                            'c' => 'comes home early',
                            'd' => 'walks with her sister',
                        ],
                        'correct' => 'a'
                    ]
                ]
            ],
            [
                'title' => 'Passage 2: Helen is getting married and I’m tired.',
                'content' => "This has been a crazy week! One of my friends is getting married on Saturday, and I’m helping her with the reception. It’s not going to be a big party, but I still have to do a lot of things. For example, I chose the songs last week, but the band is playing them for me tonight. I bought the flowers today, but I have to pick them up on Friday. I’m tired. Can someone help me, please?!",
                'questions' => [
                    [
                        'num' => 22,
                        'text' => 'The writer ……………',
                        'options' => [
                            'a' => 'is singing tonight',
                            'b' => 'is buying flowers on Friday',
                            'c' => 'listened to a band a week ago',
                            'd' => 'is going to a party this weekend',
                        ],
                        'correct' => 'd'
                    ]
                ]
            ],
            [
                'title' => 'Passage 3: The Whitney Museum of American Art',
                'content' => "The Whitney is one of the most famous art museums in New York City. It first opened in 1931 in Greenwich Village, and then it moved to two different places in 1954 and 1966. Since 2015, this museum of American art has been in a new building downtown. The new space is larger and more modern, and it has beautiful views of the Hudson River from its windows and café. Visit it next time you are in the city.",
                'questions' => [
                    [
                        'num' => 23,
                        'text' => 'The Whitney Museum …………….',
                        'options' => [
                            'a' => 'shows art from many countries',
                            'b' => 'moved to a smaller place in 2015',
                            'c' => 'has a place to eat and great views',
                            'd' => 'was in the same building since 1931',
                        ],
                        'correct' => 'c'
                    ]
                ]
            ],
            [
                'title' => 'Passage 4: Is sitting unhealthy?',
                'content' => "If you have been sitting in an office for a long period of time, stand up and move for your health. Research has shown that too much sitting might cause higher blood pressure, add body fat, and increase the danger of death from heart disease. Studies have also suggested that moving more has a positive effect on a person’s health. So, what can an office worker do? Experts say that you should take a break from sitting every 30 minutes, stand more while working, and even walk when meeting with coworkers. Moving might save your life.",
                'questions' => [
                    [
                        'num' => 24,
                        'text' => 'The article suggests that …………………',
                        'options' => [
                            'a' => 'there is very little research about the effects of sitting',
                            'b' => 'sitting for a long time might be dangerous for your health',
                            'c' => 'office workers live longer than other types of workers',
                            'd' => 'people do not usually like to walk and exercise',
                        ],
                        'correct' => 'b'
                    ],
                    [
                        'num' => 25,
                        'text' => 'According to the article, office workers should .',
                        'options' => [
                            'a' => 'stop working every half hour',
                            'b' => 'not work in an office if possible',
                            'c' => 'only stand or walk when you work',
                            'd' => 'move more to avoid serious heart problems',
                        ],
                        'correct' => 'd'
                    ]
                ]
            ],
            [
                'title' => 'Passage 5: A changing neighborhood – for better or for worse?',
                'content' => "Recently, an international online retailer opened an enormous, brand-new office in our neighborhood. Until then, there hadn’t been any major companies or huge buildings like this in the area – just small family-owned businesses. So, obviously, there has been a lot of discussion about it lately. Some people say the company is creating jobs and will attract other new businesses, but others complain that most of the new jobs will be low-paying. These people also believe that rising costs will push out independent businesses and make the neighborhood too expensive for its current residents. I can’t make up my mind whether the company will be a benefit for the neighborhood or not. It’s a complicated issue, and I’m not sure there is a right or wrong answer. What do you think?",
                'questions' => [
                    [
                        'num' => 26,
                        'text' => 'The author of the blog post believes that .',
                        'options' => [
                            'a' => 'there may be both positive and negative consequences',
                            'b' => 'the changes will be helpful for most workers from the region',
                            'c' => 'there will soon be many more big companies in the neighborhood',
                            'd' => 'the changes will be mostly harmful for people who live in the area',
                        ],
                        'correct' => 'a'
                    ],
                    [
                        'num' => 27,
                        'text' => 'Some people think the company will help the area because it will .',
                        'options' => [
                            'a' => 'create a greater number of jobs with excellent salaries',
                            'b' => 'make the area more interesting to other companies',
                            'c' => 'lower the cost of living in the neighborhood',
                            'd' => 'replace smaller stores with larger ones',
                        ],
                        'correct' => 'b'
                    ]
                ]
            ],
            [
                'title' => 'Passage 6: An inspiring story',
                'content' => "When Alex McGovern was in high school, he used to earn money working weekends at a local bakery. After working there for several months, helping bake fresh bread and cakes, Alex noticed a familiar pattern: a huge amount of food was thrown away at the end of each day. It was food that the bakery could no longer sell, but it was still good enough to eat. So Alex began to wonder what he could do with all of this extra food. With the bakery owner’s permission, he reached out to a local organization that worked with families who need help with food and housing. The charity was extremely pleased and arranged to pick up the extra food each day. Now bread was no longer wasted, but generously shared with people in need. Alex’s idea was such a success that he began approaching other local restaurants about joining the program. Before long, there were over a dozen businesses taking part, and Alex created a website to grow the program in other cities. Today Alex’s “simple” idea is helping feed people all over the country!",
                'questions' => [
                    [
                        'num' => 28,
                        'text' => 'Alex’s original goal at the bakery was to ………….',
                        'options' => [
                            'a' => 'eat free bread and cake',
                            'b' => 'learn to be a baker',
                            'c' => 'make money',
                            'd' => 'help people',
                        ],
                        'correct' => 'c'
                    ],
                    [
                        'num' => 29,
                        'text' => 'Alex got his idea ………………..',
                        'options' => [
                            'a' => 'when he saw how much food was wasted',
                            'b' => 'while he was baking some fresh bread',
                            'c' => 'from the owner of the bakery',
                            'd' => 'from a local organization',
                        ],
                        'correct' => 'a'
                    ],
                    [
                        'num' => 30,
                        'text' => 'The bakery owner …………….',
                        'options' => [
                            'a' => 'thought that Alex’s plans wouldn’t work',
                            'b' => 'allowed Alex to give away the extra bread',
                            'c' => 'helped Alex create a website for the organization',
                            'd' => 'didn’t care about the families assisted by the charity',
                        ],
                        'correct' => 'b'
                    ],
                    [
                        'num' => 31,
                        'text' => 'The extra food was …………………',
                        'options' => [
                            'a' => 'sold by Alex',
                            'b' => 'bought by the charity',
                            'c' => 'delivered by the bakery',
                            'd' => 'picked up by the organization',
                        ],
                        'correct' => 'd'
                    ]
                ]
            ],
            [
                'title' => 'Passage 7: Some thoughts on your online profile',
                'content' => "In many ways, the internet has made it easier than ever to find out about new job opportunities. Yet, as companies increasingly examine candidates’ social media profiles for information to use in the selection process, people need to be aware of the risks and rewards of posting online. The views they express—and the ways they choose to express them—can be a crucial factor in whether or not they receive an offer of employment. Many young adults, who have grown up with social media and are comfortable sharing their lives online, don’t realize how employers are using social media in hiring decisions. These companies don’t just consider information about a person’s online behavior; they may even gather information about friends and family. Some fear that employers may judge candidates based on factors such as their medical history, age, or even beliefs. While there is currently debate about what information companies are allowed to legally collect or use for hiring decisions, everyone agrees that people need to be careful about what they post online. Your behavior on social media could cost you your current position or job opportunities in the future. So, should job applicants erase their social media accounts completely? According to Professor John Sacks of the Better Hiring Institute, “It would be better to make sure you have a strong professional profile that emphasizes your qualifications. Not having any social media might send the message that you have something to hide.” In other words, take the time to create an attractive profile on a career site and carefully consider everything you post online.",
                'questions' => [
                    [
                        'num' => 32,
                        'text' => 'This article is aimed primarily at .',
                        'options' => [
                            'a' => 'employers',
                            'b' => 'college students',
                            'c' => 'potential job candidates',
                            'd' => 'social media organizations',
                        ],
                        'correct' => 'c'
                    ],
                    [
                        'num' => 33,
                        'text' => 'According to the author, some people may not realize ……………',
                        'options' => [
                            'a' => 'the effect of their online behavior on friends and family',
                            'b' => 'how their online profiles can affect hiring decisions',
                            'c' => 'what information companies cannot legally collect',
                            'd' => 'if their online profile looks professional enough',
                        ],
                        'correct' => 'b'
                    ],
                    [
                        'num' => 34,
                        'text' => 'One way of increasing your chances of getting a good job is …………',
                        'options' => [
                            'a' => 'not keeping a profile online',
                            'b' => 'expressing your opinions in a honest way',
                            'c' => 'having a profile that clearly shows your skills',
                            'd' => 'being secretive about what you share online',
                        ],
                        'correct' => 'c'
                    ],
                    [
                        'num' => 35,
                        'text' => 'The author online personal information to make hiring decisions…………',
                        'options' => [
                            'a' => 'is against companies using',
                            'b' => 'is in favor of the practice of using',
                            'c' => 'believes it does not matter if employers use',
                            'd' => 'does not say whether it is good or bad to use',
                        ],
                        'correct' => 'd'
                    ]
                ]
            ],
        ];

        $qCount = 1;
        foreach ($passages as $pIndex => $p) {
            $passage = Passage::create([
                'test_section_id' => $reading->id,
                'title' => $p['title'],
                'content' => $p['content'],
                'order' => $pIndex + 1,
            ]);

            foreach ($p['questions'] as $q) {
                $question = Question::create([
                    'test_section_id' => $reading->id,
                    'passage_id' => $passage->id,
                    'question_number' => $q['num'],
                    'question_text' => $q['text'],
                    'audio_path' => null,
                    'order' => $qCount++,
                ]);

                foreach ($q['options'] as $label => $textOpt) {
                    QuestionOption::create([
                        'question_id' => $question->id,
                        'label' => $label,
                        'option_text' => $textOpt,
                        'is_correct' => ($label === $q['correct']),
                    ]);
                }
            }
        }

        // ==========================================
        // SECTION 3: LANGUAGE USE (30 Items, 15 Minutes)
        // ==========================================
        $langUse = TestSection::create([
            'test_id' => $test->id,
            'title' => 'Section III: Language Use',
            'type' => 'language_use',
            'duration_minutes' => 15,
            'instructions' => 'This section of the test is about the use of English. For each item, choose the correct answer (a, b, c, or d) and mark it on your answer sheet. There are 30 items, and you will have 15 minutes to complete this section.',
            'order' => 3,
        ]);

        $langQuestions = [
            [
                'num' => 41,
                'text' => 'My daughter sometimes to school with her friends.',
                'options' => ['a' => 'walk', 'b' => 'walks', 'c' => 'walking', 'd' => 'not walk'],
                'correct' => 'b'
            ],
            [
                'num' => 42,
                'text' => 'eat dinner on Sundays?',
                'options' => ['a' => 'Where your family', 'b' => 'How is your family', 'c' => 'When your family do', 'd' => 'What time does your family'],
                'correct' => 'd'
            ],
            [
                'num' => 43,
                'text' => 'a lot of people in the park today.',
                'options' => ['a' => 'There', 'b' => 'There’s', 'c' => 'There are', 'd' => 'There is no'],
                'correct' => 'c'
            ],
            [
                'num' => 44,
                'text' => ', but I’m not very good.',
                'options' => ['a' => 'I can play the guitar', 'b' => 'I don’t play the guitar', 'c' => 'I play the guitar very well', 'd' => 'I can’t play the guitar well'],
                'correct' => 'a'
            ],
            [
                'num' => 45,
                'text' => 'We had a nice vacation. The weather beautiful.',
                'options' => ['a' => 'did', 'b' => 'was', 'c' => 'does', 'd' => 'were'],
                'correct' => 'b'
            ],
            [
                'num' => 46,
                'text' => 'Tom home right now. He’s still at the office.',
                'options' => ['a' => 'isn’t driving', 'b' => 'doesn’t drive', 'c' => 'didn’t drive', 'd' => 'drives'],
                'correct' => 'a'
            ],
            [
                'num' => 47,
                'text' => 'Is it true? a grandparent yesterday?',
                'options' => ['a' => 'Are you becoming', 'b' => 'Does she become', 'c' => 'Did he become', 'd' => 'They became'],
                'correct' => 'c'
            ],
            [
                'num' => 48,
                'text' => 'I go to the gym evenings. I only don’t go on the weekend.',
                'options' => ['a' => 'some', 'b' => 'most', 'c' => 'all of the', 'd' => 'many of the'],
                'correct' => 'b'
            ],
            [
                'num' => 49,
                'text' => 'Susan’s cousin is player on our soccer team.',
                'options' => ['a' => 'bad', 'b' => 'best', 'c' => 'worse', 'd' => 'the worst'],
                'correct' => 'd'
            ],
            [
                'num' => 50,
                'text' => 'Our neighbor the screen of his phone twice last year.',
                'options' => ['a' => 'breaks', 'b' => 'is breaking', 'c' => 'has broken', 'd' => 'broke'],
                'correct' => 'd'
            ],
            [
                'num' => 51,
                'text' => 'A: I can’t forget to make a reservation at the restaurant before noon. B: Don’t worry. you.',
                'options' => ['a' => 'I’m reminding', 'b' => 'I’ve reminded', 'c' => 'I’ll remind', 'd' => 'I remind'],
                'correct' => 'c'
            ],
            [
                'num' => 52,
                'text' => 'We for a hotel when the storm began.',
                'options' => ['a' => 'search', 'b' => 'will search', 'c' => 'have searched', 'd' => 'were searching'],
                'correct' => 'd'
            ],
            [
                'num' => 53,
                'text' => 'If you concentrate on your work, you usually waste a lot of time.',
                'options' => ['a' => 'don’t', 'b' => 'won’t', 'c' => 'didn’t', 'd' => 'couldn’t'],
                'correct' => 'a'
            ],
            [
                'num' => 54,
                'text' => 'I’m exhausted. to fix this machine since I got here this morning.',
                'options' => ['a' => 'I try', 'b' => 'I’ll try', 'c' => 'I tried', 'd' => 'I’ve been trying'],
                'correct' => 'd'
            ],
            [
                'num' => 55,
                'text' => 'Several bridges during the earthquake last year.',
                'options' => ['a' => 'badly damaged', 'b' => 'were badly damaged', 'c' => 'have badly damaged', 'd' => 'were badly damaging'],
                'correct' => 'b'
            ],
            [
                'num' => 56,
                'text' => 'The agency that our ideas for the poster seem a little old-fashioned.',
                'options' => ['a' => 'believes', 'b' => 'is believing', 'c' => 'was believed', 'd' => 'has been believing'],
                'correct' => 'a'
            ],
            [
                'num' => 57,
                'text' => 'Superhero movies are a kind of entertainment really attracted to.',
                'options' => ['a' => 'which', 'b' => 'I’m not', 'c' => 'who they', 'd' => 'that aren’t'],
                'correct' => 'b'
            ],
            [
                'num' => 58,
                'text' => 'More support to groups dealing with environmental issues.',
                'options' => ['a' => 'is providing', 'b' => 'might provide', 'c' => 'must be provided', 'd' => 'should be providing'],
                'correct' => 'c'
            ],
            [
                'num' => 59,
                'text' => 'Employees show their ID cards, or they couldn’t have access to the research facilities.',
                'options' => ['a' => 'were required to', 'b' => 'were allowed to', 'c' => 'didn’t have to', 'd' => 'could'],
                'correct' => 'a'
            ],
            [
                'num' => 60,
                'text' => 'Our math teacher made a hundred math problems in one hour.',
                'options' => ['a' => 'us to solve', 'b' => 'be solved', 'c' => 'us solve', 'd' => 'solve'],
                'correct' => 'c'
            ],
            [
                'num' => 61,
                'text' => 'The process be very time-consuming before they launched the new system.',
                'options' => ['a' => 'might', 'b' => 'would', 'c' => 'ought to', 'd' => 'used to'],
                'correct' => 'd'
            ],
            [
                'num' => 62,
                'text' => 'After some time together, those on John’s team learned not to underestimate .',
                'options' => ['a' => 'each other', 'b' => 'himself', 'c' => 'another', 'd' => 'itself'],
                'correct' => 'a'
            ],
            [
                'num' => 63,
                'text' => 'The man next door asked me keep an eye on his apartment while he was away.',
                'options' => ['a' => 'I can', 'b' => 'would I', 'c' => 'if I could', 'd' => 'whether will I'],
                'correct' => 'c'
            ],
            [
                'num' => 64,
                'text' => 'Our niece is very hardworking and determined. She has never had any trouble her exams.',
                'options' => ['a' => 'to pass', 'b' => 'passing', 'c' => 'passed', 'd' => 'pass'],
                'correct' => 'b'
            ],
            [
                'num' => 65,
                'text' => 'If they the damage more carefully, they would have found these other problems.',
                'options' => ['a' => 'would assess', 'b' => 'had assessed', 'c' => 'have assessed', 'd' => 'would have assessed'],
                'correct' => 'b'
            ],
            [
                'num' => 66,
                'text' => 'By this time next Monday, a new head of the sales department.',
                'options' => ['a' => 'we hire', 'b' => 'we’re hiring', 'c' => 'we’ll have hired', 'd' => 'we have been hiring'],
                'correct' => 'c'
            ],
            [
                'num' => 67,
                'text' => 'The consultants proposed a number of alternatives, the firm disregarded.',
                'options' => ['a' => 'much of what', 'b' => 'many of which', 'c' => 'some of whom', 'd' => 'none of whose'],
                'correct' => 'b'
            ],
            [
                'num' => 68,
                'text' => 'What a couple of relaxing days at an unspoiled beach.',
                'options' => ['a' => 'they actually plan', 'b' => 'did they actually plan', 'c' => 'they actually planned was', 'd' => 'have they actually planned are'],
                'correct' => 'c'
            ],
            [
                'num' => 69,
                'text' => 'We felt genuinely shocked. Never again at such an overrated place.',
                'options' => ['a' => 'ate we', 'b' => 'we will eat', 'c' => 'did eat we', 'd' => 'would we eat'],
                'correct' => 'c'
            ],
            [
                'num' => 70,
                'text' => 'Authorities recommend that everyone the highway until repairs are completed.',
                'options' => ['a' => 'avoid', 'b' => 'avoided', 'c' => 'would avoid', 'd' => 'is going to avoid'],
                'correct' => 'a'
            ],
        ];

        foreach ($langQuestions as $index => $q) {
            $question = Question::create([
                'test_section_id' => $langUse->id,
                'question_number' => $q['num'],
                'question_text' => $q['text'],
                'audio_path' => null,
                'order' => $index + 1,
            ]);

            foreach ($q['options'] as $label => $textOpt) {
                QuestionOption::create([
                    'question_id' => $question->id,
                    'label' => $label,
                    'option_text' => $textOpt,
                    'is_correct' => ($label === $q['correct']),
                ]);
            }
        }

        // ==========================================================================
        // TEST 2: JUNIOR PLACEMENT TEST (Academy Stars - 60 Questions, No Audio)
        // ==========================================================================
        $juniorTest = Test::create([
            'title' => 'Junior Placement Test - Academy Stars',
            'description' => 'Official Academy Stars English language placement evaluation for younger learners.',
            'is_active' => true,
            'show_result_to_student' => true,
        ]);

        $juniorSection = TestSection::create([
            'test_id' => $juniorTest->id,
            'title' => 'Written Evaluation',
            'type' => 'language_use',
            'duration_minutes' => 45,
            'instructions' => 'Complete the questions below by choosing the correct option (A–D). There are 60 items, and you have 45 minutes to complete this section.',
            'order' => 1,
        ]);

        $juniorQuestions = [
    // Page 3
    [
        'num' => 1,
        'text' => 'My name is Tom. ______ a boy.',
        'options' => ['a' => 'It\'s', 'b' => 'I\'m', 'c' => 'He\'s', 'd' => 'She\'s'],
        'correct' => 'b'
    ],
    [
        'num' => 2,
        'text' => 'Is it a bag?',
        'options' => ['a' => 'No, it is.', 'b' => 'Yes, it is.', 'c' => 'No, it isn\'t.', 'd' => 'Yes, it isn\'t.'],
        'correct' => 'b'
    ],
    [
        'num' => 3,
        'text' => 'That\'s my ______ . He\'s old.',
        'options' => ['a' => 'baby', 'b' => 'family', 'c' => 'brother', 'd' => 'grandpa'],
        'correct' => 'd'
    ],
    [
        'num' => 4,
        'text' => 'Are you ______ ? Yes, we are.',
        'options' => ['a' => 'hungry', 'b' => 'angry', 'c' => 'cold', 'd' => 'hot'],
        'correct' => 'a'
    ],
    [
        'num' => 5,
        'text' => 'A panda can ______ a tree.',
        'options' => ['a' => 'jump', 'b' => 'throw', 'c' => 'catch', 'd' => 'climb'],
        'correct' => 'd'
    ],
    [
        'num' => 6,
        'text' => 'Where are the paints? They\'re ______ the box.',
        'options' => ['a' => 'in', 'b' => 'on', 'c' => 'under', 'd' => 'next to'],
        'correct' => 'a'
    ],

    // Page 4
    [
        'num' => 7,
        'text' => 'My rabbits have got small feet, long ears and two ______ .',
        'options' => ['a' => 'eyes', 'b' => 'tails', 'c' => 'arms', 'd' => 'legs'],
        'correct' => 'a'
    ],
    [
        'num' => 8,
        'text' => 'What ______ you wear to school?',
        'options' => ['a' => 'does', 'b' => 'are', 'c' => 'do', 'd' => 'is'],
        'correct' => 'c'
    ],
    [
        'num' => 9,
        'text' => 'There are two ______ in the room.',
        'options' => ['a' => 'wardrobes', 'b' => 'clocks', 'c' => 'lamps', 'd' => 'beds'],
        'correct' => 'd'
    ],
    [
        'num' => 10,
        'text' => 'I like ______ but I don\'t like bananas.',
        'options' => ['a' => 'tomatoes', 'b' => 'coconuts', 'c' => 'mangoes', 'd' => 'pears'],
        'correct' => 'b'
    ],
    [
        'num' => 11,
        'text' => 'There are lots of ______ in Australia but there aren’t any pandas.',
        'options' => ['a' => 'bears', 'b' => 'camels', 'c' => 'wolves', 'd' => 'kangaroos'],
        'correct' => 'd'
    ],
    [
        'num' => 12,
        'text' => 'We ______ on Friday.',
        'options' => ['a' => 'go to school', 'b' => 'do gymnastics', 'c' => 'play basketball', 'd' => 'have a music lesson'],
        'correct' => 'b'
    ],

    // Page 5
    [
        'num' => 13,
        'text' => 'Whose ______ are these? They\'re Jane\'s.',
        'options' => ['a' => 'guitar', 'b' => 'glasses', 'c' => 'camera', 'd' => 'headphones'],
        'correct' => 'b'
    ],
    [
        'num' => 14,
        'text' => 'Kirsty ______ her grandma on Saturdays.',
        'options' => ['a' => 'visit', 'b' => 'visits', 'c' => 'don\'t visit', 'd' => 'doesn\'t visit'],
        'correct' => 'b'
    ],
    [
        'num' => 15,
        'text' => 'What are John and Kate doing? They ______ playing in the snow.',
        'options' => ['a' => 'is', 'b' => 'do', 'c' => 'are', 'd' => 'can'],
        'correct' => 'c'
    ],
    [
        'num' => 16,
        'text' => 'Is your brother sleeping? No, he ______ . He\'s reading a book.',
        'options' => ['a' => 'isn\'t', 'b' => 'don\'t', 'c' => 'aren\'t', 'd' => 'doesn\'t'],
        'correct' => 'a'
    ],
    [
        'num' => 17,
        'text' => 'I like making a snowman in ______ .',
        'options' => ['a' => 'spring', 'b' => 'summer', 'c' => 'autumn', 'd' => 'winter'],
        'correct' => 'd'
    ],
    [
        'num' => 18,
        'text' => '______ you like some grapes? Yes, please.',
        'options' => ['a' => 'Do', 'b' => 'Are', 'c' => 'Can', 'd' => 'Would'],
        'correct' => 'd'
    ],

    // Page 6
    [
        'num' => 19,
        'text' => 'Last night I was at home. I was in the ______ . I made a cake with my mum.',
        'options' => ['a' => 'hall', 'b' => 'kitchen', 'c' => 'bathroom', 'd' => 'dining room'],
        'correct' => 'b'
    ],
    [
        'num' => 20,
        'text' => 'Last weekend, I ______ an art gallery with my mum and dad.',
        'options' => ['a' => 'visit', 'b' => 'visits', 'c' => 'visited', 'd' => 'visiting'],
        'correct' => 'c'
    ],
    [
        'num' => 21,
        'text' => 'At school I\'m good at ______ . We are making a website in my class.',
        'options' => ['a' => 'maths', 'b' => 'music', 'c' => 'social studies', 'd' => 'computer studies'],
        'correct' => 'd'
    ],
    [
        'num' => 22,
        'text' => 'We ______ have PE before lunch on Wednesday.',
        'options' => ['a' => 'once', 'b' => 'twice', 'c' => 'always', 'd' => 'three times'],
        'correct' => 'c'
    ],
    [
        'num' => 23,
        'text' => 'I often go to the ______ after school. I need to study.',
        'options' => ['a' => 'library', 'b' => 'cinema', 'c' => 'supermarket', 'd' => 'swimming pool'],
        'correct' => 'a'
    ],
    [
        'num' => 24,
        'text' => 'Last weekend we ______ sad. It was raining.',
        'options' => ['a' => 'is', 'b' => 'am', 'c' => 'was', 'd' => 'were'],
        'correct' => 'd'
    ],

    // Page 7
    [
        'num' => 25,
        'text' => 'There are ______ of shops in my city.',
        'options' => ['a' => 'some', 'b' => 'much', 'c' => 'a lot', 'd' => 'many'],
        'correct' => 'c'
    ],
    [
        'num' => 26,
        'text' => 'Dolphins are ______ than sharks.',
        'options' => ['a' => 'nice', 'b' => 'good', 'c' => 'worst', 'd' => 'friendlier'],
        'correct' => 'd'
    ],
    [
        'num' => 27,
        'text' => 'Did you ______ a good day yesterday? Yes, I went to a restaurant with my friends.',
        'options' => ['a' => 'has', 'b' => 'had', 'c' => 'have', 'd' => 'having'],
        'correct' => 'c'
    ],
    [
        'num' => 28,
        'text' => 'Were there any windows in the cave? No, there ______ .',
        'options' => ['a' => 'are', 'b' => 'aren\'t', 'c' => 'wasn\'t', 'd' => 'weren\'t'],
        'correct' => 'd'
    ],
    [
        'num' => 29,
        'text' => 'We’re going swimming today. Don’t forget your ______ .',
        'options' => ['a' => 'glove', 'b' => 'trainers', 'c' => 'goggles', 'd' => 'tracksuit'],
        'correct' => 'c'
    ],
    [
        'num' => 30,
        'text' => '______ you going to France on holiday? Yes, I am.',
        'options' => ['a' => 'Do', 'b' => 'Are', 'c' => 'Can', 'd' => 'Am'],
        'correct' => 'b'
    ],

    // Page 8
    [
        'num' => 31,
        'text' => 'Did you climb this ______ when you went to Italy? Yes, we did.',
        'options' => ['a' => 'lake', 'b' => 'island', 'c' => 'volcano', 'd' => 'countryside'],
        'correct' => 'c'
    ],
    [
        'num' => 32,
        'text' => 'I ______ tie my shoelaces when I was seven.',
        'options' => ['a' => 'could', 'b' => 'was', 'c' => 'can', 'd' => 'did'],
        'correct' => 'a'
    ],
    [
        'num' => 33,
        'text' => 'Remember to take your ______ . It’s dark outside.',
        'options' => ['a' => 'safety vest', 'b' => 'brakes', 'c' => 'wheel', 'd' => 'gears'],
        'correct' => 'a'
    ],
    [
        'num' => 34,
        'text' => 'Do you like my cake? Yes, it is ______ delicious than mine.',
        'options' => ['a' => 'most', 'b' => 'more', 'c' => 'much', 'd' => 'many'],
        'correct' => 'b'
    ],
    [
        'num' => 35,
        'text' => 'I’ve got a ______ . You shouldn’t talk.',
        'options' => ['a' => 'earache', 'b' => 'sore throat', 'c' => 'broken arm', 'd' => 'stomach ache'],
        'correct' => 'b'
    ],
    [
        'num' => 36,
        'text' => 'Why ______ you buying a present? It was my mum\'s birthday.',
        'options' => ['a' => 'was', 'b' => 'were', 'c' => 'wasn\'t', 'd' => 'weren\'t'],
        'correct' => 'b'
    ],

    // Page 9
    [
        'num' => 37,
        'text' => 'Can I use this for my smartphone? Whose ______ is it? It’s my uncle’s, but you can use it.',
        'options' => ['a' => 'charger', 'b' => 'laptop', 'c' => 'mouse', 'd' => 'screen'],
        'correct' => 'a'
    ],
    [
        'num' => 38,
        'text' => 'A fish has got tiny ______ .',
        'options' => ['a' => 'fur', 'b' => 'petals', 'c' => 'scales', 'd' => 'feathers'],
        'correct' => 'c'
    ],
    [
        'num' => 39,
        'text' => 'What ______ if you put a marble in water? It sinks.',
        'options' => ['a' => 'happen', 'b' => 'happens', 'c' => 'happened', 'd' => 'happening'],
        'correct' => 'b'
    ],
    [
        'num' => 40,
        'text' => 'He ______ cleaned his dad’s car. Now he is tired.',
        'options' => ['a' => 'is', 'b' => 'did', 'c' => 'was', 'd' => 'has'],
        'correct' => 'd'
    ],
    [
        'num' => 41,
        'text' => 'We were having our English lesson when the ambulance ______ .',
        'options' => ['a' => 'came', 'b' => 'comes', 'c' => 'has come', 'd' => 'was coming'],
        'correct' => 'a'
    ],
    [
        'num' => 42,
        'text' => 'I ______ salad when I was a child, but I love it now.',
        'options' => ['a' => 'I’m not use to like', 'b' => 'I didn’t use to like', 'c' => 'I don’t used to like', 'd' => 'I didn’t used to like'],
        'correct' => 'b'
    ],

    # Page 10
    [
        'num' => 43,
        'text' => 'Has Tom ever ______ before? No, he hasn’t. We should help him.',
        'options' => ['a' => 'put up a tent', 'b' => 'been kayaking', 'c' => 'swum with dolphins', 'd' => 'been in a hot-air balloon'],
        'correct' => 'a'
    ],
    [
        'num' => 44,
        'text' => 'How long have you been a ______ ? For about five years.',
        'options' => ['a' => 'pilot', 'b' => 'dentist', 'c' => 'gardener', 'd' => 'paramedic'],
        'correct' => 'a'
    ],
    [
        'num' => 45,
        'text' => 'Motorbikes aren’t as ______ as planes.',
        'options' => ['a' => 'fast', 'b' => 'faster', 'c' => 'fastest', 'd' => 'more fast'],
        'correct' => 'a'
    ],
    [
        'num' => 46,
        'text' => 'I like your new jeans. Thanks. They are really warm because they’re made of ______ .',
        'options' => ['a' => 'wood', 'b' => 'metal', 'c' => 'denim', 'd' => 'leather'],
        'correct' => 'c'
    ],
    [
        'num' => 47,
        'text' => 'I’m learning to play the guitar. One day I ______ a famous musician.',
        'options' => ['a' => 'am', 'b' => 'will be', 'c' => 'am being', 'd' => 'have been'],
        'correct' => 'b'
    ],
    [
        'num' => 48,
        'text' => 'My mum will be furious if I ______ call her.',
        'options' => ['a' => 'don’t', 'b' => 'won’t', 'c' => 'haven’t', 'd' => 'shouldn’t'],
        'correct' => 'a'
    ],

    # Page 11
    [
        'num' => 49,
        'text' => 'I’m really hungry. I haven’t eaten ______ today.',
        'options' => ['a' => 'something', 'b' => 'anything', 'c' => 'nothing', 'd' => 'many'],
        'correct' => 'b'
    ],
    [
        'num' => 50,
        'text' => 'I’m sure that man stole my bag. But we can’t ______ that he did it at the moment.',
        'options' => ['a' => 'solve', 'b' => 'prove', 'c' => 'borrow', 'd' => 'behave'],
        'correct' => 'b'
    ],
    [
        'num' => 51,
        'text' => 'There are five different ______ of rhino in the world: black, white, Javan, greater one-horned and Sumatran.',
        'options' => ['a' => 'species', 'b' => 'habitats', 'c' => 'predators', 'd' => 'sanctuaries'],
        'correct' => 'a'
    ],
    [
        'num' => 52,
        'text' => 'I was happy when I got to my music lesson because my friend ______ her guitar to school.',
        'options' => ['a' => 'brought', 'b' => 'had brought', 'c' => 'has brought', 'd' => 'was bringing'],
        'correct' => 'b'
    ],
    [
        'num' => 53,
        'text' => 'How’s the cake you made? It tastes really ______ ! I think I put too much sugar in it.',
        'options' => ['a' => 'sour', 'b' => 'salty', 'c' => 'bitter', 'd' => 'sweet'],
        'correct' => 'd'
    ],
    [
        'num' => 54,
        'text' => 'They ______ the beach for hours! They must be tired.',
        'options' => ['a' => 'cleaned', 'b' => 'had cleaned', 'c' => 'have cleaned', 'd' => 'have been cleaning'],
        'correct' => 'd'
    ],

    # Page 12
    [
        'num' => 55,
        'text' => 'What are you doing on Saturday? I ______ a museum with my grandpa.',
        'options' => ['a' => 'I visit', 'b' => 'I will visit', 'c' => 'I’m visiting', 'd' => 'I have visited'],
        'correct' => 'c'
    ],
    [
        'num' => 56,
        'text' => 'Indoor skydiving was great, ______ ? Yeah, I want to go again!',
        'options' => ['a' => 'was it', 'b' => 'didn’t it', 'c' => 'wasn’t it', 'd' => 'doesn’t it'],
        'correct' => 'c'
    ],
    [
        'num' => 57,
        'text' => 'To ______ for the event you need to have a certain level of fitness.',
        'options' => ['a' => 'qualify', 'b' => 'achieve', 'c' => 'exercise', 'd' => 'persevere'],
        'correct' => 'a'
    ],
    [
        'num' => 58,
        'text' => 'What’s wrong? All my photos ______ from my laptop yesterday. I’m trying to get them back.',
        'options' => ['a' => 'delete', 'b' => 'deleted', 'c' => 'are deleted', 'd' => 'were deleted'],
        'correct' => 'd'
    ],
    [
        'num' => 59,
        'text' => 'I loved that film. The ______ were fantastic. Yeah, especially the actor who played the hero.',
        'options' => ['a' => 'set', 'b' => 'plot', 'c' => 'cast', 'd' => 'script'],
        'correct' => 'c'
    ],
    [
        'num' => 60,
        'text' => 'If I had my own money, I ______ buy myself a smartphone.',
        'options' => ['a' => 'should', 'b' => 'would', 'c' => 'can', 'd' => 'will'],
        'correct' => 'b'
    ]
];

        foreach ($juniorQuestions as $index => $q) {
            $imagePath = null;
            $potentialPath = 'quiz_img/' . $q['num'] . '.png';
            if (file_exists(public_path($potentialPath))) {
                $imagePath = $potentialPath;
            }

            $question = Question::create([
                'test_section_id' => $juniorSection->id,
                'question_number' => $q['num'],
                'question_text' => $q['text'],
                'audio_path' => null,
                'image_path' => $imagePath,
                'order' => $index + 1,
            ]);

            foreach ($q['options'] as $label => $textOpt) {
                QuestionOption::create([
                    'question_id' => $question->id,
                    'label' => $label,
                    'option_text' => $textOpt,
                    'is_correct' => ($label === $q['correct']),
                ]);
            }
        }

        // ==========================================================================
        // TEST 3: SENIOR PLACEMENT TEST (Language Hub - 70 Questions, No Audio)
        // ==========================================================================
        $seniorTest = Test::create([
            'title' => 'Senior Placement Test - Language Hub',
            'description' => 'Official Language Hub English language placement evaluation for Beginner to Advanced learners.',
            'is_active' => true,
            'show_result_to_student' => true,
        ]);

        $seniorSection = TestSection::create([
            'test_id' => $seniorTest->id,
            'title' => 'Written Evaluation',
            'type' => 'language_use',
            'duration_minutes' => 30,
            'instructions' => 'Complete the dialogues below by choosing the correct option (A–D). There are 70 items, and you have 30 minutes to complete this section.',
            'order' => 1,
        ]);

        $seniorQuestions = [
    [
        'num' => 1,
        'text' => 'Manager:  Where’s Mr Davidson? Assistant:  Oh, he’s   London today.',
        'options' => [
            'a' => 'in',
            'b' => 'on',
            'c' => 'to',
            'd' => 'at',
        ],
        'correct' => 'a'
    ],
    [
        'num' => 2,
        'text' => 'Amirah:  Do you like cats? Chris:  No, but there   lots of other animals I like.',
        'options' => [
            'a' => 'is',
            'b' => 'be',
            'c' => 'are',
            'd' => 'was',
        ],
        'correct' => 'c'
    ],
    [
        'num' => 3,
        'text' => 'Andrew:  Where   Alicia come from? Martin:  I think she’s from the United States.',
        'options' => [
            'a' => 'is',
            'b' => 'do',
            'c' => 'are',
            'd' => 'does',
        ],
        'correct' => 'd'
    ],
    [
        'num' => 4,
        'text' => 'Teacher:  Tell me something about your parents, Lucas. Student:  My mother and father   both very tall.',
        'options' => [
            'a' => 'is',
            'b' => 'isn’t',
            'c' => 'are',
            'd' => 'aren’t',
        ],
        'correct' => 'c'
    ],
    [
        'num' => 5,
        'text' => 'Ayla:  That’s a nice table, Sophie! Is it new? Sophie:  Oh no, it’s my   old dining table.',
        'options' => [
            'a' => 'mother',
            'b' => 'mothers',
            'c' => 'mother’s',
            'd' => 'mothers’',
        ],
        'correct' => 'c'
    ],
    [
        'num' => 6,
        'text' => 'Emma: What do you do after school? Chloe:  I see my friends. Do you visit people, too? Emma:  No, I   go out.',
        'options' => [
            'a' => 'often',
            'b' => 'never',
            'c' => 'always',
            'd' => 'sometimes',
        ],
        'correct' => 'b'
    ],
    [
        'num' => 7,
        'text' => 'Katie:  Is Charlotte at school today? Laura:  No, she   . She’s not well today.',
        'options' => [
            'a' => 'isn’t',
            'b' => 'aren’t',
            'c' => 'doesn’t',
            'd' => 'hasn’t',
        ],
        'correct' => 'a'
    ],
    [
        'num' => 8,
        'text' => 'Alex:  I’d like to make a cake.   eggs have we got? Andrea:  Three, I think. Let me check.',
        'options' => [
            'a' => 'How big',
            'b' => 'How much',
            'c' => 'How many',
            'd' => 'How long',
        ],
        'correct' => 'c'
    ],
    [
        'num' => 9,
        'text' => 'Ifrah:  Which bus goes to the hospital? Antonia:    the 236. It stops outside.',
        'options' => [
            'a' => 'Get',
            'b' => 'Got',
            'c' => 'Gets',
            'd' => 'Getting',
        ],
        'correct' => 'a'
    ],
    [
        'num' => 10,
        'text' => 'Father:  Are we ready to go? Daughter:  No, Mum can’t find   hat.',
        'options' => [
            'a' => 'its',
            'b' => 'his',
            'c' => 'her',
            'd' => 'their',
        ],
        'correct' => 'c'
    ],
    [
        'num' => 11,
        'text' => 'Shop  Assistant: Can I help you? Customer:  Yes, I’d like to buy   trousers.',
        'options' => [
            'a' => 'a',
            'b' => 'an',
            'c' => 'this',
            'd' => 'these',
        ],
        'correct' => 'd'
    ],
    [
        'num' => 12,
        'text' => 'Mother:  Where’s that fish I bought? It was on the table. Daughter:  Oh no! The cat   it.',
        'options' => [
            'a' => 'eat',
            'b' => 'eats',
            'c' => 'is eating',
            'd' => 'are eating',
        ],
        'correct' => 'c'
    ],
    [
        'num' => 13,
        'text' => 'Amanda:  I like your new sofa. Fahima:   Thanks. It’s   comfortable than the other one we had.',
        'options' => [
            'a' => 'too',
            'b' => 'very',
            'c' => 'much',
            'd' => 'more',
        ],
        'correct' => 'd'
    ],
    [
        'num' => 14,
        'text' => 'Alicia:  I’m going to the supermarket. Do you want anything? Peter:  Could you get   milk, please?',
        'options' => [
            'a' => 'a',
            'b' => 'any',
            'c' => 'some',
            'd' => 'every',
        ],
        'correct' => 'c'
    ],
    [
        'num' => 15,
        'text' => 'Karina:  When do you want to play football? Aniqa:   I   to play tomorrow, because I don’t need to go to work.',
        'options' => [
            'a' => 'like',
            'b' => 'likes',
            'c' => 'liked',
            'd' => '‘d like',
        ],
        'correct' => 'd'
    ],
    [
        'num' => 16,
        'text' => 'Manisha:  What did you do at the weekend? Nicola:  I   tennis with my friend on Saturday.',
        'options' => [
            'a' => 'play',
            'b' => 'played',
            'c' => 'plays',
            'd' => 'playing',
        ],
        'correct' => 'b'
    ],
    [
        'num' => 17,
        'text' => 'Wife:  Have we got any cheese in the fridge? Husband:   No, we haven’t. I’m   buy some this afternoon.',
        'options' => [
            'a' => 'go',
            'b' => 'go to',
            'c' => 'going',
            'd' => 'going to',
        ],
        'correct' => 'd'
    ],
    [
        'num' => 18,
        'text' => 'Laura:   Where   you last Tuesday? I tried to phone you. Beatriz:   Oh, I was visiting my grandmother. I didn’t have my phone with me.',
        'options' => [
            'a' => 'were',
            'b' => 'was',
            'c' => 'are',
            'd' => 'is',
        ],
        'correct' => 'a'
    ],
    [
        'num' => 19,
        'text' => 'Miriam:  Are you coming to my party on Tuesday? Brian:   I’m really sorry, but I   to take my daughter to the airport.',
        'options' => [
            'a' => 'has',
            'b' => 'had',
            'c' => 'have',
            'd' => 'having',
        ],
        'correct' => 'c'
    ],
    [
        'num' => 20,
        'text' => 'Saif:  Why do you like running? Isabella:  Because it’s   way to keep fit.',
        'options' => [
            'a' => 'best',
            'b' => 'better',
            'c' => 'the best',
            'd' => 'the better',
        ],
        'correct' => 'c'
    ],
    [
        'num' => 21,
        'text' => 'Anna:  Have you lived here a long time? Stefan:   Yes, over 40 years. I know   of people in this town.',
        'options' => [
            'a' => 'any',
            'b' => 'lots',
            'c' => 'more',
            'd' => 'most',
        ],
        'correct' => 'b'
    ],
    [
        'num' => 22,
        'text' => 'Josef:  Why didn’t you come to the cinema last week? Chloe:   I wanted to but I couldn’t. I   studying for that test we had on Monday.',
        'options' => [
            'a' => 'was',
            'b' => 'were',
            'c' => 'am',
            'd' => 'been',
        ],
        'correct' => 'a'
    ],
    [
        'num' => 23,
        'text' => 'Anna: That bird’s on the garden table again. I think it’s hungry. Juliana:  Yes, look! It   eat the bread we put there.',
        'options' => [
            'a' => 'is',
            'b' => 'will',
            'c' => 'goes to',
            'd' => 'is going to',
        ],
        'correct' => 'd'
    ],
    [
        'num' => 24,
        'text' => 'Sophie:  How long   married? Ying  Yue:  Two years. I met my husband when I was working in New York.',
        'options' => [
            'a' => 'had you got',
            'b' => 'did you get',
            'c' => 'have you been',
            'd' => 'are you being',
        ],
        'correct' => 'c'
    ],
    [
        'num' => 25,
        'text' => 'David:  Have you   that new film yet? Susanna:  No, I haven’t. We could go on Thursday if you like?',
        'options' => [
            'a' => 'see',
            'b' => 'saw',
            'c' => 'seen',
            'd' => 'seeing',
        ],
        'correct' => 'c'
    ],
    [
        'num' => 26,
        'text' => 'Shop  Assistant: Excuse me, please. Could I get past? Customer:  Oh, I’m sorry. I’m getting in the way,    I?',
        'options' => [
            'a' => 'don’t',
            'b' => 'aren’t',
            'c' => 'can’t',
            'd' => 'haven’t',
        ],
        'correct' => 'b'
    ],
    [
        'num' => 27,
        'text' => 'Wife:   Advertising is a big business for musicians. Husband:   Yes, musicians   a lot of money for writing short pieces of music.',
        'options' => [
            'a' => 'pay',
            'b' => 'paid',
            'c' => 'are  paid',
            'd' => 'are paying',
        ],
        'correct' => 'c'
    ],
    [
        'num' => 28,
        'text' => 'Son:  Mum, I’d really like a guitar. Can I have one? Mother:   OK, but if we buy one you   have to practise playing it.',
        'options' => [
            'a' => 'will',
            'b' => 'can',
            'c' => 'could',
            'd' => 'must',
        ],
        'correct' => 'a'
    ],
    [
        'num' => 29,
        'text' => 'Juliana:  Do you like Brazilian coffee? Miriodere:  No I don’t, because it’s   strong.',
        'options' => [
            'a' => 'too',
            'b' => 'such',
            'c' => 'much',
            'd' => 'enough',
        ],
        'correct' => 'a'
    ],
    [
        'num' => 30,
        'text' => 'Matthew:  Would you like anything from the shop? Alicia:   Yes, I’d like one of   celebrity magazines, please.',
        'options' => [
            'a' => 'most recent',
            'b' => 'more recent',
            'c' => 'the  most recent',
            'd' => 'the more recent',
        ],
        'correct' => 'c'
    ],
    [
        'num' => 31,
        'text' => 'Daughter:   Mum, my computer is broken again. I really need a new one. Mother:   I   buy one if we had the money, but it’s not possible right now.',
        'options' => [
            'a' => 'will',
            'b' => 'may',
            'c' => 'should',
            'd' => 'would',
        ],
        'correct' => 'd'
    ],
    [
        'num' => 32,
        'text' => 'Mother:     you packed your suitcase yet? We’re leaving early tomorrow morning. Son:  I’ll do it later. It won’t take long.',
        'options' => [
            'a' => 'Did',
            'b' => 'Have',
            'c' => 'Will',
            'd' => 'Are',
        ],
        'correct' => 'b'
    ],
    [
        'num' => 33,
        'text' => 'Lucas:  Do you play the piano, Natasha? Natasha:   Well, I   play when I was younger, but I’m not sure I remember now.',
        'options' => [
            'a' => 'can',
            'b' => 'can’t',
            'c' => 'could',
            'd' => 'couldn’t',
        ],
        'correct' => 'c'
    ],
    [
        'num' => 34,
        'text' => 'Martina:  What did the doctor say about your stomach pains? Padma:  He asked me what I   for the last two days.',
        'options' => [
            'a' => 'eat',
            'b' => 'had eaten',
            'c' => 'was  eating',
            'd' => 'would eat',
        ],
        'correct' => 'b'
    ],
    [
        'num' => 35,
        'text' => 'Daughter:  Everyone has arrived apart from Pamela. Mother:   Don’t worry, she phoned me this morning and said she   be a bit late.',
        'options' => [
            'a' => 'can',
            'b' => 'must',
            'c' => 'should',
            'd' => 'would',
        ],
        'correct' => 'd'
    ],
    [
        'num' => 36,
        'text' => 'Vincent:   Did you see the weather forecast? It’s going to be extremely hot this weekend. Pauline:  I know, I can’t believe it! It   since Monday.',
        'options' => [
            'a' => 'rains',
            'b' => 'has been raining',
            'c' => 'is raining',
            'd' => 'was raining',
        ],
        'correct' => 'b'
    ],
    [
        'num' => 37,
        'text' => 'Ameena:   What colour are you going to paint the living room? Charlotte:   I   probably choose something bright, like yellow.',
        'options' => [
            'a' => 'will',
            'b' => 'may',
            'c' => 'can',
            'd' => 'might',
        ],
        'correct' => 'a'
    ],
    [
        'num' => 38,
        'text' => 'Victor:   I’d love to go back in history to see how people lived hundreds of years ago. Simon:   Me too! If I   choose, I’d probably travel to ancient Rome.',
        'options' => [
            'a' => 'can',
            'b' => 'will',
            'c' => 'could',
            'd' => 'would',
        ],
        'correct' => 'c'
    ],
    [
        'num' => 39,
        'text' => 'Stephen:   The concert was fantastic yesterday. You have come. Yuuto:  I know. I wanted to, but I had to work late.',
        'options' => [
            'a' => 'must',
            'b' => 'could',
            'c' => 'ought',
            'd' => 'should',
        ],
        'correct' => 'd'
    ],
    [
        'num' => 40,
        'text' => 'Katie:  Would you like to go sightseeing or to the beach this afternoon? Matthew:  I don’t mind, I’ll let you decide. Katie:  OK, let’s go sightseeing,   we?',
        'options' => [
            'a' => 'should',
            'b' => 'shall',
            'c' => 'might',
            'd' => 'would',
        ],
        'correct' => 'b'
    ],
    [
        'num' => 41,
        'text' => 'Amanda:   It said on the news that the president also owns all the national newspapers. Andrew:  That   be right! I don’t think that’s true.',
        'options' => [
            'a' => 'must',
            'b' => 'can’t',
            'c' => 'won’t',
            'd' => 'would',
        ],
        'correct' => 'b'
    ],
    [
        'num' => 42,
        'text' => 'Assistant:   That meeting was really difficult. What would you have done if you   in my position? Manager:  Oh, I think you managed it very well.',
        'options' => [
            'a' => 'are',
            'b' => 'were',
            'c' => 'had been',
            'd' => 'would be',
        ],
        'correct' => 'c'
    ],
    [
        'num' => 43,
        'text' => 'Natalia:  My new smartphone doesn’t seem to work. Katie:   Oh dear! Perhaps you should take it   and ask for a refund.',
        'options' => [
            'a' => 'up',
            'b' => 'out',
            'c' => 'away',
            'd' => 'back',
        ],
        'correct' => 'd'
    ],
    [
        'num' => 44,
        'text' => 'Chris:  I wish I could be with our cousins … Alison:   Me too! By this time tomorrow they   on a Greek beach while we’re revising for our history test.',
        'options' => [
            'a' => 'sunbathe',
            'b' => 'will sunbathe',
            'c' => 'will  be sunbathing',
            'd' => 'will have sunbathed',
        ],
        'correct' => 'c'
    ],
    [
        'num' => 45,
        'text' => 'Son:  Are you OK, Mum? You don’t seem very relaxed. Mother:   I just wish I   an aisle seat so that I could get up and walk around more easily.',
        'options' => [
            'a' => 'had chosen',
            'b' => 'have chosen',
            'c' => 'would choose',
            'd' => 'should choose',
        ],
        'correct' => 'a'
    ],
    [
        'num' => 46,
        'text' => 'Nicola:  I love this picture, but won’t it cost a fortune? Victor:   No, it’s just a copy. The original,   is a portrait of the artist’s friend, sold for €4 million!',
        'options' => [
            'a' => 'whose',
            'b' => 'which',
            'c' => 'whom',
            'd' => 'that',
        ],
        'correct' => 'b'
    ],
    [
        'num' => 47,
        'text' => 'Laura:  I can’t believe how talented this artist was. Emily:   I know, it’s amazing.   he was almost 90 when he did them, his paintings are beautiful.',
        'options' => [
            'a' => 'Since',
            'b' => 'Besides',
            'c' => 'Although',
            'd' => 'Therefore',
        ],
        'correct' => 'c'
    ],
    [
        'num' => 48,
        'text' => 'Andrea:  I want to buy some new shoes for the winter. Shan:   Well, I   looking for a new pair of boots for weeks, but I can’t find anything I like.',
        'options' => [
            'a' => 'am',
            'b' => 'was',
            'c' => 'had  been',
            'd' => 'have been',
        ],
        'correct' => 'd'
    ],
    [
        'num' => 49,
        'text' => 'Client:  I don’t have much money – just enough to   . Accountant:   Well, let me suggest a way of helping you save more.',
        'options' => [
            'a' => 'get by',
            'b' => 'pay off',
            'c' => 'do  with',
            'd' => 'make up',
        ],
        'correct' => 'a'
    ],
    [
        'num' => 50,
        'text' => 'Pablo:  In April next year I   here for ten years exactly. Alison:  Wow! It really doesn’t seem that long.',
        'options' => [
            'a' => 'will live',
            'b' => 'will be living',
            'c' => 'am  going to live',
            'd' => 'will have been living',
        ],
        'correct' => 'd'
    ],
    [
        'num' => 51,
        'text' => 'Student:   Is it true that it took Bell and Watson ages to invent the telephone? Teacher:   Yes. When they finally succeeded, they   on it for about 30 years.',
        'options' => [
            'a' => 'must work',
            'b' => 'had been working',
            'c' => 'have  worked',
            'd' => 'would be working',
        ],
        'correct' => 'b'
    ],
    [
        'num' => 52,
        'text' => 'Rachel:  This would be a lovely place to sit on a dry day. Natasha:  Yes, I know. I just wish the rain   .',
        'options' => [
            'a' => 'would stop',
            'b' => 'has stopped',
            'c' => 'will  have stopped',
            'd' => 'would be stopping',
        ],
        'correct' => 'a'
    ],
    [
        'num' => 53,
        'text' => 'Student:  What’s today’s lesson going to be about? Teacher:   Today we’re going to learn about a tribe descendants live in Lima, the capital of Peru.',
        'options' => [
            'a' => 'who',
            'b' => 'which',
            'c' => 'whose',
            'd' => 'whom',
        ],
        'correct' => 'c'
    ],
    [
        'num' => 54,
        'text' => 'Andrea:  Did your town have a good market? Katie:   Yes. When I was young we   there every Saturday looking for bargains.',
        'options' => [
            'a' => 'had gone',
            'b' => 'would go',
            'c' => 'were going',
            'd' => 'had been going',
        ],
        'correct' => 'b'
    ],
    [
        'num' => 55,
        'text' => 'Daughter:   Joanna has been really supportive. I’m so lucky to have her as a friend. Mother:   Yes. Just think – if you hadn’t sat next to her in class at school, you   so close now.',
        'options' => [
            'a' => 'won’t be',
            'b' => 'wouldn’t be',
            'c' => 'wouldn’t have been',
            'd' => 'aren’t',
        ],
        'correct' => 'b'
    ],
    [
        'num' => 56,
        'text' => 'David:  Did you see the headline this evening? Nicola:  Yes – the Prime Minister was   to resign today.',
        'options' => [
            'a' => 'charged',
            'b' => 'argued',
            'c' => 'struggled',
            'd' => 'forced',
        ],
        'correct' => 'd'
    ],
    [
        'num' => 57,
        'text' => 'Student:   I’m concerned about the chemical test results I’ve just had from the river. Professor:   It   be a good idea to check the acid levels as well then.',
        'options' => [
            'a' => 'must',
            'b' => 'should',
            'c' => 'might',
            'd' => 'ought',
        ],
        'correct' => 'c'
    ],
    [
        'num' => 58,
        'text' => 'Aamir:   They’ve just announced that our train has been delayed. Laura:   That’s annoying. We   have rushed to get here after all.',
        'options' => [
            'a' => 'needn’t',
            'b' => 'could',
            'c' => 'should',
            'd' => 'mustn’t',
        ],
        'correct' => 'a'
    ],
    [
        'num' => 59,
        'text' => 'Liam:  So, your Dad’s got a laptop! Cian:   Yes, I bought it for him last year – until then he a typewriter!',
        'options' => [
            'a' => 'used',
            'b' => 'has used',
            'c' => 'has been using',
            'd' => 'had been using',
        ],
        'correct' => 'd'
    ],
    [
        'num' => 60,
        'text' => 'Isabella:   The flight is fully booked, so I won’t be able to go to Barbados next week. Safia:  If you    the ticket sooner, you’d have found a seat.',
        'options' => [
            'a' => 'had booked',
            'b' => 'were booking',
            'c' => 'booked',
            'd' => 'would have booked',
        ],
        'correct' => 'a'
    ],
    [
        'num' => 61,
        'text' => 'Receptionist:   You    taken a taxi to the hotel since you arrived so late. Customer:   It was OK, actually. There was a direct bus service from the airport.',
        'options' => [
            'a' => 'will have',
            'b' => 'should have',
            'c' => 'might have',
            'd' => 'would have',
        ],
        'correct' => 'b'
    ],
    [
        'num' => 62,
        'text' => 'Sophie:   Have they finished interviewing for the manager’s position yet? Rafi:  No, but they    all the candidates by next Friday.',
        'options' => [
            'a' => 'won’t see',
            'b' => 'would see',
            'c' => 'haven’t seen',
            'd' => 'will have seen',
        ],
        'correct' => 'd'
    ],
    [
        'num' => 63,
        'text' => 'Athlete:    hard I try, I can’t run any faster. Coach:  You’ve improved a lot. I wouldn’t worry about it.',
        'options' => [
            'a' => 'Though',
            'b' => 'Whereas',
            'c' => 'However',
            'd' => 'Considering',
        ],
        'correct' => 'c'
    ],
    [
        'num' => 64,
        'text' => 'Laura:  That’s a really beautiful painting. The colours are so vivid. Jeremy:  Yes, it’s amazing to think it was lost for years and   .',
        'options' => [
            'a' => 'must be restored',
            'b' => 'had to be restored',
            'c' => 'has been restoring',
            'd' => 'would be restoring',
        ],
        'correct' => 'b'
    ],
    [
        'num' => 65,
        'text' => 'Charlotte:   I saw the photos from the film festival. Was that you with the actor from The Hobbit? Niall:   Yes, it was!   did I imagine I would ever actually meet him.',
        'options' => [
            'a' => 'Not',
            'b' => 'Much',
            'c' => 'Hardly',
            'd' => 'Little',
        ],
        'correct' => 'd'
    ],
    [
        'num' => 66,
        'text' => 'Pauline:   I hear you got soaked on the golf course this morning. Chris:  Yes. I wish I   listened to the weather forecast.',
        'options' => [
            'a' => 'had',
            'b' => 'have',
            'c' => 'would have',
            'd' => 'should have',
        ],
        'correct' => 'a'
    ],
    [
        'num' => 67,
        'text' => 'Laura:  How was the meeting? Ricardo:   It finished late because Victor didn’t arrive until 5 pm. He told me he   been given the wrong directions.',
        'options' => [
            'a' => 'has',
            'b' => 'had',
            'c' => 'should have',
            'd' => 'would have',
        ],
        'correct' => 'b'
    ],
    [
        'num' => 68,
        'text' => 'Andrew:  I picked up some of that cat food you wanted. Pedro:   Oh good. Once   to these new cat biscuits, they won’t want to go back to the other stuff.',
        'options' => [
            'a' => 'we’ve switched',
            'b' => 'we’ll be switching',
            'c' => 'we’ll have switched',
            'd' => 'we’ve been switched',
        ],
        'correct' => 'a'
    ],
    [
        'num' => 69,
        'text' => 'Antonia:  Has your son done well in his exams? Phillip:   Yes. Only once   he wasn’t sufficiently prepared, but he can take that one again.',
        'options' => [
            'a' => 'he found',
            'b' => 'he has found',
            'c' => 'did he find',
            'd' => 'could he find',
        ],
        'correct' => 'c'
    ],
    [
        'num' => 70,
        'text' => 'Son:  I had a bit of a stomach ache this morning. Mother:   Oh dear! Well, I did say you   eaten that chicken last night.',
        'options' => [
            'a' => 'wouldn’t have',
            'b' => 'couldn’t have',
            'c' => 'mustn’t have',
            'd' => 'shouldn’t have',
        ],
        'correct' => 'd'
    ],
    [
        'num' => 1,
        'text' => 'A 11 D 21 B 31 D 41 B 51 B 61 B 2 C 12 C 22 A 32 B 42 C 52 A 62 D 3 D 13 D 23 D 33 C 43 D 53 C 63 C 4 C 14 C 24 C 34 B 44 C 54 B 64 B 5 C 15 D 25 C 35 D 45 A 55 B 65 D 6 B 16 B 26 B 36 B 46 B 56 D 66 A 7 A 17 D 27 C 37 A 47 C 57 C 67 B 8 C 18 A 28 A 38 C 48 D 58 A 68 A 9 A 19 C 29 A 39 D 49 A 59 D 69 C 10 C 20 C 30 C 40 B 50 D 60 A 70 D ',
        'options' => [
        ],
        'correct' => 'a'
    ],
]
;

        foreach ($seniorQuestions as $index => $q) {
            $question = Question::create([
                'test_section_id' => $seniorSection->id,
                'question_number' => $q['num'],
                'question_text' => $q['text'],
                'audio_path' => null,
                'order' => $index + 1,
            ]);

            foreach ($q['options'] as $label => $textOpt) {
                QuestionOption::create([
                    'question_id' => $question->id,
                    'label' => $label,
                    'option_text' => $textOpt,
                    'is_correct' => ($label === $q['correct']),
                ]);
            }
        }
    }
}
