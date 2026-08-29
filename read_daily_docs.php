<?php
// 2026-08-29 08:07:44

/* PHP
Topic: PDO Prepared Statements for Secure Database Access

Explanation:  
PDO (PHP Data Objects) provides a consistent interface for accessing various databases. Using prepared statements with PDO helps prevent SQL injection by separating SQL code from data. Placeholders are used in the SQL query, and actual values are bound later. PDO also supports named and positional placeholders, giving flexibility in query design. Errors can be handled via exceptions for better debugging and reliability. This approach makes database interactions both secure and maintainable.

Code example:
// Database connection using PDO
<?php
$dsn = 'mysql:host=localhost;dbname=testdb;charset=utf8mb4';
$username = 'db_user';
$password = 'db_pass';

try {
    // Enable exceptions for error handling
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}

// Prepare an INSERT statement with named placeholders
$sql = "INSERT INTO users (username, email, password_hash) 
        VALUES (:username, :email, :password_hash)";
$stmt = $pdo->prepare($sql);

// Sample data to insert
$data = [
    ':username'      => 'johndoe',
    ':email'         => 'johndoe@example.com',
    ':password_hash' => password_hash('secret123', PASSWORD_DEFAULT)
];

// Execute the prepared statement with bound values
try {
    $stmt->execute($data);
    echo "User inserted successfully. ID: " . $pdo->lastInsertId();
} catch (PDOException $e) {
    echo "Insert failed: " . $e->getMessage();
}

// Example of a SELECT query using positional placeholders
$searchEmail = 'johndoe@example.com';
$selectSql = "SELECT id, username FROM users WHERE email = ?";
$selectStmt = $pdo->prepare($selectSql);
$selectStmt->execute([$searchEmail]);

while ($row = $selectStmt->fetch(PDO::FETCH_ASSOC)) {
    echo "User ID: {$row['id']}, Username: {$row['username']}\n";
}
?>
*/

/* Laravel
Laravel Service Container and Dependency Injection  

Explanation  
The Laravel service container is a powerful tool for managing class dependencies and performing dependency injection. It resolves objects automatically, allowing you to type‑hint classes in constructors or controller methods without manually instantiating them. By binding interfaces to concrete implementations, you can swap out underlying logic without changing the consuming code. This promotes a clean separation of concerns and makes testing easier through mocking. The container also supports contextual bindings, singleton lifetimes, and automatic injection of primitive values when needed.

Code Example  

// A simple interface that defines a contract for sending notifications
namespace App\Contracts;
interface Notifier
{
    public function send(string $message);
}

// A concrete implementation that sends notifications via email
namespace App\Services;
use App\Contracts\Notifier;
class EmailNotifier implements Notifier
{
    // The $mailer is injected by Laravel's container automatically
    public function __construct(\Illuminate\Mail\Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function send(string $message)
    {
        // Here we would build and send the email
        $this->mailer->raw($message, function ($mail) {
            $mail->to('user@example.com')
                 ->subject('Notification');
        });
    }
}

// Register the binding in a service provider
namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use App\Contracts\Notifier;
use App\Services\EmailNotifier;
class NotificationServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Bind the Notifier interface to the EmailNotifier implementation
        $this->app->bind(Notifier::class, EmailNotifier::class);
    }
}

// Using the bound Notifier in a controller via constructor injection
namespace App\Http\Controllers;
use App\Contracts\Notifier;
class MessageController extends Controller
{
    protected $notifier;

    // Laravel automatically resolves the EmailNotifier when Notifier is requested
    public function __construct(Notifier $notifier)
    {
        $this->notifier = $notifier;
    }

    public function store()
    {
        // Business logic ...

        // Send a notification without caring about the concrete class
        $this->notifier->send('A new message has been created.');
    }
}
*/

