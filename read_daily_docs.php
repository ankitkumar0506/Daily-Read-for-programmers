<?php
// 2026-11-26 09:50:03

/* PHP
PHP Topic: Traits

Explanation:
Traits are a mechanism for code reuse in single inheritance languages like PHP.  
They allow you to compose classes from reusable sets of methods without using inheritance.  
A trait can contain methods and properties, and a class can use multiple traits.  
If a class uses two traits that have methods with the same name, you must resolve the conflict.  
Traits are useful for sharing common functionality across unrelated class hierarchies.

Code Example (with comments):

<?php
// Define a trait that provides logging functionality
trait Logger {
    // Log a message with a timestamp
    public function log(string $message): void {
        echo "[" . date('Y-m-d H:i:s') . "] " . $message . PHP_EOL;
    }
}

// Another trait that provides a unique identifier
trait HasId {
    private int $id;

    // Set the identifier
    public function setId(int $id): void {
        $this->id = $id;
    }

    // Get the identifier
    public function getId(): int {
        return $this->id;
    }
}

// A class that uses both traits
class User {
    use Logger, HasId; // Resolve any method name conflicts here if needed

    private string $name;

    public function __construct(string $name, int $id) {
        $this->name = $name;
        $this->setId($id); // Method from HasId trait
        $this->log("User created: {$this->name} (ID: {$this->getId()})"); // Method from Logger trait
    }
}

// Usage
$user = new User('Alice', 101);
$user->log('This is a custom log message.');
?>
*/

/* Laravel
Topic: Laravel Queues and Jobs  

Explanation:  
Laravel queues allow you to defer time‑consuming tasks such as sending emails, processing images, or calling external APIs so they run in the background. By pushing a job onto a queue, the main request can return quickly, improving user experience and reducing request latency. Laravel provides a unified API for multiple queue drivers (database, Redis, SQS, etc.), making it easy to switch back‑ends without changing your job code. Each job is a self‑contained class that receives its data via the constructor and defines a handle method where the task logic lives. Workers listen to the queue and process jobs one at a time, handling retries and failures automatically.

Code example with comments:

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

    // Data that will be serialized and passed to the job when it runs
    protected $user;

    // Constructor receives the user instance when the job is dispatched
    public function __construct($user)
    {
        $this->user = $user;
    }

    // The logic that will be executed by the queue worker
    public function handle()
    {
        // Build the email and send it using the Mail facade
        Mail::to($this->user->email)->send(new WelcomeMail($this->user));
    }
}

// Dispatching the job from a controller or service
// This line pushes the job onto the default queue connection
SendWelcomeEmail::dispatch($user);

?>
*/

/* MySQL
Topic: MySQL Stored Procedures with IN, OUT, and INOUT Parameters  

Explanation:  
A stored procedure is a reusable set of SQL statements that runs on the MySQL server.  
It can accept input values (IN), return output values (OUT), or both (INOUT).  
Using parameters makes the procedure flexible and allows you to encapsulate business logic.  
Procedures are compiled once, so subsequent calls are faster than sending raw SQL each time.  
They also help enforce security by limiting direct table access for the calling user.  

Code Example (with comments):  

DELIMITER $$  
CREATE PROCEDURE GetEmployeeSalary(  
    IN emp_id INT,            -- input: employee identifier  
    OUT emp_name VARCHAR(50),-- output: employee name returned to caller  
    INOUT total_bonus DECIMAL(10,2) -- input-output: initial bonus, modified inside procedure  
)  
BEGIN  
    -- Retrieve employee name and base salary  
    SELECT name, salary INTO emp_name, total_bonus  
    FROM employees  
    WHERE id = emp_id;  

    -- Add a performance bonus of 10% to the total_bonus (which currently holds base salary)  
    SET total_bonus = total_bonus * 1.10;  
END $$  
DELIMITER ;  

-- Example of calling the procedure  
SET @bonus = 0;  
CALL GetEmployeeSalary(101, @empName, @bonus);  
SELECT @empName AS EmployeeName, @bonus AS SalaryWithBonus;
*/

/* JavaScript
Topic: JavaScript Closures

Explanation:
A closure is a function that retains access to its lexical environment even after the outer function has finished executing.  
It allows the inner function to read variables defined in the outer scope, preserving state between calls.  
Closures are created every time a function is defined, but they become especially useful for data privacy and function factories.  
Understanding closures helps avoid common pitfalls like unintentionally sharing mutable state.  
They are a core concept for asynchronous programming, module patterns, and functional composition in JavaScript.  

Code example with comments:
function outer(a) {               // outer function receives a parameter a
    let b = 2;                    // b is a local variable in the outer scope
    return function inner(c) {    // inner function forms a closure over a and b
        // this function can still access a and b even after outer returns
        return a + b + c;         // compute sum using variables from both scopes
    };
}
let fn = outer(5);                // call outer, creating a closure with a = 5
console.log(fn(3));               // invoke inner with c = 3, prints 10 (5+2+3)  
*/

/* AI
Topic: Few‑Shot Prompt Engineering with the OpenAI Chat Completion API

Explanation:
Few‑shot prompting demonstrates the model’s ability to learn a new task from just a handful of examples supplied in the prompt. By clearly formatting input‑output pairs and then presenting a new query, the model can infer the pattern and generate accurate responses. This technique works well for classification, transformation, and extraction tasks without any fine‑tuning. The key is to keep examples concise, consistent, and to use delimiters that the model can recognize. Proper prompt design can dramatically improve reliability and reduce token usage compared to longer zero‑shot prompts.

Code example (Python, using the openai library):
import openai

# Set your OpenAI API key
openai.api_key = "YOUR_API_KEY"

# Define a few‑shot prompt with clear delimiters
examples = [
    {"input": "Translate to French: Hello, how are you?", "output": "Bonjour, comment ça va ?"},
    {"input": "Translate to French: Good night!", "output": "Bonne nuit !"},
]

# Build the message list for the chat API
messages = [
    {"role": "system", "content": "You are a helpful assistant that translates English sentences to French."},
]

# Append the demonstration examples as user‑assistant turns
for ex in examples:
    messages.append({"role": "user", "content": ex["input"]})
    messages.append({"role": "assistant", "content": ex["output"]})

# New query that the model should answer using the learned pattern
new_query = "Translate to French: See you tomorrow."
messages.append({"role": "user", "content": new_query})

# Call the Chat Completion endpoint
response = openai.ChatCompletion.create(
    model="gpt-4o-mini",
    messages=messages,
    temperature=0.0  # deterministic output for translation tasks
)

# Extract and print the model's translation
translation = response.choices[0].message.content.strip()
print("Translation:", translation)   # Expected: "À demain."  
*/

