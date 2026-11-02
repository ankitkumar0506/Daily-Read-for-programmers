<?php
// 2026-11-02 07:58:03

/* PHP
Topic: Using Prepared Statements with PDO for Secure Database Access  

Explanation:  
Prepared statements separate SQL code from data, preventing SQL injection attacks.  
PDO (PHP Data Objects) provides a uniform API for many database systems, making code portable.  
You first prepare the SQL query with placeholders, then bind values and execute.  
The database parses the query once, which can improve performance for repeated executions.  
Errors can be caught with exceptions, allowing clean error handling.  

Code Example:  
<?php
// Create a PDO connection (replace DSN, username, password with real values)
$dsn = 'mysql:host=localhost;dbname=testdb;charset=utf8mb4';
$username = 'dbuser';
$password = 'dbpass';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,   // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Fetch rows as associative arrays
];
$pdo = new PDO($dsn, $username, $password, $options);

// Prepare an INSERT statement with named placeholders
$sql = 'INSERT INTO users (username, email, created_at) VALUES (:username, :email, :created_at)';
$stmt = $pdo->prepare($sql);

// Bind values to the placeholders
$stmt->bindValue(':username', 'alice', PDO::PARAM_STR);
$stmt->bindValue(':email', 'alice@example.com', PDO::PARAM_STR);
$stmt->bindValue(':created_at', date('Y-m-d H:i:s'), PDO::PARAM_STR);

// Execute the statement
$stmt->execute();

// Retrieve the ID of the newly inserted row
$newUserId = $pdo->lastInsertId();
echo "New user inserted with ID: $newUserId";
?>
*/

/* Laravel
Topic: Laravel Queues with Redis

Explanation:
Laravel queues allow time‑consuming tasks to be processed in the background, keeping HTTP requests fast.  
Redis is a fast, in‑memory data store that works well as a queue driver because it supports atomic list operations.  
When a job is dispatched, Laravel pushes a serialized payload onto a Redis list; a worker then pops jobs off the list and executes them.  
You can run multiple workers to scale processing, and Laravel automatically retries failed jobs based on your configuration.  
Using queues improves user experience, reduces server load, and enables reliable retry and failure handling.

Code example (Job definition and dispatch):

<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendWelcomeEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userId;   // ID of the user to email

    // Constructor receives data needed for the job
    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    // The job logic runs here
    public function handle()
    {
        $user = \App\Models\User::find($this->userId);
        if ($user) {
            // Use Laravel's mailer to send the welcome email
            \Mail::to($user->email)->send(new \App\Mail\WelcomeMail($user));
        }
    }
}

// Dispatching the job from a controller or any other place
use App\Jobs\SendWelcomeEmail;

// After creating a new user...
$user = \App\Models\User::create($request->all());

// Push the job onto the Redis queue named "default"
SendWelcomeEmail::dispatch($user->id)
    ->onQueue('default');   // optional: specify a custom queue name

// To start a worker that processes jobs from Redis, run in terminal:
// php artisan queue:work redis --queue=default --sleep=3 --tries=3

// Ensure the queue connection is set to redis in config/queue.php:
// 'default' => env('QUEUE_CONNECTION', 'redis'),
// 'connections' => [
//     'redis' => [
//         'driver' => 'redis',
//         'connection' => 'default',
//         'queue' => env('REDIS_QUEUE', 'default'),
//         'retry_after' => 90,
//     ],
// ];
*/