/* MySQL
Topic: Common Table Expressions (CTEs) and Recursive Queries

Explanation:  
Common Table Expressions allow you to define a temporary result set that can be referenced within a SELECT, INSERT, UPDATE, or DELETE statement.  
Using the WITH keyword, you can give the CTE a name and specify the query that builds it.  
When the CTE is marked RECURSIVE, it can refer to itself, enabling hierarchical or tree‑like data retrieval.  
Recursive CTEs consist of an anchor member (the starting rows) and a recursive member (how to walk the hierarchy).  
The query stops automatically when the recursive member returns no rows, preventing infinite loops.

Code example with comments:  
-- Create a sample table to store employees and their managers  
CREATE TABLE employees (  
    employee_id INT PRIMARY KEY,  
    manager_id  INT NULL,        -- NULL for top‑level managers  
    name        VARCHAR(50) NOT NULL  
);  

-- Insert sample data representing a simple org chart  
INSERT INTO employees (employee_id, manager_id, name) VALUES  
(1, NULL, 'Alice'),        -- CEO  
(2, 1,    'Bob'),          -- reports to Alice  
(3, 1,    'Carol'),        -- reports to Alice  
(4, 2,    'David'),        -- reports to Bob  
(5, 2,    'Eve'),          -- reports to Bob  
(6, 3,    'Frank');        -- reports to Carol  

-- Recursive CTE to retrieve the full management hierarchy with depth level  
WITH RECURSIVE emp_tree AS (  
    -- Anchor member: start with top‑level managers (no manager_id)  
    SELECT employee_id, manager_id, name, 1 AS level  
    FROM employees  
    WHERE manager_id IS NULL  
  
    UNION ALL  
  
    -- Recursive member: join each employee to their manager found in the previous level  
    SELECT e.employee_id, e.manager_id, e.name, et.level + 1  
    FROM employees e  
    JOIN emp_tree et ON e.manager_id = et.employee_id  
)  
SELECT employee_id, manager_id, name, level  
FROM emp_tree  
ORDER BY level, employee_id;   -- Result shows each employee with their depth in the hierarchy.
*/

/* JavaScript
Topic: Closures and the Module Pattern  

Explanation:  
A closure is a function that retains access to its lexical scope even after the outer function has finished executing. This feature lets you create private variables that cannot be accessed directly from outside the closure. The module pattern leverages closures to encapsulate implementation details while exposing a public API. By wrapping code in an immediately‑invoked function expression (IIFE), you create a self‑contained module with hidden state. This approach is useful for organizing code, preventing global namespace pollution, and mimicking private members in JavaScript.  

Code example with comments:  

// Define a module using an IIFE that creates a private counter variable
const Counter = (function () {
    // Private variable, not accessible from outside the IIFE
    let count = 0;

    // Expose an object with methods that can manipulate the private variable
    return {
        // Increments the private count and logs the new value
        increment: function () {
            count++;
            console.log('Current count:', count);
        },
        // Resets the private count to zero and logs the reset action
        reset: function () {
            count = 0;
            console.log('Counter has been reset');
        },
        // Returns the current count without modifying it
        getValue: function () {
            return count;
        }
    };
})();  

// Using the public API of the Counter module
Counter.increment();   // Current count: 1
Counter.increment();   // Current count: 2
console.log('Value is', Counter.getValue()); // Value is 2
Counter.reset();       // Counter has been reset
console.log('Value after reset', Counter.getValue()); // Value after reset 0
*/

/* AI
Topic: Few‑Shot Prompt Engineering with the OpenAI Completion API

Explanation:  
Few‑shot prompting supplies the model with a small number of example input‑output pairs before the actual query, guiding it toward the desired pattern.  
It works well for tasks like text classification, data extraction, or style transfer without fine‑tuning.  
By carefully selecting representative examples, you can improve consistency and reduce hallucinations.  
The prompt is constructed as a single string where each example is separated by newline characters, ending with the new query.  
This technique is language‑agnostic and can be used with any model that accepts a plain‑text prompt.

Code example (Python, using openai library):
import os
import openai

# Set your API key (ensure it is stored securely, e.g., as an environment variable)
openai.api_key = os.getenv("OPENAI_API_KEY")

# Define a few‑shot prompt for sentiment classification
examples = [
    "Review: I loved the movie, it was fantastic!\nSentiment: Positive",
    "Review: The food was terrible and the service was slow.\nSentiment: Negative",
    "Review: It was an okay experience, nothing special.\nSentiment: Neutral"
]

# The new input we want the model to classify
new_review = "Review: The plot was confusing, but the visuals were stunning.\nSentiment:"

# Combine examples and the new query into one prompt
prompt = "\n\n".join(examples) + "\n\n" + new_review

# Call the Completion API with a deterministic setting (temperature=0)
response = openai.Completion.create(
    engine="text-davinci-003",
    prompt=prompt,
    max_tokens=5,
    temperature=0,
    top_p=1,
    n=1,
    stop=None
)

# Extract and print the model's answer
sentiment = response.choices[0].text.strip()
print(f"Predicted sentiment: {sentiment}")
*/

