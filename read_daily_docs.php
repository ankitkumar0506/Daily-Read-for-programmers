<?php
// 2026-08-21 02:45:36

/* PHP
Topic: Using PDO for Secure Database Access with Prepared Statements

Explanation:
PDO (PHP Data Objects) provides a uniform interface for accessing many different databases. It supports prepared statements, which separate SQL code from data, preventing SQL injection attacks. With PDO you can bind parameters by name or position, and the driver handles proper escaping. Error handling can be managed using exceptions for cleaner code. PDO also offers transaction support, making it easy to commit or roll back multiple queries as a single unit.

Code example (with comments):
<?php
// Create a new PDO instance for a MySQL database
$dsn = 'mysql:host=localhost;dbname=example_db;charset=utf8mb4';
$username = 'db_user';
$password = 'db_pass';
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Fetch results as associative arrays
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Use native prepared statements
];
$pdo = new PDO($dsn, $username, $password, $options);

// Prepare an INSERT statement with named placeholders
$sql = "INSERT INTO users (username, email, created_at) VALUES (:username, :email, NOW())";
$stmt = $pdo->prepare($sql);

// Bind values to the placeholders and execute the statement
$stmt->execute([
    ':username' => 'johndoe',
    ':email'    => 'john@example.com'
]);

// Prepare a SELECT statement with a positional placeholder
$sqlSelect = "SELECT id, username, email FROM users WHERE username = ?";
$stmtSelect = $pdo->prepare($sqlSelect);

// Execute with the parameter value
$stmtSelect->execute(['johndoe']);

// Fetch the result
$user = $stmtSelect->fetch();

if ($user) {
    echo "User ID: " . $user['id'] . "\n";
    echo "Username: " . $user['username'] . "\n";
    echo "Email: " . $user['email'] . "\n";
} else {
    echo "No user found.\n";
}
?>
*/

/* Laravel
Topic: Laravel Queues and Jobs

Explanation:  
Laravel queues allow you to defer time‑consuming tasks such as sending emails, processing images, or making API calls to a background worker. By pushing jobs onto a queue, the main request can return quickly while the heavy work is processed asynchronously. Laravel provides a unified API for many queue drivers (database, Redis, SQS, etc.) and a simple way to define job classes. Jobs can be delayed, retried, and failed jobs are automatically recorded for later inspection. Using queues improves user experience and helps scale applications without blocking HTTP requests.

Code example (Job class and dispatching):

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

    protected $user; // the user instance to email

    // Constructor receives the user model
    public function __construct($user)
    {
        $this->user = $user;
    }

    // The job logic that runs in the background
    public function handle()
    {
        // Build and send the welcome email
        Mail::to($this->user->email)->send(new WelcomeMail($this->user));
    }

    // Optional: specify number of retry attempts
    public function retryUntil()
    {
        return now()->addMinutes(10);
    }
}

// Dispatching the job from a controller or service
use App\Jobs\SendWelcomeEmail;

public function register(Request $request)
{
    $user = User::create($request->only('name', 'email', 'password'));

    // Push the job onto the default queue; it will be processed asynchronously
    SendWelcomeEmail::dispatch($user)->onQueue('emails');

    return response()->json(['message' => 'User registered, welcome email queued.']);
}
?>
*/

