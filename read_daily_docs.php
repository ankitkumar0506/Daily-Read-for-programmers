<?php
// 2026-10-03 06:52:22

/* PHP
PHP Generators  

Generators are a memory‑efficient way to create iterators without building an entire array in memory.  
They use the `yield` keyword to produce values one at a time, pausing the function’s execution between each.  
This makes them ideal for processing large data sets, streaming files, or any scenario where you need lazy evaluation.  
A generator returns an object that implements the Traversable interface, so it can be used in `foreach` loops.  
Because the state is preserved between yields, you can also send data back into the generator with `send()`.  

Example – reading a large CSV file line by line with a generator:  

function readCsv(string $filePath): Generator  
{  
    $handle = fopen($filePath, 'r');                // Open file for reading  
    if ($handle === false) {  
        throw new RuntimeException('Cannot open file');  
    }  
  
    while (($row = fgetcsv($handle)) !== false) {   // Read each CSV row  
        yield $row;                                 // Yield the row as an array  
    }  
  
    fclose($handle);                               // Close file when done  
}  
  
// Usage: iterate without loading the whole file into memory  
foreach (readCsv('big-data.csv') as $line) {  
    // Process each $line (an array of fields) here  
    echo implode(', ', $line) . PHP_EOL;  
}  
*/

/* Laravel
Topic: Laravel Queues with the Redis driver  

Explanation:  
Laravel queues allow time‑consuming tasks (email sending, image processing, etc.) to be processed asynchronously, keeping the web request fast. The Redis driver provides a fast, in‑memory queue backend that works well for both small and large scale applications. Jobs are defined as plain PHP classes that implement the ShouldQueue interface and placed on a named queue. The queue worker continuously polls Redis, pulls pending jobs, and runs their handle method. Using queues improves user experience and lets you retry failed jobs automatically.

Code example (Job class and dispatch)  

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

    protected $userId; // ID of the user to email  

    /**  
     * Create a new job instance.  
     */  
    public function __construct($userId)  
    {  
        $this->userId = $userId;  
    }  

    /**  
     * Execute the job.  
     */  
    public function handle()  
    {  
        $user = \App\Models\User::find($this->userId); // retrieve the user  
        if ($user) {  
            // Use Laravel's Mail facade to send the email  
            \Mail::to($user->email)->send(new \App\Mail\WelcomeMail($user));  
        }  
    }  

    /**  
     * The number of times the job may be attempted.  
     */  
    public $tries = 3;  

    /**  
     * Seconds to wait before retrying a failed job.  
     */  
    public $backoff = 60;  
}  

// Dispatching the job from a controller or service  
use App\Jobs\SendWelcomeEmail;  

public function register(Request $request)  
{  
    $user = \App\Models\User::create($request->all()); // create the user  

    // Push the email job onto the "emails" queue using Redis driver  
    SendWelcomeEmail::dispatch($user->id)->onQueue('emails');  

    return response()->json(['message' => 'User registered, welcome email queued.']);  
}  

// To start processing jobs, run the queue worker with the Redis connection:  
// php artisan queue:work redis --queue=emails --tries=3   (run this in a terminal)  
*/

