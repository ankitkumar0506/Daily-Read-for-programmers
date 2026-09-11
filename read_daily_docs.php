<?php
// 2026-09-11 06:22:53

/* PHP
Topic: PDO Prepared Statements for Secure Database Access  

Explanation:  
PDO (PHP Data Objects) provides a consistent interface for accessing different databases. Using prepared statements with PDO helps prevent SQL injection by separating query structure from data. Placeholders in the SQL are bound to variables, allowing the driver to safely escape values. PDO also offers error handling modes and supports transactions for reliable data operations. This approach makes your code more portable and secure across MySQL, PostgreSQL, SQLite, and other databases.  

Code Example (with comments):  

<?php
// Create a new PDO instance to connect to a MySQL database
$dsn = 'mysql:host=localhost;dbname=example_db;charset=utf8mb4';
$username = 'db_user';
$password = 'db_pass';

try {
    $pdo = new PDO($dsn, $username, $password);
    // Set error mode to exceptions for better error handling
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}

// Prepare an INSERT statement with named placeholders
$sql = 'INSERT INTO users (email, password_hash) VALUES (:email, :hash)';
$stmt = $pdo->prepare($sql);

// Bind values to the placeholders
$email = 'john.doe@example.com';
$hash  = password_hash('securePassword123', PASSWORD_DEFAULT);
$stmt->bindParam(':email', $email);
$stmt->bindParam(':hash', $hash);

// Execute the prepared statement
if ($stmt->execute()) {
    echo 'New user added successfully.';
} else {
    echo 'Failed to add user.';
}

// Example of a SELECT query using positional placeholders
$stmt = $pdo->prepare('SELECT id, email FROM users WHERE email = ?');
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    echo 'User ID: ' . $user['id'] . ', Email: ' . $user['email'];
} else {
    echo 'User not found.';
}
?>
*/

/* Laravel
Topic: Laravel Queues and Jobs

Explanation:  
Laravel queues allow time‑consuming tasks such as sending emails, processing images, or interacting with external APIs to be executed in the background, keeping web requests fast.  
A job is a small, self‑contained class that represents a unit of work to be processed by a queue worker.  
Queues can be driven by many drivers (database, Redis, SQS, etc.) and can be scaled horizontally by adding more workers.  
Jobs can be delayed, retried on failure, and can have custom timeout and connection settings.  
Using queues improves user experience and makes the application more resilient under load.

Code example (Job class and dispatching it):

<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\WelcomeMail;
use Mail;

/**
 * SendWelcomeEmail job will be processed by the queue worker.
 */
class SendWelcomeEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;   // User model instance to receive the email

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
        // Build and send the welcome email
        Mail::to($this->user->email)->send(new WelcomeMail($this->user));
    }
}

// Dispatching the job somewhere in a controller or service
use App\Jobs\SendWelcomeEmail;
use App\Models\User;

public function register(Request $request)
{
    // Validation and user creation logic here...
    $user = User::create($request->only(['name', 'email', 'password']));

    // Push the email job onto the default queue, it will run in the background
    SendWelcomeEmail::dispatch($user);

    // Return a response immediately without waiting for the email to be sent
    return response()->json(['message' => 'User registered successfully']);
}
?>
*/

/* MySQL
Topic: MySQL Transactions and Isolation Levels

Explanation:
A transaction groups a set of SQL statements so they are executed as a single unit of work. If any statement fails, the entire transaction can be rolled back, preserving data integrity. MySQL supports four isolation levels—READ UNCOMMITTED, READ COMMITTED, REPEATABLE READ, and SERIALIZABLE—that control how concurrently running transactions see each other's changes. The default isolation level is REPEATABLE READ, which prevents non‑repeatable reads but allows phantom rows unless locking reads are used. You can set the isolation level per session or per transaction using SET TRANSACTION. Proper use of transactions and isolation levels is essential for avoiding race conditions, dirty reads, and lost updates in multi‑user environments.

Code example with comments:
START TRANSACTION;                              -- Begin a new transaction
SET TRANSACTION ISOLATION LEVEL REPEATABLE READ;-- Set isolation level for this transaction

SELECT balance FROM accounts WHERE id = 101;   -- Read current balance (repeatable read ensures same result within transaction)
UPDATE accounts SET balance = balance - 100 WHERE id = 101;  -- Debit account
UPDATE accounts SET balance = balance + 100 WHERE id = 202;  -- Credit another account

IF ROW_COUNT() = 2 THEN                         -- Ensure both updates succeeded
    COMMIT;                                      -- Persist changes
ELSE
    ROLLBACK;                                    -- Undo all changes if any update failed
END IF;                                          -- End of conditional logic (requires a stored program context)
*/

