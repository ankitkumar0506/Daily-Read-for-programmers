<?php
// 2026-10-08 07:46:34

/* PHP
Topic: PDO Prepared Statements for Secure Database Access  

Explanation:  
1. PDO (PHP Data Objects) provides a uniform interface for accessing different databases.  
2. Prepared statements separate SQL logic from data, preventing SQL injection attacks.  
3. They allow the database engine to parse the query once and execute it multiple times with different parameters.  
4. Binding parameters can be done by name or by position, improving readability and maintainability.  
5. Error handling with PDO can be configured to throw exceptions, making debugging easier.  

Code Example (MySQL connection, insert with named parameters):  

<?php
// Enable exceptions for PDO errors
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];

// Create a new PDO instance (replace placeholders with actual credentials)
$pdo = new PDO('mysql:host=localhost;dbname=sample_db;charset=utf8mb4', 'db_user', 'db_pass', $options);

// Define an INSERT query with named placeholders
$sql = "INSERT INTO users (username, email, created_at) VALUES (:username, :email, NOW())";

// Prepare the statement once
$stmt = $pdo->prepare($sql);

// Sample data to insert
$data = [
    ':username' => 'johndoe',
    ':email'    => 'john.doe@example.com'
];

// Execute the prepared statement with bound values
$stmt->execute($data);

// Retrieve the ID of the newly inserted row
$newUserId = $pdo->lastInsertId();

echo "New user inserted with ID: " . $newUserId;
?>
*/

/* Laravel
Laravel Queues with Redis  

The queue system allows time‑consuming tasks to be processed in the background, keeping web requests fast. Laravel supports many drivers; Redis provides an in‑memory, high‑performance backend ideal for real‑time applications. Jobs are simple PHP classes that implement the ShouldQueue interface and are pushed onto the Redis queue with the dispatch helper. Workers listen to the queue, retrieve jobs, and execute their handle method. By configuring retry attempts and timeout values, you can build robust, fault‑tolerant background processing.  

// app/Jobs/SendWelcomeEmail.php  
<?php  

namespace App\Jobs;  

use Illuminate\Bus\Queueable;  
use Illuminate\Contracts\Queue\ShouldQueue;  
use Illuminate\Foundation\Bus\Dispatchable;  
use Illuminate\Queue\InteractsWithQueue;  
use Illuminate\Queue\SerializesModels;  
use App\Mail\WelcomeMail;  
use Mail;  

class SendWelcomeEmail implements ShouldQueue  
{  
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;  

    protected $user; // The user instance to receive the email  

    /**  
     * Create a new job instance.  
     * @param  \App\Models\User  $user  
     */  
    public function __construct($user)  
    {  
        $this->user = $user;  
    }  

    /**  
     * Execute the job.  
     */  
    public function handle()  
    {  
        // Send the welcome email using a mailable class  
        Mail::to($this->user->email)->send(new WelcomeMail($this->user));  
    }  

    /**  
     * The number of times the job may be attempted.  
     */  
    public function retryUntil()  
    {  
        return now()->addMinutes(10); // give up after 10 minutes  
    }  
}  

// Dispatching the job from a controller or event  
use App\Jobs\SendWelcomeEmail;  

// $user is an instance of App\Models\User  
SendWelcomeEmail::dispatch($user)->onQueue('emails'); // place job on the 'emails' queue  

// Terminal command to start a worker listening to the Redis queue  
php artisan queue:work redis --queue=emails --tries=3  

// .env configuration for Redis queue driver  
QUEUE_CONNECTION=redis  
REDIS_HOST=127.0.0.1  
REDIS_PASSWORD=null  
REDIS_PORT=6379  
*/

