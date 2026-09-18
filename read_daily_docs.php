<?php
// 2026-09-18 06:20:05

/* PHP
Topic: Prepared Statements with PDO (PHP Data Objects)

Explanation:
Prepared statements separate SQL code from the data that will be supplied at execution time, preventing SQL injection attacks. PDO provides a uniform interface for many database systems, making the code portable across MySQL, PostgreSQL, SQLite, etc. When a statement is prepared, the database parses and compiles it once, then can be executed multiple times with different parameter values efficiently. Binding parameters lets you specify data types, which helps with proper quoting and performance. Using try‑catch blocks around PDO operations ensures that errors are caught and handled gracefully.

Code example (with comments):
<?php
// Enable exceptions for error handling
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    // Connect to the database (replace DSN, username, password as needed)
    $pdo = new PDO('mysql:host=localhost;dbname=example_db;charset=utf8mb4', 'db_user', 'db_pass', $options);

    // Prepare an INSERT statement with named placeholders
    $stmt = $pdo->prepare(
        'INSERT INTO users (username, email, created_at) VALUES (:username, :email, NOW())'
    );

    // Bind values to the placeholders (optional: specify data type)
    $stmt->bindValue(':username', $username, PDO::PARAM_STR);
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);

    // Example data
    $username = 'alice';
    $email    = 'alice@example.com';

    // Execute the prepared statement
    $stmt->execute();

    // Get the ID of the newly inserted row
    $newUserId = $pdo->lastInsertId();

    echo "User created with ID: $newUserId\n";
} catch (PDOException $e) {
    // Handle any database errors
    echo 'Database error: ' . $e->getMessage();
}
?>
*/

/* Laravel
Topic: Laravel Queues and Background Jobs

Explanation:
Laravel queues allow you to defer time‑consuming tasks such as sending emails, processing files, or making API calls to a background process. By pushing jobs onto a queue, the main request can respond quickly while the heavy work is handled asynchronously. Laravel supports several queue drivers (database, Redis, SQS, etc.) and provides a unified API to define, dispatch, and process jobs. Workers listen to the queue and execute jobs one by one, optionally retrying failed attempts. This improves application performance, scalability, and user experience.

Code Example (a simple email sending job using the database driver):

// app/Jobs/SendWelcomeEmail.php
<?php

namespace App\Jobs;

use App\Mail\WelcomeMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;

class SendWelcomeEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user; // The user instance to whom the email will be sent

    // Constructor receives the user object when the job is dispatched
    public function __construct($user)
    {
        $this->user = $user;
    }

    // This method is called by the queue worker
    public function handle()
    {
        // Build and send the welcome email
        Mail::to($this->user->email)->send(new WelcomeMail($this->user));
    }
}

// Dispatching the job from a controller after user registration
// app/Http/Controllers/Auth/RegisterController.php
public function register(Request $request)
{
    // Validation and user creation logic...
    $user = User::create($request->only(['name', 'email', 'password']));

    // Push the email job onto the default queue
    SendWelcomeEmail::dispatch($user);

    return redirect()->route('home')->with('status', 'Registration complete! Check your email.');
}

// Running the queue worker (from the terminal)
php artisan queue:work --tries=3

// Queue configuration (config/queue.php)
// Ensure the default driver is set to 'database' and run the migration:
// php artisan queue:table
// php artisan migrate

// After setting up, the job will be stored in the 'jobs' table and processed
// by the worker without blocking the user's registration request.
*/