/* JavaScript
Topic: Closures in JavaScript

Explanation:
A closure is a function that retains access to its lexical scope even when that function is executed outside of its original context. Closures enable data privacy, allowing inner functions to remember variables from an outer function after the outer function has finished running. They are created automatically whenever a function is defined inside another function and the inner function references variables from the outer scope. Closures are fundamental for patterns like module creation, currying, and asynchronous callbacks. Understanding closures helps avoid common pitfalls such as unintended memory retention or variable shadowing.

Code Example:
// Outer function creates a private counter variable
function createCounter(initialValue) {
    let count = initialValue;               // count is private to createCounter

    // Inner function forms a closure over `count`
    return function increment(step = 1) {
        count += step;                      // can modify the private variable
        console.log('Current count:', count);
        return count;
    };
}

// Using the closure
const counterA = createCounter(10);        // counterA has its own `count`
counterA();                                 // prints: Current count: 11
counterA(5);                                // prints: Current count: 16

const counterB = createCounter(0);         // a separate closure with its own `count`
counterB(2);                                // prints: Current count: 2
counterB();                                 // prints: Current count: 3

// The `count` variable is not directly accessible from the outside
// console.log(count); // ReferenceError: count is not defined

// This demonstrates how the inner function preserves access to the
// outer function's variables, forming a closure.
*/

/* AI
Topic: Few‑Shot Prompt Engineering with OpenAI’s Chat Completion API  

Explanation:  
Few‑shot prompting supplies the model with a small number of example input‑output pairs inside the prompt, guiding it toward the desired behavior without fine‑tuning.  
By framing the task as a conversation and inserting demonstrations, the model can infer the pattern and apply it to new inputs.  
This technique works well for classification, data extraction, and transformation tasks where a large labeled dataset is unavailable.  
The prompt typically includes a system message that defines the role, followed by user‑assistant exchanges that illustrate the pattern.  
Adjusting the number and clarity of examples can significantly affect accuracy and robustness.  

Code example (Python, using the openai library):  

import os  
import openai  

# Load your OpenAI API key from an environment variable  
openai.api_key = os.getenv("OPENAI_API_KEY")  

def classify_sentiment(text):  
    # Define the system instruction that sets the assistant’s role  
    system_msg = {"role": "system", "content": "You are a helpful assistant that classifies the sentiment of a short sentence as Positive, Negative, or Neutral."}  

    # Provide two demonstration examples (few‑shot)  
    examples = [  
        {"role": "user", "content": "I love the new design of the website!"},  
        {"role": "assistant", "content": "Positive"},  
        {"role": "user", "content": "The update broke the login process."},  
        {"role": "assistant", "content": "Negative"}  
    ]  

    # Append the actual user query after the examples  
    user_msg = {"role": "user", "content": text}  

    # Call the Chat Completion endpoint with all messages in order  
    response = openai.ChatCompletion.create(  
        model="gpt-4o-mini",  
        messages=[system_msg] + examples + [user_msg],  
        temperature=0.0  # deterministic output for classification  
    )  

    # Extract and return the model’s reply (the predicted sentiment)  
    return response.choices[0].message["content"].strip()  

# Example usage  
if __name__ == "__main__":  
    test_sentence = "The customer support was okay, nothing special."  
    sentiment = classify_sentiment(test_sentence)  
    print(f"Input: {test_sentence}")  
    print(f"Predicted sentiment: {sentiment}")  
*/

