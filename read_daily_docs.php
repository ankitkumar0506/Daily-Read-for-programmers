<?php
// 2026-09-12 06:09:34

/* PHP
Topic: Using Prepared Statements with PDO for Secure Database Queries

Explanation:
Prepared statements separate SQL logic from data, preventing SQL injection attacks.  
PDO (PHP Data Objects) provides a uniform interface for interacting with many database systems.  
You first prepare the SQL query with placeholders, then bind values and execute.  
This approach also improves performance when executing the same query multiple times.  
Error handling can be managed with exceptions to catch any database issues.

Code example (PHP):
<?php
// Enable exceptions for PDO errors
$dsn = 'mysql:host=localhost;dbname=testdb;charset=utf8mb4';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    // Create a new PDO instance
    $pdo = new PDO($dsn, 'username', 'password', $options);
    
    // Prepare an INSERT statement with named placeholders
    $stmt = $pdo->prepare(
        'INSERT INTO users (email, password_hash, created_at) VALUES (:email, :pwd, NOW())'
    );
    
    // Sample data to insert
    $email = 'user@example.com';
    $password = 'SecretPass123';
    // Hash the password securely
    $hash = password_hash($password, PASSWORD_DEFAULT);
    
    // Bind values to the placeholders and execute the statement
    $stmt->execute([
        ':email' => $email,
        ':pwd'   => $hash,
    ]);
    
    echo "User inserted successfully.";
} catch (PDOException $e) {
    // Handle any errors that occur during the connection or query
    echo 'Database error: ' . $e->getMessage();
}
?>
*/

/* Laravel
Topic: Laravel Queues

Explanation:  
Laravel queues allow you to defer time‑consuming tasks such as sending emails, processing uploads, or generating reports to a background process.  
Jobs are placed onto a queue driver (database, Redis, SQS, etc.) and processed by a queue worker, keeping web requests fast.  
You can configure multiple queues with different priorities, and each job class defines a handle method that contains the work logic.  
Failed jobs are automatically recorded, and Laravel provides tools to retry or inspect them.  
Using queues also enables horizontal scaling by running several workers across multiple servers.

Code example (a simple email sending job):

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

    protected $user; // the user instance to receive the email

    // The job receives the user when it is dispatched
    public function __construct($user)
    {
        $this->user = $user;
    }

    // This method is executed by the queue worker
    public function handle()
    {
        // Build the mailable and send it
        Mail::to($this->user->email)->send(new WelcomeMail($this->user));
    }

    // Optional: specify a connection or queue name
    public function viaQueue()
    {
        return 'emails';
    }
}

// Dispatching the job from a controller or service
// The job will be pushed onto the default queue driver
SendWelcomeEmail::dispatch($user);
?>
*/

/* MySQL
Topic: MySQL Stored Procedures and Variables

Explanation:
Stored procedures allow you to encapsulate a set of SQL statements for reuse and better security.  
They can accept input parameters, return output parameters, and contain control‑flow logic such as IF, LOOP, and WHILE.  
Local variables inside a procedure are declared with the DECLARE statement and exist only for the duration of the call.  
Using stored procedures reduces network round‑trips because multiple statements execute on the server side.  
They also help enforce business rules and can be granted specific execution privileges independent of underlying tables.  

Code example (with comments):
CREATE DATABASE IF NOT EXISTS demo_db;
USE demo_db;

-- Create a simple table to work with
CREATE TABLE IF NOT EXISTS employees (
    emp_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    salary DECIMAL(10,2)
);

-- Insert sample data
INSERT INTO employees (first_name, last_name, salary) VALUES
('Alice', 'Smith', 72000.00),
('Bob', 'Johnson', 85000.00),
('Carol', 'Lee', 64000.00);

-- Drop the procedure if it already exists
DROP PROCEDURE IF EXISTS GetHighEarners;

-- Define a stored procedure that returns employees earning above a given threshold
DELIMITER $$

CREATE PROCEDURE GetHighEarners (IN min_salary DECIMAL(10,2))
BEGIN
    -- Declare a local variable to count the rows returned
    DECLARE emp_count INT DEFAULT 0;

    -- Select employees with salary greater than the input parameter
    SELECT emp_id, first_name, last_name, salary
    FROM employees
    WHERE salary > min_salary;

    -- Get the number of rows found and store it in the local variable
    GET DIAGNOSTICS CONDITION 1 emp_count = ROW_COUNT;

    -- Return the count as an additional result set
    SELECT CONCAT('Total high earners: ', emp_count) AS summary;
END$$

DELIMITER ;

-- Call the procedure with a salary threshold of 70000
CALL GetHighEarners(70000.00);
*/

/* JavaScript
Topic: Closures in JavaScript

Explanation:  
A closure is a function that retains access to the variables of its outer (enclosing) function even after that outer function has finished executing. This happens because the inner function forms a lexical environment that includes the outer scope’s variables. Closures enable data privacy, function factories, and the ability to maintain state across multiple calls without using global variables. They are created every time a function is defined, but they only become useful when the inner function is returned or passed elsewhere. Understanding closures is essential for mastering asynchronous patterns, callbacks, and module design in JavaScript.

Code Example:
// A function that creates a counter using a closure
function createCounter(initialValue) {
    // This variable is private to the closure
    let count = initialValue;

    // The inner function forms a closure over 'count'
    return function increment(step = 1) {
        // It can read and modify 'count' each time it's called
        count += step;
        return count;
    };
}

// Using the closure
const counter = createCounter(5);   // start at 5
console.log(counter());            // 6
console.log(counter(2));           // 8
console.log(counter());            // 9

// The 'count' variable is not accessible directly
// console.log(count); // Uncaught ReferenceError: count is not defined   (uncommenting this line would cause an error)
*/

/* AI
Topic: Prompt Engineering for Few‑Shot Learning with Large Language Models  

Explanation:  
Few‑shot prompting lets a model infer a new task from just a handful of examples embedded in the prompt. By carefully formatting the examples and the instruction, you guide the model’s reasoning and improve output quality. This technique is especially useful when fine‑tuning data is scarce or when rapid prototyping is needed. The prompt typically includes a clear task description, several input‑output pairs as demonstrations, and a placeholder for the new input. Adjusting delimiters, ordering, and the level of detail can significantly affect performance.

Code example (Python, OpenAI API) with comments:

import os
import openai

# Load your API key from an environment variable
openai.api_key = os.getenv("OPENAI_API_KEY")

# Define a few‑shot prompt for translating English to French
prompt = """Task: Translate the following English sentences into French.
Example 1:
English: I love programming.
French: J'adore programmer.

Example 2:
English: The weather is nice today.
French: Il fait beau aujourd'hui.

Now translate:
English: {input_sentence}
French:"""

def translate_to_french(sentence):
    # Insert the user sentence into the prompt
    filled_prompt = prompt.format(input_sentence=sentence)

    # Call the chat completion endpoint with the filled prompt
    response = openai.ChatCompletion.create(
        model="gpt-4o-mini",          # Choose a suitable model
        messages=[{"role": "user", "content": filled_prompt}],
        temperature=0.2,               # Low temperature for deterministic output
        max_tokens=60
    )
    # Extract and return the model's translation
    return response.choices[0].message.content.strip()

# Example usage
english_sentence = "She will arrive tomorrow morning."
french_translation = translate_to_french(english_sentence)
print("English:", english_sentence)
print("French :", french_translation)
*/