/* MySQL
Topic: Common Table Expressions (CTE) and Recursive Queries

Explanation:
A Common Table Expression (CTE) is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement.  
CTEs improve readability by allowing you to define subqueries at the top of the statement, rather than nesting them deep inside.  
MySQL supports both non‑recursive and recursive CTEs, the latter enabling hierarchical data traversal such as organization charts or folder trees.  
Recursive CTEs consist of an anchor member (the base case) and a recursive member that repeatedly references the CTE itself until a termination condition is met.  
Using CTEs can also help the optimizer generate more efficient execution plans compared with equivalent derived tables.

Code example (recursive CTE that lists an employee hierarchy):
-- Define the recursive CTE named emp_hierarchy
WITH RECURSIVE emp_hierarchy AS (
    -- Anchor member: start with the top‑level manager (manager_id IS NULL)
    SELECT
        employee_id,
        employee_name,
        manager_id,
        1 AS level
    FROM employees
    WHERE manager_id IS NULL

    UNION ALL

    -- Recursive member: join children to their parents
    SELECT
        e.employee_id,
        e.employee_name,
        e.manager_id,
        eh.level + 1 AS level
    FROM employees e
    INNER JOIN emp_hierarchy eh ON e.manager_id = eh.employee_id
)
-- Query the CTE to get the full hierarchy ordered by level
SELECT
    employee_id,
    employee_name,
    manager_id,
    level
FROM emp_hierarchy
ORDER BY level, manager_id;
*/

/* JavaScript
Topic: Closures in JavaScript

Explanation:
A closure is a function that retains access to its lexical environment even after the outer function has finished executing. It allows inner functions to reference variables defined in the outer scope, preserving their values across calls. Closures are created each time a function is declared, and they are essential for data privacy, partial application, and function factories. Understanding closures helps avoid common pitfalls like unintended variable sharing in loops. They are a core concept for writing modular and maintainable JavaScript code.

Code example with comments:
function makeCounter(initialValue) {               // outer function that creates a counter
    let count = initialValue;                      // private variable, not directly accessible outside
    return function() {                           // inner function forms a closure over 'count'
        count += 1;                                // modifies the private variable
        return count;                              // returns the updated count
    };
}

const counterA = makeCounter(0);                    // create a new counter starting at 0
console.log(counterA()); // 1                        // first call increments to 1
console.log(counterA()); // 2                        // second call increments to 2

const counterB = makeCounter(10);                   // independent counter starting at 10
console.log(counterB()); // 11                       // operates on its own 'count' variable
console.log(counterA()); // 3                        // counterA retains its own state, now 3

// The inner functions keep their own lexical environment, demonstrating closures.
*/

/* AI
Topic: Few‑Shot Prompt Engineering with OpenAI’s Chat Completion API  

Explanation:  
Few‑shot prompting supplies the model with a small number of example interactions inside the prompt, teaching it the desired input‑output pattern without fine‑tuning. This technique works well for tasks such as text classification, data extraction, or transformation where the model must follow a specific format. By placing the examples in the system or user messages, you guide the model’s reasoning and keep token usage low. Adjust the number and style of examples to balance performance and cost. The approach is language‑agnostic and can be applied through any API that accepts a chat‑style message list.

Code example (Python, using the openai library):

import os
import openai

# Load your API key from an environment variable or replace with a string
openai.api_key = os.getenv("OPENAI_API_KEY")

# Define a few‑shot prompt that teaches the model how to extract dates from sentences
messages = [
    {"role": "system", "content": "You are a helpful assistant that extracts dates from user sentences and returns them in ISO‑8601 format. If no date is present, reply with 'None'."},
    {"role": "user", "content": "I went to the conference on March 3rd, 2023."},
    {"role": "assistant", "content": "2023-03-03"},
    {"role": "user", "content": "Our next meeting is scheduled for next Friday."},
    {"role": "assistant", "content": "2023-09-27"},  # assume today is 2023‑09‑20
    {"role": "user", "content": "Please book a flight."},
    {"role": "assistant", "content": "None"},
    # New query we want the model to answer using the same pattern
    {"role": "user", "content": "The deadline is 15th of August, 2024."}
]

response = openai.ChatCompletion.create(
    model="gpt-4o-mini",          # choose a suitable model
    messages=messages,
    temperature=0.0               # deterministic output for extraction tasks
)

# Print the model's answer, which should be the extracted date
print(response["choices"][0]["message"]["content"].strip())
*/

