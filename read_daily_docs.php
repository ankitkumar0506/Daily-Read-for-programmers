<?php
// 2026-09-19 06:18:19

/* PHP
PHP Topic: Generators (yield)

Explanation:  
Generators allow you to create iterators without storing the entire dataset in memory.  
When a function contains the `yield` keyword, it returns a Generator object that produces values on demand.  
Each `yield` pauses the function’s execution, preserving its state for the next iteration.  
This makes handling large data streams, such as reading big files or database rows, efficient and memory‑friendly.  
Generators can also receive values sent back into the function using `$generator->send($value)`.

Code example with comments:  

<?php
function getNumbers($max) {
    for ($i = 1; $i <= $max; $i++) {
        // yield returns the current number and pauses execution
        yield $i;
    }
}

// Create the generator
$numbers = getNumbers(5);

// Iterate over the generated values
foreach ($numbers as $num) {
    // Output each number followed by a newline
    echo $num . PHP_EOL;
}
?>
*/

/* Laravel
Laravel Queues and Jobs  
Explanation:  
Laravel queues allow time‑consuming tasks to be processed in the background, keeping web requests fast.  
You define a Job class that contains the logic to be executed later.  
Jobs are dispatched to a queue connection (database, Redis, etc.) and processed by a queue worker.  
Failed jobs are automatically logged, and you can retry them after fixing the issue.  
Using queues improves scalability and user experience for tasks like sending emails, image processing, or API calls.  

Code Example (Job class and dispatch)  

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
     *  
     * @param  \App\Models\User  $user  
     * @return void  
     */  
    public function __construct($user)  
    {  
        $this->user = $user;  
    }  

    /**  
     * Execute the job.  
     *  
     * @return void  
     */  
    public function handle()  
    {  
        // Send the welcome email using Laravel's Mail facade  
        Mail::to($this->user->email)->send(new WelcomeMail($this->user));  
    }  
}  

// Dispatching the job from a controller or any other place  
use App\Jobs\SendWelcomeEmail;  

public function register(Request $request)  
{  
    $user = User::create($request->only(['name', 'email', 'password']));  

    // Push the email sending to the queue instead of sending instantly  
    SendWelcomeEmail::dispatch($user);  

    return response()->json(['message' => 'User registered, email will be sent shortly.']);  
}  

// To start processing jobs, run the worker in the terminal  
// php artisan queue:work --queue=default  

// Queue configuration (config/queue.php) – example for database driver  
/*
'connections' => [
    'database' => [
        'driver' => 'database',
        'table' => 'jobs',
        'queue' => 'default',
        'retry_after' => 90,
    ],
],
*/  
*/

/* MySQL
Topic: Common Table Expressions (CTE) and Recursive Queries

Explanation:  
A Common Table Expression (CTE) is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement. It is defined using the WITH clause and can improve query readability, especially for complex subqueries. MySQL 8.0 introduced support for both non‑recursive and recursive CTEs. A recursive CTE allows you to perform hierarchical or tree‑like queries, such as traversing organizational charts or folder structures, by repeatedly applying a query to its own output until a termination condition is met. CTEs are scoped to the statement they belong to and do not persist beyond it.

Code example (with comments):
WITH RECURSIVE OrgChart AS (               -- define a recursive CTE named OrgChart
    SELECT employee_id, manager_id, 1 AS level
    FROM employees
    WHERE manager_id IS NULL               -- base case: top‑level manager(s)

    UNION ALL
    SELECT e.employee_id, e.manager_id, oc.level + 1
    FROM employees e
    INNER JOIN OrgChart oc
        ON e.manager_id = oc.employee_id   -- recursive step: find direct reports
)
SELECT employee_id, manager_id, level
FROM OrgChart
ORDER BY level, employee_id;                -- final result shows hierarchy with depth levels  
*/

/* JavaScript
Topic: Closures in JavaScript

Explanation:  
A closure is a function that retains access to the variables of its outer (enclosing) function even after that outer function has finished executing.  
Closures allow private state to be maintained without exposing it directly to the global scope.  
They are created automatically whenever an inner function references a variable from an outer function.  
Because the inner function holds a reference to the outer variables, those variables are not garbage‑collected until the closure itself is no longer reachable.  
Closures are widely used for data encapsulation, event handlers, and implementing function factories.

Code example:
// A function that creates a counter with private state
function createCounter(initialValue) {
    let count = initialValue;           // this variable is captured by the closure

    // The inner function forms a closure over 'count'
    return function () {
        count += 1;                     // can modify the private variable
        return count;                  // returns the updated value
    };
}

// Using the closure
const counterA = createCounter(0);
console.log(counterA()); // 1
console.log(counterA()); // 2

const counterB = createCounter(10);
console.log(counterB()); // 11
console.log(counterA()); // 3   // counterA maintains its own separate state

// The variables 'count' inside each closure are private and cannot be accessed directly
// Attempting to read 'count' here would result in a ReferenceError.
*/

/* AI
Topic: Few‑Shot Prompt Engineering with OpenAI’s ChatCompletion API  

Explanation:  
Few‑shot prompting supplies the model with a short set of example interactions that illustrate the desired behavior, allowing the same model to perform a new task without fine‑tuning. By placing the examples directly in the user message, you guide the model’s reasoning pattern and output format. This technique works well for classification, transformation, or Q&A tasks where a clear template can be demonstrated. The approach is lightweight, requires only API calls, and can be adapted on the fly for different domains. Careful choice of examples and concise instructions often yields the biggest quality gains.

Code example (Python, using the official openai library):  

import os  
import openai  

# Load your OpenAI API key from an environment variable  
openai.api_key = os.getenv("OPENAI_API_KEY")  

# Define a few‑shot prompt that shows the desired input‑output mapping  
few_shot_prompt = """You are a helpful assistant that converts plain‑English dates into ISO‑8601 format (YYYY‑MM‑DD).

Example 1:  
User: "The project started on March 5th, 2022."  
Assistant: "2022-03-05"

Example 2:  
User: "Our deadline is next Friday."  
Assistant: "2023-04-28"  # assuming today is 2023‑04‑22

Now convert the following date:  

User: "The meeting will be held on the 2nd of November, 2024."  
Assistant:"""  

# Call the ChatCompletion endpoint with the constructed prompt  
response = openai.ChatCompletion.create(  
    model="gpt-4o-mini",  
    messages=[{"role": "user", "content": few_shot_prompt}],  
    temperature=0.0,          # deterministic output for date formatting  
    max_tokens=20            # limit to a short ISO string  
)  

# Extract and print the assistant’s reply  
iso_date = response.choices[0].message.content.strip()  
print("ISO‑8601 date:", iso_date)   # Expected output: 2024-11-02  
*/

