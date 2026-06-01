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
    }
}
