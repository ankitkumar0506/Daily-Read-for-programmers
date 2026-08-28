<?php
// 2026-08-28 13:08:38

/* PHP
Topic: PDO Prepared Statements  

Explanation:  
PDO (PHP Data Objects) provides a consistent interface for accessing databases.  
Prepared statements separate the SQL query from its data, protecting against SQL injection.  
They allow the database engine to parse the query once and execute it multiple times with different parameters.  
Using PDO, you can bind values by name or position, and the driver handles the appropriate escaping.  
This approach improves security and can boost performance for repeated queries.  

Code example (with comments):  

<?php
// Create a new PDO instance (replace DSN, username, password with your credentials)
$dsn = 'mysql:host=localhost;dbname=testdb;charset=utf8mb4';
$username = 'dbuser';
$password = 'dbpass';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // Fetch results as associative arrays
];
$pdo = new PDO($dsn, $username, $password, $options);

// Prepare an INSERT statement with named placeholders
$sql = "INSERT INTO users (email, password_hash, created_at) VALUES (:email, :hash, NOW())";
$stmt = $pdo->prepare($sql);

// Bind values to the placeholders
$email = 'alice@example.com';
$passwordPlain = 'Secret123!';
$hash = password_hash($passwordPlain, PASSWORD_BCRYPT);
$stmt->bindParam(':email', $email);
$stmt->bindParam(':hash', $hash);

// Execute the prepared statement
$stmt->execute();

// Check how many rows were inserted
echo "Rows inserted: " . $stmt->rowCount() . PHP_EOL;

// Example of a SELECT with positional placeholders
$stmt = $pdo->prepare("SELECT id, email FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user) {
    echo "User ID: " . $user['id'] . " Email: " . $user['email'] . PHP_EOL;
} else {
    echo "No user found." . PHP_EOL;
}
?>
*/

/* Laravel
Topic: Laravel Queues and Jobs

Explanation:  
Laravel queues allow you to defer time‑consuming tasks such as sending emails, processing uploads, or generating reports to a background process. By pushing jobs onto a queue, the main request can return quickly while the worker handles the heavy lifting. Laravel supports multiple queue drivers (database, Redis, SQS, etc.) and provides a simple syntax to create and dispatch jobs. Jobs are plain PHP classes that implement the ShouldQueue interface and contain a handle method where the job logic resides. Workers listen to the queue and execute jobs, retrying failed attempts based on your configuration.

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

    protected $user;

    // The job receives the user instance when it is dispatched
    public function __construct($user)
    {
        $this->user = $user;
    }

    // This method is called by the queue worker
    public function handle()
    {
        // Send the welcome email using Laravel's Mail facade
        Mail::to($this->user->email)->send(new WelcomeMail($this->user));
    }
}

// Dispatching the job from a controller or service
// This will push the job onto the default queue connection
$user = User::find(1);
SendWelcomeEmail::dispatch($user);
*/

/* MySQL
Topic: Common Table Expressions (CTEs) and Recursive Queries

Explanation:  
A Common Table Expression (CTE) is a temporary result set that can be referenced within a SELECT, INSERT, UPDATE, or DELETE statement.  
CTEs are defined using the WITH clause and improve query readability by allowing you to break complex logic into named subqueries.  
When the WITH keyword is followed by RECURSIVE, the CTE can reference itself, enabling hierarchical or tree‑structured data processing.  
Recursive CTEs consist of an anchor member (the base case) and a recursive member that repeatedly joins the CTE to itself until a termination condition is met.  
They are useful for traversing organizational charts, bill‑of‑materials, or any data with parent‑child relationships without needing procedural code.

Code example (recursive CTE that lists an employee hierarchy):

WITH RECURSIVE employee_path (emp_id, emp_name, manager_id, level) AS
(
    -- Anchor member: start with top‑level managers (no manager)
    SELECT emp_id,
           emp_name,
           manager_id,
           1 AS level
    FROM employees
    WHERE manager_id IS NULL

    UNION ALL

    -- Recursive member: join each employee to its manager
    SELECT e.emp_id,
           e.emp_name,
           e.manager_id,
           ep.level + 1
    FROM employees e
    JOIN employee_path ep ON e.manager_id = ep.emp_id
)
SELECT emp_id,
       emp_name,
       manager_id,
       level
FROM employee_path
ORDER BY level, manager_id;
*/

/* JavaScript
Topic: JavaScript Closures

Explanation:  
A closure is created when an inner function retains access to variables from its outer (enclosing) function even after that outer function has finished executing. This allows the inner function to remember the lexical environment in which it was defined, enabling data encapsulation and private state. Closures are fundamental for patterns such as function factories, module patterns, and callbacks. Because the captured variables live on the heap rather than the stack, they persist for the lifetime of any references to the inner function. Understanding closures helps avoid common pitfalls like unintended shared state in loops.

Code example with comments:  
function makeCounter(initialValue) {  
    let count = initialValue;            // count is private to the closure  
    return function() {                 // the inner function forms the closure  
        count += 1;                      // it can read and modify count  
        return count;                   // returns the updated value  
    };  
}  

const counterA = makeCounter(0); // creates a new closure with its own count  
console.log(counterA()); // 1  
console.log(counterA()); // 2  

const counterB = makeCounter(10); // a separate closure, independent state  
console.log(counterB()); // 11  
console.log(counterA()); // 3 – counterA’s count continues where it left off  
*/

/* AI
Topic: Few‑Shot Prompt Engineering for Text Classification with Large Language Models  

Explanation:  
1. Few‑shot prompting supplies the model with a small number of labeled examples directly in the prompt, guiding it to infer the classification task without fine‑tuning.  
2. This technique leverages the model’s in‑context learning ability, making it fast to prototype new classifiers.  
3. The prompt typically contains a clear instruction, several example input‑output pairs, and then the new query.  
4. Selecting diverse, representative examples improves consistency and reduces bias.  
5. The approach works with any LLM that supports completion or chat endpoints, such as OpenAI’s gpt‑4o.  

Code example (Python, OpenAI API)  

import os  
import openai  

# Load your API key from an environment variable  
openai.api_key = os.getenv("OPENAI_API_KEY")  

def classify_text(text):  
    # Define a few‑shot prompt with two example classifications  
    prompt = (  
        "Classify the sentiment of the following sentences as Positive, Negative, or Neutral.\n\n"  
        "Sentence: I loved the new phone I bought today!\n"  
        "Sentiment: Positive\n\n"  
        "Sentence: The movie was okay, not great but not terrible.\n"  
        "Sentiment: Neutral\n\n"  
        f"Sentence: {text}\n"  
        "Sentiment:"  
    )  

    # Call the chat completion endpoint using the prompt as a system message  
    response = openai.ChatCompletion.create(  
        model="gpt-4o-mini",  
        messages=[{"role": "user", "content": prompt}],  
        temperature=0.0,            # deterministic output for classification  
        max_tokens=10               # we only need the label word  
    )  

    # Extract and return the model’s answer, stripping whitespace  
    sentiment = response.choices[0].message.content.strip()  
    return sentiment  

# Example usage  
sample = "The service was painfully slow and the staff were rude."  
print("Input:", sample)  
print("Predicted Sentiment:", classify_text(sample))  
*/