/* MySQL
Topic: MySQL Stored Procedures with IN, OUT, and INOUT Parameters  

Explanation:  
A stored procedure is a named set of SQL statements that can be invoked repeatedly.  
It can accept input parameters (IN), return values through output parameters (OUT), or both (INOUT).  
Using parameters makes the procedure flexible and allows complex logic to be encapsulated on the server side.  
Procedures improve performance by reducing network round‑trips and enable reuse of business rules.  
They can be called from applications, scripts, or other SQL statements.

Code example (with comments):
CREATE DATABASE IF NOT EXISTS demo_db;
USE demo_db;

-- Create a table to store employee salaries
CREATE TABLE IF NOT EXISTS employees (
    emp_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    salary DECIMAL(10,2) NOT NULL
);

-- Insert sample data
INSERT INTO employees (name, salary) VALUES
('Alice', 75000.00),
('Bob', 62000.00),
('Carol', 88000.00);

-- Drop the procedure if it already exists
DROP PROCEDURE IF EXISTS adjust_salary;

-- Create a stored procedure that adjusts an employee's salary
CREATE PROCEDURE adjust_salary (
    IN p_emp_id INT,                -- input: employee identifier
    IN p_increment_percent DECIMAL(5,2),  -- input: percentage increase
    OUT p_new_salary DECIMAL(10,2)  -- output: the new salary after adjustment
)
BEGIN
    DECLARE v_current_salary DECIMAL(10,2);

    -- Retrieve the current salary for the given employee
    SELECT salary INTO v_current_salary
    FROM employees
    WHERE emp_id = p_emp_id;

    -- Calculate the new salary
    SET p_new_salary = v_current_salary * (1 + p_increment_percent / 100);

    -- Update the employee record with the new salary
    UPDATE employees
    SET salary = p_new_salary
    WHERE emp_id = p_emp_id;
END;

-- Call the procedure for employee with ID 2, giving a 5% raise
CALL adjust_salary(2, 5.00, @updated_salary);

-- Retrieve the output value
SELECT @updated_salary AS new_salary_for_employee_2;

-- Verify the table reflects the change
SELECT * FROM employees;
*/

/* JavaScript
Topic: Closures in JavaScript

Explanation:
A closure is a function that retains access to its lexical scope even when that function is executed outside of its original context.  
Closures allow inner functions to remember the environment in which they were created, preserving variables from the outer function.  
They are created automatically each time a function is defined, and the retained variables remain alive as long as they are referenced.  
Closures are useful for data privacy, partial application, and creating function factories.  
Understanding closures helps avoid common pitfalls like unintentionally sharing mutable state between function calls.

Code Example:
function makeCounter(initialValue) {          // outer function creates a private variable
    let count = initialValue;                // this variable is captured by the inner function
    return function() {                     // the returned inner function forms a closure
        count += 1;                          // it can read and modify 'count' each call
        console.log(count);
    };
}

const counterA = makeCounter(0);   // each call to makeCounter produces a separate closure
const counterB = makeCounter(10);

counterA(); // prints 1
counterA(); // prints 2
counterB(); // prints 11
counterA(); // prints 3

// The variables 'count' inside counterA and counterB are independent because each closure has its own lexical environment.
*/

/* AI
Topic: Few-Shot Prompt Engineering with OpenAI’s Chat Completion API  

Explanation:  
Few‑shot prompting supplies the model with a handful of input‑output examples to shape its behavior without fine‑tuning. By embedding these examples directly in the system or user messages, you can guide the model to follow a specific format, adopt a particular tone, or solve a defined class of problems. This technique works well for tasks like data extraction, code generation, or custom Q&A where a full dataset for training is unavailable. The quality of the examples and their order strongly influence the results, so concise, representative cases are key. Using the Chat Completion endpoint, you can programmatically construct the prompt and retrieve the model’s response in real time.  

Code example (Python, using the openai library):  

import os  
import openai  

# Load your API key from an environment variable for security  
openai.api_key = os.getenv("OPENAI_API_KEY")  

# Define a few‑shot prompt that teaches the model to convert natural‑language dates to ISO format  
few_shot_examples = [  
    {"role": "user", "content": "Convert the date 'July 4, 2023' to ISO format."},  
    {"role": "assistant", "content": "2023-07-04"},  
    {"role": "user", "content": "Convert the date '12th February 1999' to ISO format."},  
    {"role": "assistant", "content": "1999-02-12"}  
]  

# New query that follows the same pattern  
new_query = {"role": "user", "content": "Convert the date 'March 15th, 2025' to ISO format."}  

# Combine the examples with the new query  
messages = few_shot_examples + [new_query]  

# Call the Chat Completion API  
response = openai.ChatCompletion.create(  
    model="gpt-4o-mini",          # lightweight model suitable for prompt‑engineering tasks  
    messages=messages,  
    temperature=0.0               # deterministic output for format‑sensitive tasks  
)  

# Extract and print the model’s answer  
answer = response.choices[0].message.content.strip()  
print("ISO date:", answer)   # Expected output: 2025-03-15
*/