/* MySQL
Topic: Recursive Common Table Expressions (CTEs) in MySQL

Explanation:
A recursive CTE lets you query hierarchical or tree‑structured data without writing procedural code. It consists of an anchor query that returns the first level of rows and a recursive member that references the CTE itself to produce subsequent levels. MySQL evaluates the CTE repeatedly, appending each new result set until the recursive member returns no rows. This technique is useful for organization charts, bill‑of‑materials, or any parent‑child relationship. You must include the MAXRECURSION option (or rely on the default limit of 1000) to prevent infinite loops.

Code example (comments start with --):

-- Create a sample table representing an employee hierarchy
CREATE TABLE employees (
    emp_id INT PRIMARY KEY,
    emp_name VARCHAR(50),
    manager_id INT NULL   -- NULL means top‑level manager
);

-- Insert sample data
INSERT INTO employees VALUES
(1, 'Alice', NULL),   -- CEO
(2, 'Bob', 1),        -- reports to Alice
(3, 'Carol', 1),      -- reports to Alice
(4, 'Dave', 2),       -- reports to Bob
(5, 'Eve', 2),        -- reports to Bob
(6, 'Frank', 3);      -- reports to Carol

-- Recursive CTE to list all subordinates of a given manager (e.g., manager_id = 1)
WITH RECURSIVE subordinates AS (
    -- Anchor member: start with the direct reports of the chosen manager
    SELECT emp_id, emp_name, manager_id, 1 AS level
    FROM employees
    WHERE manager_id = 1
    UNION ALL
    -- Recursive member: find employees whose manager is in the previous level
    SELECT e.emp_id, e.emp_name, e.manager_id, s.level + 1
    FROM employees e
    INNER JOIN subordinates s ON e.manager_id = s.emp_id
)
SELECT emp_id, emp_name, manager_id, level
FROM subordinates
ORDER BY level, emp_id;

-- The result shows Bob, Carol at level 1, then Dave, Eve, Frank at level 2.
*/

/* JavaScript
Topic: Closures in JavaScript  

Explanation:  
A closure is a function that retains access to its lexical scope even when the outer function has finished executing.  
It allows private variables to be encapsulated, enabling data hiding and stateful functions without exposing internal details.  
Closures are created each time a function is defined, capturing the surrounding variables at that moment.  
They are essential for patterns such as factories, memoization, and event handlers.  
Understanding closures helps avoid common pitfalls like unintentionally sharing mutable state across calls.  

Code example:  
function makeCounter() {  
    let count = 0; // private variable captured by the inner function  
    return function() {  
        count++;            // modifies the closed‑over variable  
        return count;       // returns the updated value  
    };  
}  

const counter = makeCounter(); // each call to makeCounter gets its own closure  

console.log(counter()); // 1  
console.log(counter()); // 2  
console.log(counter()); // 3  
*/

/* AI
Topic: Few‑Shot Prompt Engineering for Large Language Models

Explanation:  
Few‑shot prompting supplies a language model with a handful of example inputs and desired outputs directly in the prompt, guiding the model to infer the intended pattern. This technique is lightweight—no fine‑tuning required—and works well for tasks like text classification, transformation, or data extraction. By carefully selecting diverse yet representative examples, you can improve consistency and reduce hallucinations. The prompt structure typically includes a brief instruction, several labeled examples, and a placeholder for the new query. Adjusting the number of shots, the wording of instructions, and the formatting of examples can dramatically affect performance.

Code example (Python, using OpenAI’s ChatCompletion API):

import os
import json
import openai

# Load your API key from an environment variable
openai.api_key = os.getenv("OPENAI_API_KEY")

def classify_sentiment(text):
    # Build a few‑shot prompt with instruction and examples
    prompt = [
        {"role": "system", "content": "You are a helpful assistant that classifies sentiment as Positive, Negative, or Neutral."},
        {"role": "user",   "content": "Review: I love this product! It works perfectly.\nSentiment:"},
        {"role": "assistant","content": "Positive"},
        {"role": "user",   "content": "Review: The item arrived late and was damaged.\nSentiment:"},
        {"role": "assistant","content": "Negative"},
        {"role": "user",   "content": "Review: The packaging is okay, nothing special.\nSentiment:"},
        {"role": "assistant","content": "Neutral"},
        {"role": "user",   "content": f"Review: {text}\nSentiment:"}
    ]

    # Call the ChatCompletion endpoint
    response = openai.ChatCompletion.create(
        model="gpt-4o-mini",      # choose a suitable model
        messages=prompt,
        temperature=0.0           # deterministic output for classification
    )

    # Extract and return the model's answer
    sentiment = response.choices[0].message.content.strip()
    return sentiment

# Example usage
sample = "The battery life is decent, but the screen is dull."
print("Sentiment:", classify_sentiment(sample))
*/

