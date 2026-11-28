<?php
// 2026-11-28 09:10:53

/* PHP
PHP Generators

Generators allow a function to return values one at a time, pausing its execution between each yield. This is useful for handling large data sets without loading everything into memory. They are implemented with the `yield` keyword, turning a regular function into an iterator. Generators improve performance and reduce memory consumption, especially in loops that process files, database rows, or API streams. They can also be used to create infinite sequences safely.

<?php
// Example: a generator that yields the first n Fibonacci numbers
function fibonacciGenerator(int $limit): Generator
{
    $a = 0;          // first number
    $b = 1;          // second number
    $count = 0;
    while ($count < $limit) {
        yield $a;    // pause and return the current value
        $next = $a + $b;
        $a = $b;
        $b = $next;
        $count++;
    }
}

// Use the generator in a foreach loop
foreach (fibonacciGenerator(10) as $index => $value) {
    echo "Fibonacci #{$index}: {$value}\n";
}
?>
*/

/* Laravel
Topic: Laravel Queues and Jobs

Explanation:  
Laravel queues allow you to defer time‑consuming tasks such as sending emails, processing images, or calling external APIs to a background process. By pushing jobs onto a queue, the main request can respond quickly while workers handle the heavy lifting asynchronously. Laravel provides a unified API for multiple queue back‑ends like database, Redis, Amazon SQS, and others. Jobs are simple PHP classes that encapsulate the logic to be performed, and they can be retried automatically if they fail. Workers are started with an Artisan command and listen for new jobs, executing them in the order they are received.

Code example (Job class and dispatch):

<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\WelcomeMail;
use Mail;

/* This job sends a welcome email to a newly registered user. */
class SendWelcomeEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;   // The user instance that will receive the email

    // Constructor receives the user object when the job is dispatched
    public function __construct($user)
    {
        $this->user = $user;
    }

    // The handle method contains the logic that will run in the background worker
    public function handle()
    {
        // Build and send the email; this runs outside the HTTP request cycle
        Mail::to($this->user->email)->send(new WelcomeMail($this->user));
    }
}

/* Dispatching the job from a controller or service */
use App\Jobs\SendWelcomeEmail;

public function register(Request $request)
{
    // Validation and user creation logic...
    $user = User::create($request->only(['name', 'email', 'password']));

    // Push the email sending job onto the default queue
    SendWelcomeEmail::dispatch($user);

    return response()->json(['message' => 'User registered, welcome email queued.']);
}

/* Starting a worker (run this in the terminal) */
# php artisan queue:work --tries=3   // The worker will process jobs and retry up to 3 times on failure.
*/

/* MySQL
Topic: Common Table Expressions (CTEs) and Recursive Queries

Explanation:  
A Common Table Expression (CTE) is a temporary named result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement. CTEs improve readability by allowing you to break complex queries into logical building blocks. They are defined using the WITH clause and exist only for the duration of the statement. When a CTE references itself, it becomes a recursive CTE, which is useful for hierarchical data such as organizational charts or tree structures. Recursive CTEs consist of an anchor member (the base case) and a recursive member that repeatedly references the CTE until a termination condition is met.

Code example with comments:

-- Define a recursive CTE to list an employee hierarchy
WITH RECURSIVE EmployeeHierarchy AS (
    -- Anchor member: start with the top‑level manager (e.g., employee_id = 1)
    SELECT 
        employee_id,
        manager_id,
        employee_name,
        1 AS level
    FROM employees
    WHERE employee_id = 1

    UNION ALL

    -- Recursive member: join each employee to their direct reports
    SELECT 
        e.employee_id,
        e.manager_id,
        e.employee_name,
        eh.level + 1 AS level
    FROM employees e
    INNER JOIN EmployeeHierarchy eh
        ON e.manager_id = eh.employee_id
)
-- Final query: retrieve the full hierarchy ordered by level
SELECT 
    employee_id,
    manager_id,
    employee_name,
    level
FROM EmployeeHierarchy
ORDER BY level, employee_id;
*/

/* JavaScript
Topic: Event Loop, Call Stack, and Microtasks  

Explanation:  
- JavaScript runs on a single thread, processing code line‑by‑line using the call stack.  
- The event loop continuously checks the call stack; when it’s empty, it pulls tasks from the macro‑task queue (e.g., setTimeout, I/O callbacks).  
- Promises create microtasks, which are placed in a separate microtask queue that is processed **after** each macro‑task but **before** the next macro‑task.  
- This ordering guarantees that promise callbacks run before timers scheduled for the same tick, leading to subtle timing differences.  
- Understanding this model helps avoid bugs related to asynchronous code execution and enables you to write more predictable JavaScript.  

Code example (with comments):  

function log(value) {  
    console.log(value);  
}  

log('Start');                     // 1) Synchronous, runs immediately  

setTimeout(() => {                // Macro‑task, runs after current stack & microtasks  
    log('Timeout');               // Executes later  
}, 0);  

Promise.resolve()                  // Creates a microtask  
    .then(() => {                 // 2) Microtask, runs after current stack, before setTimeout  
        log('Promise then 1');  
    })  
    .then(() => {                 // 3) Another microtask, runs after previous then  
        log('Promise then 2');  
    });  

log('End');                       // 4) Synchronous, runs before any async callbacks  
*/

/* AI
Topic: Few‑Shot Prompt Engineering with OpenAI’s Chat Completion API  

Explanation:  
Few‑shot prompting provides the model with a short set of example interactions that illustrate the desired behavior, allowing you to shape outputs without fine‑tuning. By inserting a handful of input‑output pairs into the system message or the beginning of the conversation, you give the model a clear pattern to follow. This technique works well for tasks such as formatting JSON, extracting entities, or converting natural language to code. The examples should be concise, representative, and cover edge cases you expect the model to handle. Adjust the temperature to a low value (e.g., 0.2) to encourage deterministic, pattern‑matching responses.

Code example (Python, using the OpenAI library):

import os
import openai

# Load your API key from an environment variable
openai.api_key = os.getenv("OPENAI_API_KEY")

# Define a few‑shot prompt: system message + two example user‑assistant pairs
messages = [
    {"role": "system", "content": "You are a helpful assistant that converts natural‑language descriptions of simple arithmetic problems into JSON with fields 'operand1', 'operand2', and 'operator'."},
    {"role": "user", "content": "What is the sum of 7 and 12?"},
    {"role": "assistant", "content": "{\"operand1\": 7, \"operand2\": 12, \"operator\": \"+\"}"},
    {"role": "user", "content": "Multiply 5 by 9."},
    {"role": "assistant", "content": "{\"operand1\": 5, \"operand2\": 9, \"operator\": \"*\"}"},
    # New query we want the model to answer in the same format
    {"role": "user", "content": "Subtract 20 from 45."}
]

response = openai.ChatCompletion.create(
    model="gpt-4o-mini",          # choose an appropriate model
    messages=messages,
    temperature=0.2,              # low temperature for deterministic output
    max_tokens=100
)

# Print the model's JSON answer
print(response["choices"][0]["message"]["content"])
*/