/* MySQL
Topic: Common Table Expressions (CTEs) and Recursive Queries

Explanation:
A Common Table Expression (CTE) is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement. CTEs are defined using the WITH clause and improve readability by allowing you to break complex queries into logical building blocks. Recursive CTEs enable you to query hierarchical data such as organizational charts or folder structures by repeatedly referencing the CTE itself. They are processed before the main query, so you can use the CTE multiple times within the same statement. CTEs are scoped to the statement in which they appear and do not persist beyond it.

Code example (MySQL 8.0+):
WITH RECURSIVE org_chart AS ( 
    -- Anchor member: start with the top‑level manager (id = 1) 
    SELECT employee_id, manager_id, employee_name, 1 AS level 
    FROM employees 
    WHERE employee_id = 1 
    UNION ALL 
    -- Recursive member: find employees whose manager is in the previous level 
    SELECT e.employee_id, e.manager_id, e.employee_name, oc.level + 1 
    FROM employees e 
    INNER JOIN org_chart oc ON e.manager_id = oc.employee_id 
) 
SELECT employee_id, manager_id, employee_name, level 
FROM org_chart 
ORDER BY level, manager_id; 

-- The query returns the entire hierarchy starting from the CEO (id = 1), 
-- showing each employee's level in the organization.
*/

/* JavaScript
Topic: JavaScript Closures

Explanation:  
A closure is a function that retains access to the variables of its outer (enclosing) scope even after that outer function has finished executing. This happens because the inner function forms a lexical environment that captures the surrounding variables. Closures are useful for data privacy, creating function factories, and maintaining state between calls without using global variables. They rely on JavaScript’s function scope and the fact that functions are first‑class objects. Understanding closures helps avoid common pitfalls with asynchronous code and loops.

Code example with comments:  
function makeCounter() {                     // outer function creates a private variable
    let count = 0;                           // this variable is captured by the inner function
    return function() {                     // the inner function forms a closure
        count += 1;                          // it can read and modify count each call
        console.log('Current count:', count);
    };
}

const counterA = makeCounter();              // each call to makeCounter gets its own closure
const counterB = makeCounter();

counterA();   // Current count: 1
counterA();   // Current count: 2
counterB();   // Current count: 1   (separate closure, independent state)
*/

/* AI
Topic: Few‑Shot Prompt Engineering with OpenAI’s Chat Completion API  

Explanation:  
Few‑shot prompting supplies a small set of example input‑output pairs inside the prompt so the model can infer the desired pattern without fine‑tuning. By framing the task as a conversation, you can guide the model to behave like a specific tool or role (e.g., a code reviewer). The examples act as “in‑context” training data, which the model uses to predict the next response. This technique works well for classification, transformation, or generation tasks where a full dataset is unavailable. Adjust the temperature and max_tokens to balance creativity and precision.

Code example (Python, using the openai library):

import os
import openai

# Load your API key from an environment variable or directly assign it
openai.api_key = os.getenv("OPENAI_API_KEY")

# Define a few‑shot prompt that shows how to convert a list of numbers into a sorted, unique list
system_message = {"role": "system", "content": "You are a helpful assistant that formats lists of integers."}
example_user_1 = {"role": "user", "content": "Input: [3, 1, 2, 3]\nOutput:"}
example_assistant_1 = {"role": "assistant", "content": "[1, 2, 3]"}
example_user_2 = {"role": "user", "content": "Input: [10, 5, 5, 8]\nOutput:"}
example_assistant_2 = {"role": "assistant", "content": "[5, 8, 10]"}

# New query we want the model to answer
new_query = {"role": "user", "content": "Input: [7, 2, 7, 4]\nOutput:"}

# Assemble the message list in the order: system, examples, new query
messages = [
    system_message,
    example_user_1, example_assistant_1,
    example_user_2, example_assistant_2,
    new_query
]

response = openai.ChatCompletion.create(
    model="gpt-4o-mini",      # Choose a model that supports chat completions
    messages=messages,
    temperature=0.0,          # Deterministic output for exact formatting
    max_tokens=20             # Small limit because the answer is short
)

# Print the model’s answer (the formatted list)
print(response.choices[0].message.content.strip())   # Expected output: [2, 4, 7]
*/

