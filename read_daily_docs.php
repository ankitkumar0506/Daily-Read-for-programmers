<?php
// 2026-09-24 06:28:31

/* PHP
Topic: PDO Prepared Statements  

Explanation:  
Prepared statements separate SQL code from data, preventing malicious input from altering query structure.  
They are parsed and compiled by the database server once, then executed multiple times with different parameters.  
Using PDO’s bindParam or bindValue methods ensures that values are properly escaped and typed.  
This approach improves performance for repeated queries and provides a consistent API across many database drivers.  
Adopting prepared statements is a core practice for building secure and maintainable PHP applications.  

Code example:  
<?php
// Create a new PDO instance (replace DSN, username, password with your own values)
$pdo = new PDO('mysql:host=localhost;dbname=testdb;charset=utf8mb4', 'dbuser', 'dbpass');

// Enable exceptions for error handling
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Define an SQL statement with named placeholders
$sql = 'SELECT id, name, email FROM users WHERE status = :status AND created_at > :date';

// Prepare the statement once
$stmt = $pdo->prepare($sql);

// Bind values to the placeholders (type safety and automatic escaping)
$status = 'active';
$date   = '2023-01-01';
$stmt->bindParam(':status', $status, PDO::PARAM_STR);
$stmt->bindParam(':date',   $date,   PDO::PARAM_STR);

// Execute the prepared statement
$stmt->execute();

// Fetch all matching rows as an associative array
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Output results
foreach ($users as $user) {
    echo "ID: {$user['id']} - Name: {$user['name']} - Email: {$user['email']}\n";
}
?>
*/

/* Laravel
Laravel Queues and Jobs  

Laravel queues allow you to defer time‑consuming tasks (such as sending emails, processing images, or API calls) to a background process, keeping web requests fast and responsive.  
You define a job class that contains the logic to be executed, then push the job onto a queue driver (database, Redis, SQS, etc.).  
A queue worker runs continuously, pulling jobs from the queue and executing their handle method.  
If a job fails, Laravel can automatically retry it a configurable number of times and move it to a failed_jobs table for later inspection.  
Using queues also enables you to scale processing horizontally by adding more workers without changing your application code.  

Example – creating and dispatching a job that sends a welcome email  

<?php  
namespace App\Jobs;  

use App\Mail\WelcomeMail;  
use Illuminate\Bus\Queueable;  
use Illuminate\Contracts\Queue\ShouldQueue;  
use Illuminate\Foundation\Bus\Dispatchable;  
use Illuminate\Queue\InteractsWithQueue;  
use Illuminate\Queue\SerializesModels;  
use Illuminate\Support\Facades\Mail;  

class SendWelcomeEmail implements ShouldQueue  
{  
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;  

    protected $user; // The user instance that will receive the email  

    /**  
     * Create a new job instance.  
     *  
     * @param  \App\Models\User  $user  
     * @return void  
     */  
    public function __construct($user)  
    {  
        $this->user = $user; // Store the user for later use in handle()  
    }  

    /**  
     * Execute the job.  
     *  
     * @return void  
     */  
    public function handle()  
    {  
        // Build the mailable and send it via the Mail facade  
        Mail::to($this->user->email)->send(new WelcomeMail($this->user));  
    }  
}  

// Dispatching the job from a controller or any other place  

use App\Jobs\SendWelcomeEmail;  

public function register(Request $request)  
{  
    $user = User::create($request->all()); // Create the new user  

    // Push the job onto the default queue; it will be processed asynchronously  
    SendWelcomeEmail::dispatch($user);  

    return response()->json(['message' => 'Registration successful, welcome email will be sent shortly.']);  
}  
*/

/* MySQL
Topic: Composite Indexes in MySQL

Explanation:  
A composite index is an index that covers two or more columns of a table. MySQL can use the leftmost prefix of the indexed columns to satisfy queries, so the order of columns in the index matters. Composite indexes are especially useful for queries that filter on multiple columns together, improving read performance without needing separate single‑column indexes. However, they increase write overhead and storage usage, so they should be created only when the query patterns justify them. Understanding the selectivity of each column helps decide the optimal column order in the composite index.

Code example with comments:

CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    order_date DATE NOT NULL,
    status ENUM('pending','shipped','delivered','canceled') NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    INDEX idx_customer_date_status (customer_id, order_date, status)   -- composite index on three columns
);

-- Query that can benefit from the composite index above
SELECT order_id, total_amount
FROM orders
WHERE customer_id = 42
  AND order_date BETWEEN '2024-01-01' AND '2024-01-31'
  AND status = 'shipped';

-- Use EXPLAIN to verify that the index is being used
EXPLAIN SELECT order_id, total_amount
FROM orders
WHERE customer_id = 42
  AND order_date BETWEEN '2024-01-01' AND '2024-01-31'
  AND status = 'shipped';
*/

/* JavaScript
Topic: JavaScript Closures

Explanation:  
A closure is a function that retains access to variables from its lexical scope even after that outer function has finished executing. It allows the inner function to remember the environment in which it was created, enabling data privacy and function factories. Closures are created automatically whenever a function accesses a variable defined outside its own scope. They are essential for patterns like module encapsulation, partial application, and maintaining state across multiple calls. Understanding closures helps avoid common pitfalls with asynchronous code and loops.

Code Example with comments:  
function makeCounter() {                     // outer function creates a private variable
    let count = 0;                           // this variable is captured by the inner function
    return function() {                     // the inner function forms a closure over count
        count++;                             // modify the private variable each call
        console.log('Current count:', count); // output the current value
    };
}
const counter = makeCounter();               // counter now holds the closure
counter();                                   // Current count: 1
counter();                                   // Current count: 2
counter();                                   // Current count: 3

// Even if we create another counter, it has its own independent count
const anotherCounter = makeCounter();
anotherCounter();                            // Current count: 1
counter();                                   // Current count: 4   (original counter continues)
*/

/* AI
Topic: Prompt Engineering for Few‑Shot Learning with OpenAI’s GPT‑4 API  

Explanation:  
Few‑shot prompting lets you teach a language model a new task by providing a handful of example input‑output pairs directly in the prompt. By carefully formatting these examples and using clear delimiters, the model can infer the desired pattern without any fine‑tuning. This technique is especially useful when you have limited labeled data or need rapid prototyping. Including a concise instruction line before the examples improves consistency. The final user query is appended after the examples, and the model returns the predicted output in the same format.  

Code example (Python, using the openai library):  

import openai  

# Set your API key (ensure it is stored securely)  
openai.api_key = "YOUR_API_KEY"  

def few_shot_completion(user_input):  
    # Define the system instruction and few‑shot examples  
    prompt = """You are a helpful assistant that converts natural language descriptions of arithmetic expressions into valid Python code.  

Example 1:  
Input: "Add 7 and 3 then multiply the result by 2."  
Output: "((7 + 3) * 2)"  

Example 2:  
Input: "Subtract 5 from 20 and divide by 3."  
Output: "((20 - 5) / 3)"  

Now convert the following request:  
Input: "{}"  
Output:""".format(user_input)  

    # Call the OpenAI ChatCompletion endpoint  
    response = openai.ChatCompletion.create(  
        model="gpt-4",  
        messages=[{"role": "user", "content": prompt}],  
        temperature=0.0,        # deterministic output  
        max_tokens=64,  
    )  

    # Extract the generated text (strip leading/trailing whitespace)  
    return response.choices[0].message.content.strip()  

# Example usage  
query = "Multiply 4 by the sum of 9 and 2."  
print(f"Prompt input: {query}")  
print("Generated Python expression:", few_shot_completion(query))  
*/