/* MySQL
Topic: Common Table Expressions (CTE) and Recursive Queries

Explanation:
A Common Table Expression (CTE) is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement.  
CTEs improve readability by allowing you to break complex queries into logical building blocks.  
They are defined using the WITH clause and can be recursive, enabling hierarchical data traversal.  
Recursive CTEs consist of an anchor member (the base case) and a recursive member that references the CTE itself.  
MySQL 8.0+ fully supports both non‑recursive and recursive CTEs, making it easier to work with tree structures such as organization charts or category hierarchies.  

Code Example (with comments):
WITH RECURSIVE OrgChart AS (                              -- Define a recursive CTE named OrgChart
    SELECT 
        employee_id,                                    -- Base case: select the top‑level manager(s)
        manager_id,
        name,
        1 AS level                                      -- Level 1 for top manager
    FROM employees
    WHERE manager_id IS NULL                           -- No manager means top of hierarchy

    UNION ALL                                          -- Combine base case with recursive part
    SELECT 
        e.employee_id,
        e.manager_id,
        e.name,
        oc.level + 1 AS level                           -- Increment level for each deeper level
    FROM employees e
    JOIN OrgChart oc ON e.manager_id = oc.employee_id   -- Recursive join: find subordinates of current level
)
SELECT 
    employee_id,
    manager_id,
    name,
    level
FROM OrgChart
ORDER BY level, manager_id;                              -- Result shows the hierarchy ordered by level and manager  
*/

/* JavaScript
Topic: Closures in JavaScript

Explanation:  
A closure is a function that retains access to the variables of its outer (lexical) scope even after that outer function has finished executing.  
It allows you to create private state that cannot be accessed directly from the outside, enabling data encapsulation.  
The inner function forms a closure over any variables declared in the outer function, preserving their values across calls.  
Closures are created each time a function is defined, not only when it is invoked, so they are lightweight and ubiquitous.  
They are heavily used in callbacks, event handlers, and module patterns to maintain context.  
Mastering closures helps you write more modular, maintainable, and bug‑free JavaScript code.

Code example:  
function createCounter(initialValue) {          // outer function creates a private variable
    let count = initialValue;                  // this variable is captured by the inner function
    return function increment(step) {          // inner function forms a closure over 'count'
        count += step;                         // modifies the private 'count' variable
        console.log('Current count:', count); // can access and use 'count' each time it's called
    };
}

const counterA = createCounter(0);              // counterA has its own private 'count'
counterA(5);   // Output: Current count: 5
counterA(3);   // Output: Current count: 8

const counterB = createCounter(10);             // counterB has a separate 'count' variable
counterB(2);   // Output: Current count: 12
counterB(4);   // Output: Current count: 16

// Even though 'createCounter' has finished execution, the inner functions still
// have access to their respective 'count' variables because of closures.
*/

/* AI
Prompt Engineering for Few‑Shot Learning with the OpenAI API  
This technique embeds a handful of example input‑output pairs directly in the prompt, guiding the model to produce consistent results on new queries.  
By carefully selecting diverse yet representative examples, you can shape the model’s behavior without changing any model parameters.  
Few‑shot prompts are especially useful for tasks like data extraction, classification, or formatting where a full fine‑tuning process would be overkill.  
The approach works across GPT‑3.5, GPT‑4, and newer models, and it can be combined with system messages for additional instruction hierarchy.  
Performance can be measured by comparing the model’s output on a validation set against expected results, iterating on example selection to improve accuracy.  

import os
import openai

# Load your OpenAI API key from environment variable
openai.api_key = os.getenv("OPENAI_API_KEY")

# Define a few‑shot prompt: system message + two examples + new user query
prompt = [
    {"role": "system", "content": "You are an assistant that extracts the date and location from event announcements."},
    {"role": "user",   "content": "Join us for the Spring Festival on March 21, 2025 at Central Park."},
    {"role": "assistant", "content": "Date: March 21, 2025\nLocation: Central Park"},
    {"role": "user",   "content": "Don't miss the Tech Expo happening September 10th, 2024 in the Downtown Convention Center."},
    {"role": "assistant", "content": "Date: September 10, 2024\nLocation: Downtown Convention Center"},
    {"role": "user",   "content": "Upcoming webinar: AI Innovations on November 15, 2025, hosted online via Zoom."}
]

# Call the chat completion endpoint
response = openai.ChatCompletion.create(
    model="gpt-4o-mini",          # Choose an appropriate model
    messages=prompt,
    temperature=0.0               # Low temperature for deterministic extraction
)

# Print the model's extracted information
print(response["choices"][0]["message"]["content"])
*/

